<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::latest()->paginate(10);
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        return view('budgets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee'              => 'required|integer|min:2000|max:2100',
            'titre'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'statut'             => 'required|in:brouillon,actif,clos',
            'total_previsionnel' => 'required|numeric|min:0',
        ]);

        $budget = Budget::create($validated);

        return redirect()->route('budgets.show', $budget)
            ->with('success', 'Budget créé avec succès.');
    }

    public function show(Budget $budget)
    {
        $budget->load('lignes');
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'annee'              => 'required|integer|min:2000|max:2100',
            'titre'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'statut'             => 'required|in:brouillon,actif,clos',
            'total_previsionnel' => 'required|numeric|min:0',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.show', $budget)
            ->with('success', 'Budget mis à jour.');
    }
}
