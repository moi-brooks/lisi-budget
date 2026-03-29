<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('annee');
            $table->string('saison', 20);           // e.g. "2024-2025"
            $table->decimal('total', 12, 2);         // total alloué au labo (DH)
            $table->foreignId('administrateur_id')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
