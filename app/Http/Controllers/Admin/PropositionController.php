<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LigneBudgetProposee;
use App\Models\Budget;
use App\Exports\PropositionsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Notifications\PropositionStatusNotification;

class PropositionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $annee = $request->get('annee', '');

        $annees = Budget::select('annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');

        $query = LigneBudgetProposee::with(['emetteur.user', 'ligne.budget'])
            ->where('statut', '=', $status);

        if ($annee) {
            $query->whereHas('ligne.budget', function ($q) use ($annee) {
                $q->where('annee', $annee);
            });
        }

        $propositions = $query->latest()->get();

        return view('admin.proposition.index', compact('propositions', 'status', 'annees', 'annee'));
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('statut');
        $annee = $request->get('annee');

        $filename = 'propositions_' . ($annee ? "{$annee}_" : '') . date('Ymd_Hi') . '.xlsx';
        return (new PropositionsExport($annee, $status))->download($filename);
    }

    public function exportPdf(Request $request)
    {
        $status = $request->get('statut');
        $annee = $request->get('annee');

        $export = new PropositionsExport($annee, $status);
        $propositions = $export->collection();

        $pdf = Pdf::loadView('pdf.propositions_export', compact('propositions', 'status', 'annee'))
            ->setPaper('a4', 'landscape');

        $filename = 'propositions_' . ($annee ? "{$annee}_" : '') . date('Ymd_Hi') . '.pdf';
        return $pdf->download($filename);
    }

    public function approve($id)
    {
        $proposition = LigneBudgetProposee::findOrFail($id);
        
        if ($proposition->statut !== 'en_attente') {
            return back()->with('error', 'Cette proposition a déjà été traitée.');
        }

        // Validate dotation (optional strict check, could just approve)
        $emetteur = $proposition->emetteur;
        $totalEngage = $emetteur->lignesProposees()->whereIn('statut', ['en_attente', 'approuve'])->sum('montant');
        
        // Approving doesn't change the theoretical sum of en_attente + approuve, but validating is good
        // Actually the emetteur should not be able to propose more than their dotation anyway
        
        $proposition->update([
            'statut' => 'approuve',
            'motif_refus' => null,
            'validated_at' => now(),
        ]);
        
        $proposition->emetteur->user->notify(new PropositionStatusNotification($proposition));
        
        return back()->with('success', 'Proposition approuvée avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $proposition = LigneBudgetProposee::findOrFail($id);
        
        if ($proposition->statut !== 'en_attente') {
            return back()->with('error', 'Cette proposition a déjà été traitée.');
        }

        $validated = $request->validate([
            'motif_refus' => 'required|string|min:10',
        ]);

        $proposition->update([
            'statut' => 'rejete',
            'motif_refus' => $validated['motif_refus'],
        ]);
        
        $proposition->increment('nb_refus');
        
        $proposition->emetteur->user->notify(new PropositionStatusNotification($proposition));
        
        return back()->with('success', 'Proposition rejetée.');
    }
}
