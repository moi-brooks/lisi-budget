<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('engagements', function (Blueprint $table) {
            // Saisie libre du fournisseur par l'émetteur (remplace le catalogue).
            // fournisseur_id reste nullable et en base pour les données existantes.
            $table->string('fournisseur_nom')->nullable()->after('fournisseur_id');
        });
    }

    public function down(): void
    {
        Schema::table('engagements', function (Blueprint $table) {
            $table->dropColumn('fournisseur_nom');
        });
    }
};
