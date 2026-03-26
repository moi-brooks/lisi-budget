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
        'nom',
        'code',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function besoins(): HasMany
    {
        return $this->hasMany(Besoin::class);
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function lignesProposees(): HasMany
    {
        return $this->hasMany(LigneBudgetProposee::class);
    }
}

