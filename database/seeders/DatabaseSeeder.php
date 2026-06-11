<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\Engagement;
use App\Models\LigneBudgetaire;
use App\Models\LigneBudgetProposee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@uca.ac.ma'],
            [
                'name' => 'Dr. Khalid Mansouri',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Budget Actif
        $budget = Budget::firstOrCreate(
            ['annee' => date('Y')],
            [
                'saison' => date('Y') . '-' . (date('Y') + 1),
                'total' => 2500000.00,
                'administrateur_id' => $admin->id,
            ]
        );

        // 3. Lignes Budgétaires de base
        $this->call(LigneBudgetaireSeeder::class);
        $lignes = LigneBudgetaire::all();

        // 4. Emetteurs (Research Professors)
        $emetteurData = [
            ['name' => 'Pr. Ahmed Alami', 'email' => 'alami@uca.ac.ma', 'dotation' => 150000],
            ['name' => 'Pr. Sara Benali', 'email' => 'benali@uca.ac.ma', 'dotation' => 200000],
            ['name' => 'Dr. Yassine Zahiri', 'email' => 'zahiri@uca.ac.ma', 'dotation' => 120000],
        ];

        foreach ($emetteurData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'emetteur',
                ]
            );

            $emetteur = Emetteur::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'budget_id' => $budget->id,
                    'dotation' => $data['dotation'],
                    'profession' => 'Enseignant Chercheur',
                ]
            );

            // 5. Propositions pour chaque émetteur
            foreach ($lignes->random(4) as $ligne) {
                LigneBudgetProposee::create([
                    'emetteur_id' => $emetteur->id,
                    'ligne_budgetaire_id' => $ligne->id,
                    'montant' => rand(5000, 20000),
                    'statut' => rand(0, 1) ? 'approuve' : 'en_attente',
                ]);
            }

            // 6. Engagements (BCs) pour peupler les graphes
            for ($i = 0; $i < 3; $i++) {
                $prop = $emetteur->lignesProposees()->where('statut', 'approuve')->first();
                if ($prop) {
                    Engagement::create([
                        'emetteur_id' => $emetteur->id,
                        'ligne_proposee_id' => $prop->id,
                        'commentaire' => 'Commande LISI-' . rand(100, 999),
                        'fournisseur_id' => null,
                        'date' => now()->subDays(rand(1, 30)),
                        'total_ht' => rand(2000, 10000),
                        'tva' => 20,
                        'total_ttc' => rand(2400, 12000),
                        'statut' => 'approuve',
                    ]);
                }
            }
        }
    }
}
