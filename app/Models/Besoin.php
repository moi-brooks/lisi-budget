<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Besoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'emetteur_id',
        'libelle',
        'montant',
        'priorite',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function emetteur(): BelongsTo
    {
        return $this->belongsTo(Emetteur::class);
    }
}
