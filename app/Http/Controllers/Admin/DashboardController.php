<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\Engagement;
use App\Models\LigneBudgetProposee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'budgets_count' => Budget::count(),
            'emetteurs_count' => Emetteur::count(),
            'propositions_attente' => LigneBudgetProposee::where('statut', 'en_attente')->count(),
            'engagements_attente' => Engagement::where('statut', 'en_attente')->count(),
        ];

        // Données dynamiques pour le graphique (Engagements approuvés par émetteur)
        $chartData = Emetteur::with(['user'])
            ->withSum(['engagements' => fn($q) => $q->where('statut', 'approuve')], 'total_ttc')
            ->get()
            ->map(fn($e) => [
                'label' => $e->user->name,
                'value' => (float) ($e->engagements_sum_total_ttc ?? 0)
            ]);

        return view('admin.dashboard', compact('stats', 'chartData'));

    }
}
