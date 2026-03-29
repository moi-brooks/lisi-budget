<?php

namespace App\Http\Controllers\Emetteur;

use App\Http\Controllers\Controller;
use App\Models\LigneBudgetaire;
use App\Models\LigneBudgetProposee;
use Illuminate\Http\Request;

class LigneProposeeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $emetteur = auth()->user()->emetteur;

        if (!$emetteur) {
            return redirect()->route('emetteur.dashboard')->with('error', 'Profil émetteur non trouvé.');
        }

        $propositions = $emetteur->lignesProposees()
            ->with('ligne')
            ->where('statut', $status)
            ->latest()
            ->get();

        return view('emetteur.ligne.index', compact('propositions', 'status'));
    }

    public function create()
    {
        $emetteur = auth()->user()->emetteur;
        if (!$emetteur) return redirect()->route('emetteur.dashboard');

        // Can only propose on lines for the active budget of this emetteur
        $lignes = LigneBudgetaire::where('budget_id', $emetteur->budget_id)->get();
        return view('emetteur.ligne.create', compact('lignes'));
    }

    public function store(Request $request)
    {
        $emetteur = auth()->user()->emetteur;

        $validated = $request->validate([
            'ligne_budgetaire_id' => 'required|exists:ligne_budgetaires,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        // Check reliquat
        if ($validated['montant'] > $emetteur->reliquat) {
            return back()->withInput()->withErrors(['montant' => 'Le montant dépasse votre reliquat disponible.']);
        }

        $emetteur->lignesProposees()->create($validated);

        return redirect()->route('emetteur.lignes.index')->with('success', 'Proposition soumise avec succès.');
    }

    public function edit(LigneBudgetProposee $ligne)
    {
        if ($ligne->emetteur_id !== auth()->user()->emetteur->id) {
            abort(403);
        }

        if ($ligne->statut === 'approuve') {
            return back()->with('error', 'Impossible de modifier une proposition approuvée.');
        }

        $lignes = LigneBudgetaire::where('budget_id', auth()->user()->emetteur->budget_id)->get();
        return view('emetteur.ligne.edit', compact('ligne', 'lignes'));
    }

    public function update(Request $request, LigneBudgetProposee $ligne)
    {
        $emetteur = auth()->user()->emetteur;

        if ($ligne->emetteur_id !== $emetteur->id || $ligne->statut === 'approuve') {
            abort(403);
        }

        $validated = $request->validate([
            'ligne_budgetaire_id' => 'required|exists:ligne_budgetaires,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        // Check reliquat (ignoring old amount)
        $reliquatSansLigne = $emetteur->dotation - $emetteur->lignesProposees()
            ->where('id', '!=', $ligne->id)
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->sum('montant');

        if ($validated['montant'] > $reliquatSansLigne) {
            return back()->withInput()->withErrors(['montant' => 'Le montant dépasse votre reliquat disponible.']);
        }

        // If it was rejected, a new edit puts it back to en_attente
        $validated['statut'] = 'en_attente';
        $validated['motif_refus'] = null;

        $ligne->update($validated);

        return redirect()->route('emetteur.lignes.index')->with('success', 'Proposition mise à jour et soumise.');
    }

    public function destroy(LigneBudgetProposee $ligne)
    {
        if ($ligne->emetteur_id !== auth()->user()->emetteur->id || $ligne->statut === 'approuve') {
            abort(403);
        }

        $ligne->delete();
        return back()->with('success', 'Proposition supprimée.');
    }
}
