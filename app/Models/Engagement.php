<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Engagement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'ligne_proposee_id',
        'emetteur_id',
        'fournisseur_id',
        'fournisseur_nom',
        'date',
        'commentaire',
        'tva',
        'total_ht',
        'total_ttc',
        'statut',
        'motif_refus',
    ];

    protected $casts = [
        'date' => 'date',
        'tva' => 'decimal:2',
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['statut', 'total_ht', 'total_ttc', 'motif_refus'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function emetteur(): BelongsTo
    {
        return $this->belongsTo(Emetteur::class);
    }

    public function ligneProposee(): BelongsTo
    {
        return $this->belongsTo(LigneBudgetProposee::class, 'ligne_proposee_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function besoins(): HasMany
    {
        return $this->hasMany(Besoin::class);
    }

    /** Total de l'expression de besoins (stocké en base) */
    public function getMontantTotalAttribute(): float
    {
        return (float) $this->total_ttc;
    }

    public function calculerTotal(): void
    {
        $ht = (float) $this->besoins()->sum('montant');
        $this->total_ht = $ht;
        $this->total_ttc = $ht + ($ht * ($this->tva / 100));
    }

    /** Check if fully delivered */
    public function getEstLivreAttribute(): bool
    {
        if ($this->besoins()->count() === 0) return false;
        // Fix string given closure lint dynamically via exact operator
        return $this->besoins()->where('is_delivered', '=', false)->count() === 0;
    }
}
