<?php

namespace App\Http\Controllers;

use App\Models\Emetteur;
use App\Models\User;
use Illuminate\Http\Request;

class EmetteurController extends Controller
{
    public function index()
    {
        $emetteurs = Emetteur::with('user')->paginate(10);
        return view('emetteurs.index', compact('emetteurs'));
    }

    public function create()
    {
        $users = User::where('role', 'emetteur')->get();
        return view('emetteurs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'code'    => 'required|string|max:20|unique:emetteurs,code',
            'user_id' => 'required|exists:users,id',
        ]);

        $emetteur = Emetteur::create($validated);

        return redirect()->route('emetteurs.show', $emetteur)
            ->with('success', 'Émetteur créé avec succès.');
    }

    public function show(Emetteur $emetteur)
    {
        $emetteur->load(['user', 'besoins', 'engagements.ligne']);
        return view('emetteurs.show', compact('emetteur'));
    }

    public function edit(Emetteur $emetteur)
    {
        $users = User::where('role', 'emetteur')->get();
        return view('emetteurs.edit', compact('emetteur', 'users'));
    }

    public function update(Request $request, Emetteur $emetteur)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'code'    => 'required|string|max:20|unique:emetteurs,code,' . $emetteur->id,
            'user_id' => 'required|exists:users,id',
        ]);

        $emetteur->update($validated);

        return redirect()->route('emetteurs.show', $emetteur)
            ->with('success', 'Émetteur mis à jour.');
    }
}
