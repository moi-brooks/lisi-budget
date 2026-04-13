<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LigneBudgetProposee extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'ligne_budgetaire_id',
        'emetteur_id',
        'description',
        'montant',
        'statut',
        'motif_refus',
        'nb_refus',
        'validated_at',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'nb_refus' => 'integer',
        'validated_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['montant', 'statut', 'motif_refus'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function ligne(): BelongsTo
    {
        return $this->belongsTo(LigneBudgetaire::class, 'ligne_budgetaire_id');
    }

    public function emetteur(): BelongsTo
    {
        return $this->belongsTo(Emetteur::class);
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'ligne_proposee_id');
    }

    /** Montant déjà engagé (TTC) sur cette ligne (en attente ou approuvé) */
    public function getMontantConsommeAttribute(): float
    {
        return (float) $this->engagements()
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->sum('total_ttc');
    }

    /** Montant restant disponible sur cette proposition spécifique */
    public function getMontantDisponibleAttribute(): float
    {
        return (float) $this->montant - $this->montant_consomme;
    }
}
