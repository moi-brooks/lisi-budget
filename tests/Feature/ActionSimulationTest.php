<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Emetteur;
use App\Models\Budget;
use App\Models\LigneBudgetaire;
use App\Models\LigneBudgetProposee;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PropositionStatusNotification;

class ActionSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_proposition_and_notification_is_sent()
    {
        Notification::fake();

        // Admin User
        $admin = User::factory()->create(['role' => 'admin']);

        // Budget & Ligne
        $budget = Budget::create([
            'administrateur_id' => $admin->id,
            'annee' => 2026,
            'saison' => '2025-2026',
            'total' => 100000,
            'statut' => 'actif',
        ]);

        $ligne = LigneBudgetaire::create([
            'budget_id' => $budget->id,
            'article' => 1234,
            'paragraphe' => 56,
            'rubrique' => 789,
            'code_ligne' => 'ART-1',
            'nom' => 'Matériel Informatique',
        ]);

        // Emetteur User
        $emetteurUser = User::factory()->create(['role' => 'emetteur']);
        $emetteur = Emetteur::create([
            'user_id' => $emetteurUser->id,
            'budget_id' => $budget->id,
            'departement' => 'Informatique',
            'dotation' => 10000,
        ]);

        // Proposition
        $proposition = LigneBudgetProposee::create([
            'ligne_budgetaire_id' => $ligne->id,
            'emetteur_id' => $emetteur->id,
            'montant' => 5000,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.propositions.approve', $proposition->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Proposition approuvée avec succès.');

        $this->assertDatabaseHas('ligne_budget_proposees', [
            'id' => $proposition->id,
            'statut' => 'approuve',
        ]);

        Notification::assertSentTo(
            [$emetteurUser], PropositionStatusNotification::class
        );
    }

    public function test_admin_can_reject_proposition_and_notification_is_sent()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $emetteurUser = User::factory()->create(['role' => 'emetteur']);
        
        $budget = Budget::create([
            'administrateur_id' => $admin->id,
            'annee' => 2026,
            'saison' => '2025-2026',
            'total' => 100000,
            'statut' => 'actif',
        ]);

        $ligne = LigneBudgetaire::create([
            'budget_id' => $budget->id,
            'article' => 1234,
            'paragraphe' => 56,
            'rubrique' => 789,
            'code_ligne' => 'ART-1',
            'nom' => 'Matériel Informatique',
        ]);

        $emetteur = Emetteur::create([
            'user_id' => $emetteurUser->id,
            'budget_id' => $budget->id,
            'departement' => 'Informatique',
            'dotation' => 10000,
        ]);

        $proposition = LigneBudgetProposee::create([
            'ligne_budgetaire_id' => $ligne->id,
            'emetteur_id' => $emetteur->id,
            'montant' => 5000,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.propositions.reject', $proposition->id), [
            'motif_refus' => 'Motif de test de rejet très long',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Proposition rejetée.');

        $this->assertDatabaseHas('ligne_budget_proposees', [
            'id' => $proposition->id,
            'statut' => 'rejete',
            'motif_refus' => 'Motif de test de rejet très long',
        ]);

        Notification::assertSentTo(
            [$emetteurUser], PropositionStatusNotification::class
        );
    }
}
