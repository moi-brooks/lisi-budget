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

        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.lignes.index')->with('error', 'L\'exercice budgétaire est clôturé. Aucune nouvelle soumission possible.');
        }

        // Can only propose on lines for the active budget of this emetteur
        $lignes = LigneBudgetaire::where('budget_id', $emetteur->budget_id)->get();
        return view('emetteur.ligne.create', compact('lignes'));
    }

    public function store(Request $request)
    {
        $emetteur = auth()->user()->emetteur;

        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.lignes.index')->with('error', 'L\'exercice budgétaire est clôturé.');
        }

        $validated = $request->validate([
            'propositions' => 'required|array|min:1|max:10',
            'propositions.*.ligne_budgetaire_id' => 'required|exists:ligne_budgetaires,id',
            'propositions.*.description' => 'nullable|string|max:255',
            'propositions.*.montant' => 'required|numeric|min:0.01',
        ]);

        $totalAProposer = collect($validated['propositions'])->sum('montant');
        $reliquatDisponible = $emetteur->reliquat;

        if ($totalAProposer > $reliquatDisponible) {
            return back()->withInput()->withErrors(['global' => 'Le montant total dépasse votre reliquat disponible.']);
        }

        foreach ($validated['propositions'] as $prop) {
            // Optional: check individual line status if user wants uniqueness per line category
            // But usually research can have multiple items for the same category (e.g. 2 different laptops in 'petit materiel')
            $emetteur->lignesProposees()->create($prop);
        }

        return redirect()->route('emetteur.lignes.index')->with('success', count($validated['propositions']) . ' proposition(s) soumise(s) avec succès.');
    }

    public function edit(LigneBudgetProposee $ligne)
    {
        $emetteur = auth()->user()->emetteur;
        if ($ligne->emetteur_id !== $emetteur->id) {
            abort(403);
        }

        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.lignes.index')->with('error', 'L\'exercice budgétaire est clôturé. Modification impossible.');
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

        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.lignes.index')->with('error', 'L\'exercice budgétaire est clôturé.');
        }

        $validated = $request->validate([
            'ligne_budgetaire_id' => 'required|exists:ligne_budgetaires,id',
            'description' => 'nullable|string|max:255',
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

        return redirect()->route('emetteur.lignes.index')->with('success', 'Proposition mise à jour.');
    }

    public function destroy(LigneBudgetProposee $ligne)
    {
        $emetteur = auth()->user()->emetteur;
        if ($ligne->emetteur_id !== $emetteur->id || $ligne->statut === 'approuve') {
            abort(403);
        }

        if ($emetteur->budget->is_closed) {
            return back()->with('error', 'L\'exercice budgétaire est clôturé.');
        }

        $ligne->delete();
        return back()->with('success', 'Proposition supprimée.');
    }
}
