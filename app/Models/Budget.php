<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'annee',
        'titre',
        'description',
        'statut',
        'total_previsionnel',
    ];

    protected $casts = [
        'total_previsionnel' => 'decimal:2',
        'annee' => 'integer',
    ];

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneBudgetaire::class);
    }
}

