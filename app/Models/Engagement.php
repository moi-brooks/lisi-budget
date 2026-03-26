<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Engagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ligne_budgetaire_id',
        'emetteur_id',
        'montant',
        'description',
        'date_engagement',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_engagement' => 'date',
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
