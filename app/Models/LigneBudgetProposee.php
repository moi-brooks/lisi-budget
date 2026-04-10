<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
