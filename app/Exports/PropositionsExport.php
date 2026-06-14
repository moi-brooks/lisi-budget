<?php

namespace App\Exports;

use App\Models\LigneBudgetProposee;
use Rap2hpoutre\FastExcel\FastExcel;

class PropositionsExport
{
    protected ?string $annee;
    protected ?string $statut;

    public function __construct(?string $annee = null, ?string $statut = null)
    {
        $this->annee  = $annee;
        $this->statut = $statut;
    }

    public function collection()
    {
        $query = LigneBudgetProposee::with(['emetteur.user', 'ligne.budget'])
            ->latest();

        if ($this->annee) {
            $query->whereHas('ligne.budget', function ($q) {
                $q->where('annee', $this->annee);
            });
        }

        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        return $query->get()->map(fn ($p) => [
            'Année'             => $p->ligne?->budget?->annee ?? '—',
            'Émetteur'          => $p->emetteur?->user?->name ?? '—',
            'Ligne Originale'   => $p->ligne?->nom ?? '—',
            'Code Budget'       => $p->ligne?->code_complet ?? '—',
            'Montant (DH)'      => number_format($p->montant, 2, '.', ''),
            'Statut'            => match ($p->statut) {
                'approuve' => 'Approuvé',
                'rejete'   => 'Rejeté',
                default    => 'En attente',
            },
            'Motif de refus'    => $p->motif_refus ?? '—',
            'Saisie le'         => $p->created_at?->format('d/m/Y H:i') ?? '—',
        ]);
    }

    public function download(string $filename)
    {
        return (new FastExcel($this->collection()))->download($filename);
    }
}
