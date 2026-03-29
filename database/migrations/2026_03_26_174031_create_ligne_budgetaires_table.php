<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligne_budgetaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('article');            // Niveau 1 (ex: 2)
            $table->smallInteger('paragraphe');        // Niveau 2 (ex: 220)
            $table->mediumInteger('rubrique');         // Niveau 3 (ex: 22030)
            $table->string('code_ligne', 10);          // Code complet niveau 4 (ex: 22033)
            $table->string('nom');                     // Libellé complet
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ligne_budgetaires');
    }
};
