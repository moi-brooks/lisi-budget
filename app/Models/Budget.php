<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Budget extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'annee',
        'saison',
        'total',
        'administrateur_id',
        'date_limite',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['annee', 'saison', 'total', 'administrateur_id', 'date_limite'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'total' => 'decimal:2',
        'annee' => 'integer',
        'date_limite' => 'date',
    ];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administrateur_id');
    }

    public function emetteurs(): HasMany
    {
        return $this->hasMany(Emetteur::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneBudgetaire::class);
    }

    /** Somme des dotations allouées aux émetteurs */
    public function getTotalAlloueAttribute(): float
    {
        return (float) $this->emetteurs()->sum('dotation');
    }

    /** Reliquat = total - somme dotations */
    public function getReliquatAttribute(): float
    {
        return (float) $this->total - $this->total_alloue;
    }

    /** Est-ce que le budget est clôturé par la date limite ? */
    public function getIsClosedAttribute(): bool
    {
        if (!$this->date_limite) {
            return false; // Pas de limite
        }
        return now()->startOfDay()->greaterThan($this->date_limite);
    }
}
