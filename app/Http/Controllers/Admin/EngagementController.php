<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Engagement;
use App\Models\Budget;
use App\Exports\EngagementsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Notifications\EngagementStatusNotification;

class EngagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $annee = $request->get('annee', '');
        $emetteur_id = $request->get('emetteur_id', '');
        $ligne_id = $request->get('ligne_id', '');

        $annees = Budget::select('annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');
        $emetteurs = \App\Models\Emetteur::with('user')->get();
        $lignes = \App\Models\LigneBudgetaire::all();
        
        $query = Engagement::with(['emetteur.user', 'fournisseur', 'ligneProposee.ligne.budget'])
            ->where('statut', '=', $status);

        if ($annee) {
            $query->whereHas('ligneProposee.ligne.budget', function ($q) use ($annee) {
                $q->where('annee', $annee);
            });
        }

        if ($emetteur_id) {
            $query->where('emetteur_id', $emetteur_id);
        }

        if ($ligne_id) {
            $query->whereHas('ligneProposee', function ($q) use ($ligne_id) {
                $q->where('ligne_id', $ligne_id);
            });
        }

        $engagements = $query->latest()->get();

        // Resolve selected budget for official exports
        $selectedBudget = $annee
            ? Budget::where('annee', $annee)->orderByDesc('created_at')->first()
            : Budget::orderByDesc('annee')->orderByDesc('created_at')->first();

        $hasApprovedEngagements = false;
        if ($selectedBudget) {
            $hasApprovedEngagements = Engagement::where('statut', 'approuve')
                ->whereHas('ligneProposee.ligne', function($q) use ($selectedBudget) {
                    $q->where('budget_id', $selectedBudget->id);
                })->exists();
        }
            
        return view('admin.engagement.index', compact(
            'engagements', 'status',
            'annees', 'annee', 'emetteurs', 'emetteur_id', 'lignes', 'ligne_id',
            'selectedBudget', 'hasApprovedEngagements'
        ));
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('statut');
        $saison = $request->get('saison');
        $annee = $request->get('annee');
        $emetteur_id = $request->get('emetteur_id');
        $ligne_id = $request->get('ligne_id');

        $filename = 'expression_besoins_' . ($saison ? "{$saison}_" : '') . date('Ymd_Hi') . '.xlsx';
        return (new EngagementsExport($saison, $status, $annee, $emetteur_id, $ligne_id))
            ->download($filename);
    }

    public function exportPdf(Request $request)
    {
        $status = $request->get('statut');
        $saison = $request->get('saison');
        $annee = $request->get('annee');
        $emetteur_id = $request->get('emetteur_id');
        $ligne_id = $request->get('ligne_id');

        // Reuse the logic from EngagementsExport
        $export = new EngagementsExport($saison, $status, $annee, $emetteur_id, $ligne_id);
        $engagements = $export->collection();

        $pdf = Pdf::loadView('pdf.engagements_export', compact('engagements', 'status', 'saison', 'annee'))
            ->setPaper('a4', 'landscape');

        $filename = 'expression_besoins_' . ($saison ? "{$saison}_" : '') . date('Ymd_Hi') . '.pdf';
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

        $filename = 'EB-' . str_pad($engagement->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function approve($id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cette requête a déjà été traitée.');
        }

        $engagement->update([
            'statut' => 'approuve',
            'motif_refus' => null,
        ]);
        
        $engagement->emetteur->user->notify(new EngagementStatusNotification($engagement));
        
        return back()->with('success', 'Expressions de besoins approuvée avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cette requête a déjà été traitée.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string',
        ]);

        $engagement->update([
            'statut' => 'rejete',
            'motif_refus' => $validated['motif_refus'],
        ]);
        
        $engagement->emetteur->user->notify(new EngagementStatusNotification($engagement));
        
        return back()->with('success', 'Expressions de besoins rejetée.');
    }

    public function setTva(Request $request, $id)
    {
        $validated = $request->validate([
            'tva' => 'required|numeric|min:0'
        ]);

        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->statut !== 'en_attente') {
            return back()->with('error', 'Cette expressions de besoins a déjà été traitée.');
        }

        $engagement->tva = $validated['tva'];
        $engagement->calculerTotal();
        $engagement->save();

        return back()->with('success', 'TVA mise à jour avec recalcul du Total TTC.');
    }
}
