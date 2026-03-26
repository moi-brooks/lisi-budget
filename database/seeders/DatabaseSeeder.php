<?php

namespace Database\Seeders;

use App\Models\Besoin;
use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\Engagement;
use App\Models\LigneBudgetaire;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Users ---
        $admin = User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@lisi.dz',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $gestionnaire = User::create([
            'name'     => 'Gestionnaire Budget',
            'email'    => 'gestionnaire@lisi.dz',
            'password' => Hash::make('password'),
            'role'     => 'gestionnaire',
        ]);

        $userEmetteur1 = User::create([
            'name'     => 'Responsable Informatique',
            'email'    => 'info@lisi.dz',
            'password' => Hash::make('password'),
            'role'     => 'emetteur',
        ]);

        $userEmetteur2 = User::create([
            'name'     => 'Responsable RH',
            'email'    => 'rh@lisi.dz',
            'password' => Hash::make('password'),
            'role'     => 'emetteur',
        ]);

        // --- Emetteurs ---
        $emetteur1 = Emetteur::create([
            'nom'     => 'Département Informatique',
            'code'    => 'INFO',
            'user_id' => $userEmetteur1->id,
        ]);

        $emetteur2 = Emetteur::create([
            'nom'     => 'Département Ressources Humaines',
            'code'    => 'RH',
            'user_id' => $userEmetteur2->id,
        ]);

        // --- Budget ---
        $budget = Budget::create([
            'annee'              => 2026,
            'titre'              => 'Budget Annuel LISI 2026',
            'description'        => 'Budget prévisionnel pour l\'exercice fiscal 2026.',
            'statut'             => 'actif',
            'total_previsionnel' => 5000000.00,
        ]);

        // --- Lignes Budgétaires ---
        $ligneInfo = LigneBudgetaire::create([
            'budget_id'      => $budget->id,
            'code'           => 'LB-INFO-01',
            'libelle'        => 'Équipements et matériel informatique',
            'montant_alloue' => 1500000.00,
            'statut'         => 'ouvert',
        ]);

        $ligneRh = LigneBudgetaire::create([
            'budget_id'      => $budget->id,
            'code'           => 'LB-RH-01',
            'libelle'        => 'Formation et développement RH',
            'montant_alloue' => 800000.00,
            'statut'         => 'ouvert',
        ]);

        $ligneGen = LigneBudgetaire::create([
            'budget_id'      => $budget->id,
            'code'           => 'LB-GEN-01',
            'libelle'        => 'Frais généraux et fonctionnement',
            'montant_alloue' => 2700000.00,
            'statut'         => 'ouvert',
        ]);

        // --- Besoins ---
        Besoin::create([
            'emetteur_id' => $emetteur1->id,
            'libelle'     => 'Renouvellement serveurs de production',
            'montant'     => 450000.00,
            'priorite'    => 'haute',
            'statut'      => 'en_attente',
        ]);

        Besoin::create([
            'emetteur_id' => $emetteur1->id,
            'libelle'     => 'Licences logiciels (suite bureautique)',
            'montant'     => 120000.00,
            'priorite'    => 'moyenne',
            'statut'      => 'approuve',
        ]);

        Besoin::create([
            'emetteur_id' => $emetteur2->id,
            'libelle'     => 'Programme de formation leadership',
            'montant'     => 200000.00,
            'priorite'    => 'moyenne',
            'statut'      => 'en_attente',
        ]);

        // --- Engagements ---
        Engagement::create([
            'ligne_budgetaire_id' => $ligneInfo->id,
            'emetteur_id'         => $emetteur1->id,
            'montant'             => 120000.00,
            'description'         => 'Achat licences Microsoft 365 (50 utilisateurs)',
            'date_engagement'     => '2026-02-15',
            'statut'              => 'solde',
        ]);

        Engagement::create([
            'ligne_budgetaire_id' => $ligneRh->id,
            'emetteur_id'         => $emetteur2->id,
            'montant'             => 80000.00,
            'description'         => 'Séminaire de formation management Q1',
            'date_engagement'     => '2026-03-10',
            'statut'              => 'en_cours',
        ]);
    }
}
