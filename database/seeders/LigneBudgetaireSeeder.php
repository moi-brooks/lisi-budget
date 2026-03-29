<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\LigneBudgetaire;
use Illuminate\Database\Seeder;

class LigneBudgetaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // On a besoin d'un budget par défaut pour y rattacher les lignes
        $budget = Budget::firstOrCreate(
            ['annee' => date('Y')],
            [
                'saison' => date('Y') . '-' . (date('Y') + 1),
                'total' => 1000000.00,
                'administrateur_id' => 1, // On suppose que l'admin (ID 1) existe déjà
            ]
        );

        $lignes = [
            // 2.220.22030 (Déplacements au Maroc)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 220,
                'rubrique' => 22030,
                'code_ligne' => '22031',
                'nom' => 'Frais de déplacement au Maroc',
            ],
            // 2.220.22030 (Déplacements ou missions à l'étranger)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 220,
                'rubrique' => 22030,
                'code_ligne' => '22033',
                'nom' => 'Frais de déplacement ou missions à l\'étranger',
            ],
            // 2.230.23020 (Expositions, visites et foires)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23020,
                'code_ligne' => '23021',
                'nom' => 'Organisation de fêtes, réceptions et foires',
            ],
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23020,
                'code_ligne' => '23022',
                'nom' => 'Sponsoring et autres dépenses publicitaires',
            ],
            // 2.230.23050 (Achat de matériel informatique)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23050,
                'code_ligne' => '23054',
                'nom' => 'Achat, acquisition et location du matériel technique et informatique',
            ],
            // 2.230.23060 (Entretien et réparation du matériel informatique)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23060,
                'code_ligne' => '23062',
                'nom' => 'Entretien et réparation du matériel informatique ou scientifique',
            ],
            // 2.230.23070 (Achat de consommables)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23070,
                'code_ligne' => '23073',
                'nom' => 'Achat et fournitures informatiques ou électroniques',
            ],
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 230,
                'rubrique' => 23070,
                'code_ligne' => '23074',
                'nom' => 'Toner, encre et petit matériel informatique',
            ],
            // 2.240.24010 (Dépôt et publication)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 240,
                'rubrique' => 24010,
                'code_ligne' => '24011',
                'nom' => 'Frais de publication',
            ],
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 240,
                'rubrique' => 24010,
                'code_ligne' => '24012',
                'nom' => 'Frais de reliures, d\'impression et de reprographie',
            ],
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 240,
                'rubrique' => 24010,
                'code_ligne' => '24013',
                'nom' => 'Frais d\'inscription aux congrès et colloques',
            ],
            // 2.240.24070 (Livres et abonnements)
            [
                'budget_id' => $budget->id,
                'article' => 2,
                'paragraphe' => 240,
                'rubrique' => 24070,
                'code_ligne' => '24074',
                'nom' => 'Achat d\'ouvrages, abonnements, bibliothèques',
            ],
        ];

        foreach ($lignes as $ligne) {
            LigneBudgetaire::firstOrCreate(
                ['budget_id' => $ligne['budget_id'], 'code_ligne' => $ligne['code_ligne']],
                $ligne
            );
        }
    }
}
