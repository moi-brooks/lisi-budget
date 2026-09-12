<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withCount('engagements')->orderBy('nom')->get();

        return view('admin.fournisseur.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('admin.fournisseur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        Fournisseur::create($validated);

        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur créé.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.fournisseur.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $fournisseur->update($validated);

        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->engagements()->exists()) {
            return redirect()->route('admin.fournisseurs.index')
                ->with('error', "Impossible de supprimer « {$fournisseur->nom} » : des expressions de besoins y sont rattachées.");
        }

        $fournisseur->delete();

        return redirect()->route('admin.fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
