<?php

namespace App\Http\Controllers\Emetteur;

use App\Http\Controllers\Controller;
use App\Models\Emetteur;
use App\Models\LigneBudgetProposee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $montant_attente = $emetteur->lignesProposees()->where('statut', 'en_attente')->sum('montant');
        $reliquat = $emetteur->reliquat; // dotation - engagements APPROUVES uniquement (voir Emetteur::getReliquatAttribute)

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

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password'              => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        auth()->user()->update([
            'password'             => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        return back()->with('password_changed', true);
    }
}
