<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LigneBudgetaire extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'budget_id',
        'article',
        'paragraphe',
        'rubrique',
        'code_ligne',
        'nom',
        'solde',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nom', 'budget_alloue', 'solde'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'article'    => 'integer',
        'paragraphe' => 'integer',
        'rubrique'   => 'integer',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function propositions(): HasMany
    {
        return $this->hasMany(LigneBudgetProposee::class);
    }

    /** Code hiérarchique lisible : article.paragraphe.rubrique.code_ligne */
    public function getCodeCompletAttribute(): string
    {
        return "{$this->article}.{$this->paragraphe}.{$this->rubrique}.{$this->code_ligne}";
    }
}
