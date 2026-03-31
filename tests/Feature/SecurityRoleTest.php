<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Budget;
use App\Models\Emetteur;

class SecurityRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/emetteur/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_emetteur_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['role' => 'emetteur']);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_emetteur_routes()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)->get('/emetteur/dashboard');
        $response->assertStatus(403);
    }

    public function test_redirects_are_correct_based_on_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $emetteur = User::factory()->create(['role' => 'emetteur']);

        $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin/dashboard');
        $this->actingAs($emetteur)->get('/dashboard')->assertRedirect('/emetteur/dashboard');
    }
}
