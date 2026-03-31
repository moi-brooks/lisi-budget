<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Emetteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'budget_id',
        'dotation',
        'profession',
    ];

    protected $casts = [
        'dotation' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function lignesProposees(): HasMany
    {
        return $this->hasMany(LigneBudgetProposee::class);
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    /** Montant total proposé (toutes propositions) */
    public function getMontantProposeAttribute(): float
    {
        return (float) $this->lignesProposees()->sum('montant');
    }

    /** Montant approuvé */
    public function getMontantApprouveAttribute(): float
    {
        return (float) $this->lignesProposees()->where('statut', '=', 'approuve')->sum('montant');
    }

    /** Reliquat basé sur dotation - somme des engagements approuvés (TTC) */
    public function getReliquatAttribute(): float
    {
        $engage = $this->engagements()
            ->where('statut', '=', 'approuve')
            ->sum('total_ttc');
        return (float) $this->dotation - $engage;
    }

}
