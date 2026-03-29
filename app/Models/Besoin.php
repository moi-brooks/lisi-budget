<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Besoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'engagement_id',
        'intitule',
        'description',
        'quantite',
        'prix_unitaire',
        'montant',
        'is_delivered',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'montant'       => 'decimal:2',
        'is_delivered'  => 'boolean',
    ];

    public function engagement(): BelongsTo
    {
        return $this->belongsTo(Engagement::class);
    }
}
