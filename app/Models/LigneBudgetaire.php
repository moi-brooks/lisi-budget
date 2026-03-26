<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LigneBudgetaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'code',
        'libelle',
        'montant_alloue',
        'statut',
    ];

    protected $casts = [
        'montant_alloue' => 'decimal:2',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function propositions(): HasMany
    {
        return $this->hasMany(LigneBudgetProposee::class);
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function getMontantEngageAttribute(): float
    {
        return (float) $this->engagements()->where('statut', '!=', 'annule')->sum('montant');
    }

    public function getMontantDisponibleAttribute(): float
    {
        return (float) $this->montant_alloue - $this->montant_engage;
    }
}
