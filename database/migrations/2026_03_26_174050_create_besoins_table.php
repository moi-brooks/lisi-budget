<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('besoins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engagement_id')->constrained()->onDelete('cascade');
            $table->string('intitule');
            $table->text('description')->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('montant', 12, 2)->default(0);
            $table->boolean('is_delivered')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('besoins');
    }
};
