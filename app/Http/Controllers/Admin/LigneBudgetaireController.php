<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\LigneBudgetaire;
use Illuminate\Http\Request;

class LigneBudgetaireController extends Controller
{
    public function index(Budget $budget)
    {
        $lignes = $budget->lignes()->orderBy('article')->orderBy('paragraphe')->orderBy('rubrique')->get();
        return view('admin.ligne.index', compact('budget', 'lignes'));
    }

    public function create(Budget $budget)
    {
        return view('admin.ligne.create', compact('budget'));
    }

    public function store(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'article'    => 'required|integer',
            'paragraphe' => 'required|integer',
            'rubrique'   => 'required|integer',
            'code_ligne' => 'required|string|max:20',
            'nom'        => 'required|string|max:255',
        ]);

        $validated['budget_id'] = $budget->id;

        LigneBudgetaire::create($validated);

        return redirect()->route('admin.budgets.lignes.index', $budget)
            ->with('success', 'Ligne budgétaire créée avec succès.');
    }

    public function edit(Budget $budget, LigneBudgetaire $ligne)
    {
        return view('admin.ligne.edit', compact('budget', 'ligne'));
    }

    public function update(Request $request, Budget $budget, LigneBudgetaire $ligne)
    {
        $validated = $request->validate([
            'article'    => 'required|integer',
            'paragraphe' => 'required|integer',
            'rubrique'   => 'required|integer',
            'code_ligne' => 'required|string|max:20',
            'nom'        => 'required|string|max:255',
        ]);

        $ligne->update($validated);

        return redirect()->route('admin.budgets.lignes.index', $budget)
            ->with('success', 'Ligne budgétaire mise à jour.');
    }

    public function destroy(Budget $budget, LigneBudgetaire $ligne)
    {
        $ligne->delete();

        return redirect()->route('admin.budgets.lignes.index', $budget)
            ->with('success', 'Ligne budgétaire supprimée.');
    }
}
