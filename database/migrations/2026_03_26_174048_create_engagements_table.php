<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_proposee_id')->constrained('ligne_budget_proposees')->onDelete('cascade');
            $table->foreignId('emetteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('fournisseur_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->text('commentaire')->nullable();
            $table->decimal('tva', 8, 2)->default(0);
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engagements');
    }
};
