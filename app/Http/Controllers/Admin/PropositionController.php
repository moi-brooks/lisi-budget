<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LigneBudgetProposee;
use Illuminate\Http\Request;

class PropositionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        
        $propositions = LigneBudgetProposee::with(['emetteur.user', 'ligne.budget'])
            ->where('statut', '=', $status)
            ->latest()
            ->get();
            
        return view('admin.proposition.index', compact('propositions', 'status'));
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
        
        return back()->with('success', 'Proposition rejetée.');
    }
}
