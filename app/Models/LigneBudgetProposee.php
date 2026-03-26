<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneBudgetProposee extends Model
{
    use HasFactory;

    protected $fillable = [
        'ligne_budgetaire_id',
        'emetteur_id',
        'montant_propose',
        'justification',
        'statut',
    ];

    protected $casts = [
        'montant_propose' => 'decimal:2',
    ];

    public function ligne(): BelongsTo
    {
        return $this->belongsTo(LigneBudgetaire::class, 'ligne_budgetaire_id');
    }

    public function emetteur(): BelongsTo
    {
        return $this->belongsTo(Emetteur::class);
    }
}
