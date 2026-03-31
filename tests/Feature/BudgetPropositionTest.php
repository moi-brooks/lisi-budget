<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Budget;
use App\Models\Emetteur;
use App\Models\LigneBudgetaire;
use App\Models\LigneBudgetProposee;

class BudgetPropositionTest extends TestCase
{
    use RefreshDatabase;

    protected $emetteurUser;
    protected $adminUser;
    protected $ligne;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->emetteurUser = User::factory()->create(['role' => 'emetteur']);

        $budget = Budget::create([
            'annee' => 2026,
            'saison' => 'Hiver',
            'total' => 100000,
            'administrateur_id' => $this->adminUser->id
        ]);

        Emetteur::create([
            'user_id' => $this->emetteurUser->id,
            'budget_id' => $budget->id,
            'dotation' => 50000
        ]);

        $this->ligne = LigneBudgetaire::create([
            'budget_id' => $budget->id,
            'article' => 'A1',
            'paragraphe' => 'P1',
            'rubrique' => 'R1',
            'code_ligne' => 'A1-P1-R1',
            'nom' => 'Matériel Informatique'
        ]);
    }

    public function test_emetteur_cannot_propose_more_than_dotation()
    {
        $response = $this->actingAs($this->emetteurUser)
            ->post('/emetteur/lignes', [
                'ligne_budgetaire_id' => $this->ligne->id,
                'montant' => 60000 // Dotation is 50000
            ]);

        $response->assertSessionHasErrors(['montant']);
        $this->assertEquals(0, LigneBudgetProposee::count());
    }

    public function test_emetteur_cannot_duplicate_en_attente_proposition()
    {
        $this->actingAs($this->emetteurUser)
            ->post('/emetteur/lignes', [
                'ligne_budgetaire_id' => $this->ligne->id,
                'montant' => 10000
            ]);

        // Attempt duplicate
        $response = $this->actingAs($this->emetteurUser)
            ->post('/emetteur/lignes', [
                'ligne_budgetaire_id' => $this->ligne->id,
                'montant' => 5000
            ]);

        $response->assertSessionHasErrors(['ligne_budgetaire_id']);
        $this->assertEquals(1, LigneBudgetProposee::count());
    }

    public function test_rejecting_proposition_increments_nb_refus()
    {
        $prop = LigneBudgetProposee::create([
            'emetteur_id' => $this->emetteurUser->emetteur->id,
            'ligne_budgetaire_id' => $this->ligne->id,
            'montant' => 10000,
            'statut' => 'en_attente'
        ]);

        $this->actingAs($this->adminUser)
            ->post("/admin/propositions/{$prop->id}/reject", [
                'motif_refus' => 'Ceci est un motif de refus valide avec plus de 10 caracteres'
            ]);

        $prop->refresh();
        $this->assertEquals('rejete', $prop->statut);
        $this->assertEquals(1, $prop->nb_refus);
    }
}
