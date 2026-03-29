<?php

namespace App\Http\Controllers\Emetteur;

use App\Http\Controllers\Controller;
use App\Models\Emetteur;
use App\Models\LigneBudgetProposee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $emetteur = auth()->user()->emetteur;
        
        if (!$emetteur) {
            // Emetteur profile not assigned yet
            return view('emetteur.dashboard-empty');
        }

        $dotation = $emetteur->dotation;
        $montant_approuve = $emetteur->montant_approuve;
        $montant_attente = $emetteur->lignesProposees()->where('statut', 'en_attente')->sum('montant_propose');
        $reliquat = $emetteur->reliquat; // Handles attente + approuve

        $stats = [
            'dotation' => $dotation,
            'montant_approuve' => $montant_approuve,
            'montant_attente' => $montant_attente,
            'reliquat' => $reliquat,
            'propositions_count' => $emetteur->lignesProposees()->count(),
            'engagements_count' => $emetteur->engagements()->count(),
        ];

        return view('emetteur.dashboard', compact('stats', 'emetteur'));
    }
}
