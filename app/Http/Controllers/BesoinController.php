<?php

namespace App\Http\Controllers;

use App\Models\Besoin;
use App\Models\Emetteur;
use Illuminate\Http\Request;

class BesoinController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isEmetteur() && $user->emetteur) {
            $besoins = $user->emetteur->besoins()->latest()->paginate(10);
        } else {
            $besoins = Besoin::with('emetteur')->latest()->paginate(10);
        }

        return view('besoins.index', compact('besoins'));
    }

    public function create()
    {
        $emetteurs = Emetteur::all();
        return view('besoins.create', compact('emetteurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'emetteur_id' => 'required|exists:emetteurs,id',
            'libelle'     => 'required|string|max:255',
            'montant'     => 'required|numeric|min:0',
            'priorite'    => 'required|in:faible,moyenne,haute',
        ]);
        $validated['statut'] = 'en_attente';

        Besoin::create($validated);

        return redirect()->route('besoins.index')
            ->with('success', 'Besoin soumis avec succès.');
    }

    public function edit(Besoin $besoin)
    {
        $emetteurs = Emetteur::all();
        return view('besoins.edit', compact('besoin', 'emetteurs'));
    }

    public function update(Request $request, Besoin $besoin)
    {
        $validated = $request->validate([
            'statut'  => 'required|in:en_attente,approuve,rejete',
            'priorite'=> 'required|in:faible,moyenne,haute',
            'montant' => 'required|numeric|min:0',
        ]);

        $besoin->update($validated);

        return redirect()->route('besoins.index')
            ->with('success', 'Besoin mis à jour.');
    }

    public function destroy(Besoin $besoin)
    {
        $besoin->delete();
        return redirect()->route('besoins.index')
            ->with('success', 'Besoin supprimé.');
    }
}
