<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligne_budget_proposees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_budgetaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('emetteur_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 12, 2);
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->integer('nb_refus')->default(0);
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ligne_budget_proposees');
    }
};
