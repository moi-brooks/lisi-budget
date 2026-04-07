<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Engagement;
use App\Models\Budget;
use App\Exports\EngagementsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $saison = $request->get('saison', '');

        $saisons = Budget::select('saison')->distinct()->orderBy('saison', 'desc')->pluck('saison');
        
        $query = Engagement::with(['emetteur.user', 'fournisseur', 'ligneProposee.ligne.budget'])
            ->where('statut', '=', $status);

        if ($saison) {
            $query->whereHas('ligneProposee.ligne.budget', function ($q) use ($saison) {
                $q->where('saison', $saison);
            });
        }

        $engagements = $query->latest()->get();
            
        return view('admin.engagement.index', compact('engagements', 'status', 'saisons', 'saison'));
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('statut');
        $saison = $request->get('saison');

        $filename = 'engagements_' . ($saison ? "{$saison}_" : '') . date('Ymd_Hi') . '.xlsx';
        return (new EngagementsExport($saison, $status))->download($filename);
    }

    public function exportPdf(Request $request)
    {
        $status = $request->get('statut');
        $saison = $request->get('saison');

        // Reuse the logic from EngagementsExport
        $export = new EngagementsExport($saison, $status);
        $engagements = $export->collection();

        $pdf = Pdf::loadView('pdf.engagements_export', compact('engagements', 'status', 'saison'))
            ->setPaper('a4', 'landscape');

        $filename = 'engagements_' . ($saison ? "{$saison}_" : '') . date('Ymd_Hi') . '.pdf';
        return $pdf->download($filename);
    }

    public function show($id)
    {
        $engagement = Engagement::with(['emetteur.user', 'besoins', 'ligneProposee.ligne', 'fournisseur'])
            ->findOrFail($id);

        return view('admin.engagement.show', compact('engagement'));
    }

    public function download($id)
    {
        $engagement = Engagement::with(['emetteur.user', 'fournisseur', 'besoins', 'ligneProposee.ligne'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.bon_commande', compact('engagement'))
            ->setPaper('a4', 'portrait');

        $filename = 'BC-' . str_pad($engagement->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function approve($id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cet engagement a déjà été traité.');
        }

        $engagement->update([
            'statut' => 'approuve',
            'motif_refus' => null,
        ]);
        
        return back()->with('success', 'Bon de commande approuvé avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cet engagement a déjà été traité.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string',
        ]);

        $engagement->update([
            'statut' => 'rejete',
            'motif_refus' => $validated['motif_refus'],
        ]);
        
        return back()->with('success', 'Bon de commande rejeté.');
    }

    public function setTva(Request $request, $id)
    {
        $validated = $request->validate([
            'tva' => 'required|numeric|min:0'
        ]);

        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cet engagement a déjà été traité.');
        }

        $engagement->tva = $validated['tva'];
        $engagement->calculerTotal();
        $engagement->save();

        return back()->with('success', 'TVA mise à jour avec recalcul du Total TTC.');
    }
}
