<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::orderByDesc('annee')->get();
        return view('admin.budget.index', compact('budgets'));
    }

    public function create()
    {
        return view('admin.budget.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'annee' => 'required|integer|min:2020|max:2099',
            'saison' => 'required|string|max:20',
            'total' => 'required|numeric|min:0',
        ]);

        $validated['administrateur_id'] = auth()->id();
        Budget::create($validated);

        return redirect()->route('admin.budgets.index')->with('success', 'Budget créé.');
    }

    public function show(Budget $budget)
    {
        return view('admin.budget.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        return view('admin.budget.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'annee' => 'required|integer|min:2020|max:2099',
            'saison' => 'required|string|max:20',
            'total' => 'required|numeric|min:0',
        ]);

        $budget->update($validated);
        return redirect()->route('admin.budgets.index')->with('success', 'Budget mis à jour.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('admin.budgets.index')->with('success', 'Budget supprimé.');
    }
}
