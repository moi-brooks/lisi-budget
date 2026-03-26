<?php

namespace App\Http\Controllers;

use App\Models\Besoin;
use App\Models\Budget;
use App\Models\Engagement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'budgets_actifs'      => Budget::where('statut', 'actif')->count(),
            'besoins_en_attente'  => Besoin::where('statut', 'en_attente')->count(),
            'engagements_cours'   => Engagement::where('statut', 'en_cours')->count(),
            'total_engage'        => Engagement::where('statut', '!=', 'annule')->sum('montant'),
        ];

        $recentBudgets    = Budget::latest()->take(5)->get();
        $recentBesoins    = Besoin::with('emetteur')->latest()->take(5)->get();
        $recentEngagements = Engagement::with(['emetteur', 'ligne'])->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentBudgets', 'recentBesoins', 'recentEngagements'));
    }
}
