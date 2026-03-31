<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\LigneBudgetaire;
use App\Models\LigneBudgetProposee;
use App\Models\Engagement;
use App\Models\Besoin;
use App\Models\Fournisseur;

class EngagementCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_engagement_calculates_total_ht_and_ttc()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $emetteurUser = User::factory()->create(['role' => 'emetteur']);

        $budget = Budget::create([
            'annee' => 2026,
            'total' => 100000,
            'saison' => 'Hiver',
            'administrateur_id' => $admin->id
        ]);

        $emetteur = Emetteur::create([
            'user_id' => $emetteurUser->id,
            'budget_id' => $budget->id,
            'dotation' => 50000
        ]);

        $fournisseur = Fournisseur::create(['nom' => 'Test Fournisseur', 'adresse' => 'Adresse Test']);

        $ligne = LigneBudgetaire::create([
            'budget_id' => $budget->id,
            'article' => 'A1',
            'paragraphe' => 'P1',
            'rubrique' => 'R1',
            'code_ligne' => 'A1-P1-R1',
            'nom' => 'Test'
        ]);

        $proposition = LigneBudgetProposee::create([
            'ligne_budgetaire_id' => $ligne->id,
            'emetteur_id' => $emetteur->id,
            'montant' => 15000,
            'statut' => 'approuve'
        ]);

        $eng = Engagement::create([
            'emetteur_id' => $emetteur->id,
            'fournisseur_id' => $fournisseur->id,
            'ligne_proposee_id' => $proposition->id,
            'date' => now(),
            'tva' => 0,
            'statut' => 'en_attente'
        ]);

        Besoin::create([
            'engagement_id' => $eng->id,
            'intitule' => 'PC Portable',
            'quantite' => 2,
            'prix_unitaire' => 5000,
            'montant' => 10000
        ]);

        // Observer should have calculated totals
        $eng->refresh();
        $this->assertEquals(10000, $eng->total_ht);
        $this->assertEquals(10000, $eng->total_ttc);

        // Modify TVA as Admin
        $this->actingAs($admin)
            ->post("/admin/engagements/{$eng->id}/tva", [
                'tva' => 20
            ]);

        $eng->refresh();
        $this->assertEquals(20, $eng->tva);
        $this->assertEquals(10000, $eng->total_ht);
        $this->assertEquals(12000, $eng->total_ttc);
    }
}
