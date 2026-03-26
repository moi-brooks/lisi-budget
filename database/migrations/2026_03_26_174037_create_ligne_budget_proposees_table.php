<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ligne_budget_proposees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_budgetaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('emetteur_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_propose', 15, 2);
            $table->text('justification')->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_budget_proposees');
    }
};
