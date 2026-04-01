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
        $budget = Budget::first() ?: Budget::create([
            'annee' => date('Y'),
            'saison' => date('Y') . '-' . (date('Y') + 1),
            'total' => 2500000.00,
            'administrateur_id' => 1,
        ]);

        $lignes = [
            ['rubrique' => 38, 'nom' => "Frais de participation et d'inscription aux colloques", 'code_ligne' => '38-01'],
            ['rubrique' => 32, 'nom' => "Achats de petit outillage et petit équipement", 'code_ligne' => '32-01'],
            ['rubrique' => 12, 'nom' => "Achat de fournitures informatiques", 'code_ligne' => '12-01'],
            ['rubrique' => 11, 'nom' => "Achat de fournitures de bureau, papeterie et imprimés", 'code_ligne' => '11-01'],
            ['rubrique' => 32, 'nom' => "Achat de matières premières", 'code_ligne' => '32-02'],
            ['rubrique' => 23, 'nom' => "Frais de transport du personnel et des étudiants à l'étranger", 'code_ligne' => '23-01'],
            ['rubrique' => 21, 'nom' => "Indemnités de déplacement à l'intérieur du Royaume", 'code_ligne' => '21-01'],
            ['rubrique' => 22, 'nom' => "Indemnités kilométriques", 'code_ligne' => '22-01'],
            ['rubrique' => 25, 'nom' => "Indemnités de mission à l'étranger", 'code_ligne' => '25-01'],
            ['rubrique' => 20, 'nom' => "Achat de carburants", 'code_ligne' => '20-01'],
            ['rubrique' => 13, 'nom' => "Achat de matériel scientifique", 'code_ligne' => '13-01'],
            ['rubrique' => 14, 'nom' => "Achat de matériel informatique", 'code_ligne' => '14-01'],
        ];

        foreach ($lignes as $data) {
            LigneBudgetaire::updateOrCreate(
                ['budget_id' => $budget->id, 'nom' => $data['nom']],
                [
                    'article' => 2, // Standard article
                    'paragraphe' => 200, // Standard paragraphe
                    'rubrique' => $data['rubrique'],
                    'code_ligne' => $data['code_ligne'],
                ]
            );
        }
    }
}
