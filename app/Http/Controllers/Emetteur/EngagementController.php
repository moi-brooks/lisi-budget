<?php

namespace App\Http\Controllers\Emetteur;

use App\Http\Controllers\Controller;
use App\Models\Besoin;
use App\Models\Engagement;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $emetteur = auth()->user()->emetteur;

        if (!$emetteur) return redirect()->route('emetteur.dashboard');

        $engagements = $emetteur->engagements()
            ->with(['fournisseur', 'ligneProposee.ligne'])
            ->where('statut', $status)
            ->latest()
            ->get();

        return view('emetteur.engagement.index', compact('engagements', 'status'));
    }

    public function create()
    {
        $emetteur = auth()->user()->emetteur;
        
        // Can only create Engagement on APPROVED propositions
        $lignesApprouvees = $emetteur->lignesProposees()
            ->with('ligne')
            ->where('statut', 'approuve')
            ->get();
            
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('emetteur.engagement.create', compact('lignesApprouvees', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $emetteur = auth()->user()->emetteur;

        $validated = $request->validate([
            'ligne_proposee_id' => 'required|exists:ligne_budget_proposees,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
            'tva' => 'required|numeric|min:0|max:100',
            
            // First besoin line items
            'intitule.*' => 'required|string',
            'description.*' => 'nullable|string',
            'quantite.*' => 'required|integer|min:1',
            'prix_unitaire.*' => 'required|numeric|min:0.01',
        ]);

        // Security check
        $ligne = $emetteur->lignesProposees()->findOrFail($validated['ligne_proposee_id']);
        if ($ligne->statut !== 'approuve') {
            abort(403, 'Ligne non approuvée.');
        }

        $engagement = $emetteur->engagements()->create([
            'ligne_proposee_id' => $validated['ligne_proposee_id'],
            'fournisseur_id' => $validated['fournisseur_id'],
            'date' => $validated['date'],
            'commentaire' => $validated['commentaire'] ?? null,
            'tva' => $validated['tva'],
        ]);

        // Ensure array inputs exist safely
        if ($request->has('intitule')) {
            foreach ($request->intitule as $key => $intitule) {
                $qte = $request->quantite[$key];
                $prix = $request->prix_unitaire[$key];
                $engagement->besoins()->create([
                    'intitule' => $intitule,
                    'description' => $request->description[$key] ?? null,
                    'quantite' => $qte,
                    'prix_unitaire' => $prix,
                    'montant' => $qte * $prix,
                ]);
            }
        }
        
        $engagement->calculerTotal();
        $engagement->save();

        return redirect()->route('emetteur.engagements.show', $engagement)->with('success', 'Bon de commande créé.');
    }

    public function show(Engagement $engagement)
    {
        if ($engagement->emetteur_id !== auth()->user()->emetteur->id) {
            abort(403);
        }

        return view('emetteur.engagement.show', compact('engagement'));
    }

    public function storeBesoin(Request $request, $id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->emetteur_id !== auth()->user()->emetteur->id || $engagement->statut !== 'en_attente') {
            abort(403);
        }

        $validated = $request->validate([
            'intitule' => 'required|string',
            'description' => 'nullable|string',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0.01',
        ]);

        $qte = $validated['quantite'];
        $prix = $validated['prix_unitaire'];

        $engagement->besoins()->create([
            'intitule' => $validated['intitule'],
            'description' => $validated['description'] ?? null,
            'quantite' => $qte,
            'prix_unitaire' => $prix,
            'montant' => $qte * $prix,
        ]);

        $engagement->calculerTotal();
        $engagement->save();

        return back()->with('success', 'Article ajouté.');
    }

    public function markLivre(Request $request, $id)
    {
        $besoin = Besoin::findOrFail($id);
        
        if ($besoin->engagement->emetteur_id !== auth()->user()->emetteur->id) {
            abort(403);
        }

        $validated = $request->validate([
            'is_delivered' => 'required|boolean'
        ]);

        $besoin->update(['is_delivered' => $validated['is_delivered']]);

        return back()->with('success', 'Statut de livraison mis à jour.');
    }
}
