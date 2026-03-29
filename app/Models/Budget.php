<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'annee',
        'saison',
        'total',
        'administrateur_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'annee' => 'integer',
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
}
