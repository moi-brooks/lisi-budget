<?php

namespace App\Http\Controllers;

use App\Models\Emetteur;
use App\Models\Engagement;
use App\Models\LigneBudgetaire;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isEmetteur() && $user->emetteur) {
            $engagements = $user->emetteur->engagements()->with('ligne.budget')->latest()->paginate(10);
        } else {
            $engagements = Engagement::with(['ligne.budget', 'emetteur'])->latest()->paginate(10);
        }

        return view('engagements.index', compact('engagements'));
    }

    public function create()
    {
        $lignes    = LigneBudgetaire::with('budget')->where('statut', 'ouvert')->get();
        $emetteurs = Emetteur::all();
        return view('engagements.create', compact('lignes', 'emetteurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ligne_budgetaire_id' => 'required|exists:ligne_budgetaires,id',
            'emetteur_id'         => 'required|exists:emetteurs,id',
            'montant'             => 'required|numeric|min:0.01',
            'description'         => 'required|string|max:255',
            'date_engagement'     => 'required|date',
        ]);
        $validated['statut'] = 'en_cours';

        $engagement = Engagement::create($validated);

        return redirect()->route('engagements.index')
            ->with('success', 'Engagement enregistré avec succès.');
    }

    public function show(Engagement $engagement)
    {
        $engagement->load(['ligne.budget', 'emetteur']);
        return view('engagements.show', compact('engagement'));
    }

    public function update(Request $request, Engagement $engagement)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_cours,solde,annule',
        ]);

        $engagement->update($validated);

        return redirect()->route('engagements.index')
            ->with('success', 'Statut de l\'engagement mis à jour.');
    }
}
