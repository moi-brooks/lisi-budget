<?php

namespace App\Exports;

use App\Models\Engagement;
use Rap2hpoutre\FastExcel\FastExcel;

class EngagementsExport
{
    protected ?string $saison;
    protected ?string $statut;

    public function __construct(?string $saison = null, ?string $statut = null)
    {
        $this->saison  = $saison;
        $this->statut  = $statut;
    }

    public function collection()
    {
        $query = Engagement::with(['emetteur.user', 'ligneProposee.ligne', 'fournisseur'])
            ->latest();

        if ($this->saison) {
            $query->whereHas('ligneProposee.ligne.budget', function ($q) {
                $q->where('saison', $this->saison);
            });
        }

        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        return $query->get()->map(fn ($e) => [
            'N° BC'             => str_pad($e->id, 5, '0', STR_PAD_LEFT),
            'Date'              => $e->date?->format('d/m/Y') ?? '—',
            'Émetteur'          => $e->emetteur?->user?->name ?? '—',
            'Fournisseur'       => $e->fournisseur?->nom ?? '—',
            'Ligne budgétaire'  => $e->ligneProposee?->ligne?->nom ?? '—',
            'Montant HT (DH)'   => number_format($e->total_ht, 2, '.', ''),
            'TVA (%)'           => $e->tva,
            'Montant TTC (DH)'  => number_format($e->total_ttc, 2, '.', ''),
            'Statut'            => match ($e->statut) {
                'approuve' => 'Approuvé',
                'rejete'   => 'Rejeté',
                default    => 'En attente',
            },
            'Motif de refus'    => $e->motif_refus ?? '—',
        ]);
    }

    public function download(string $filename)
    {
        return (new FastExcel($this->collection()))->download($filename);
    }
}
