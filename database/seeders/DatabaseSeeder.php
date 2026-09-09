<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\Engagement;
use App\Models\Fournisseur;
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

        // 4. Fournisseurs de démo (créés une seule fois)
        $fournisseurs = collect([
            ['nom' => 'Librairie Papeterie Al Massira', 'adresse' => '12 Av. Mohammed V, Marrakech'],
            ['nom' => 'TechnoStore Maroc SARL', 'adresse' => 'Zone Industrielle Sidi Ghanem, Marrakech'],
            ['nom' => 'Comptoir Scientifique du Sud', 'adresse' => '45 Rue Ibn Sina, Casablanca'],
        ])->map(fn ($data) => Fournisseur::firstOrCreate(['nom' => $data['nom']], $data));

        // Catalogue d'articles pour les expressions de besoins de démo
        $catalogue = [
            ['intitule' => 'Ordinateur portable', 'pu' => [6000, 12000]],
            ['intitule' => "Cartouches d'encre", 'pu' => [300, 800]],
            ['intitule' => 'Ramette papier A4', 'pu' => [40, 60]],
            ['intitule' => 'Disque dur externe 2 To', 'pu' => [700, 1200]],
            ['intitule' => 'Vidéoprojecteur', 'pu' => [3000, 6000]],
            ['intitule' => 'Consommables de laboratoire', 'pu' => [500, 2500]],
            ['intitule' => 'Frais de mission colloque', 'pu' => [2000, 5000]],
        ];

        // 5. Emetteurs (Research Professors)
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

            // 6. Propositions pour chaque émetteur
            foreach ($lignes->random(4) as $ligne) {
                LigneBudgetProposee::create([
                    'emetteur_id' => $emetteur->id,
                    'ligne_budgetaire_id' => $ligne->id,
                    'montant' => rand(5000, 20000),
                    'statut' => rand(0, 1) ? 'approuve' : 'en_attente',
                ]);
            }

            // 7. Engagements (BCs) + expressions de besoins pour peupler les graphes
            $propsApprouvees = $emetteur->lignesProposees()->where('statut', 'approuve')->get();

            for ($i = 0; $i < 3; $i++) {
                $prop = $propsApprouvees->get($i) ?? $propsApprouvees->first();
                if (! $prop) {
                    continue;
                }

                $engagement = Engagement::create([
                    'emetteur_id' => $emetteur->id,
                    'ligne_proposee_id' => $prop->id,
                    'commentaire' => 'Commande LISI-' . rand(100, 999),
                    'fournisseur_id' => $fournisseurs->random()->id,
                    'date' => now()->subDays(rand(1, 30)),
                    'tva' => 20,
                    'total_ht' => 0,
                    'total_ttc' => 0,
                    'statut' => 'approuve',
                ]);

                // 2 à 3 articles par engagement
                foreach (collect($catalogue)->random(rand(2, 3)) as $article) {
                    $quantite = rand(1, 5);
                    $prixUnitaire = rand($article['pu'][0], $article['pu'][1]);

                    $engagement->besoins()->create([
                        'intitule' => $article['intitule'],
                        'description' => 'Article de démonstration',
                        'quantite' => $quantite,
                        'prix_unitaire' => $prixUnitaire,
                        'montant' => $prixUnitaire * $quantite,
                        'is_delivered' => (bool) rand(0, 1),
                    ]);
                }

                // Recalcule total_ht / total_ttc à partir des besoins
                $engagement->calculerTotal();
                $engagement->save();
            }
        }
    }
}
