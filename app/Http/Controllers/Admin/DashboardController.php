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

        return view('admin.dashboard', compact('stats'));
    }
}
