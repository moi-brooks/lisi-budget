<?php

namespace App\Exports;

use App\Models\Engagement;
use Rap2hpoutre\FastExcel\FastExcel;

class EngagementsExport
{
    protected ?string $saison;
    protected ?string $statut;
    protected ?int $annee;
    protected ?int $emetteur_id;
    protected ?int $ligne_id;

    public function __construct(
        ?string $saison = null, 
        ?string $statut = null,
        ?int $annee = null,
        ?int $emetteur_id = null,
        ?int $ligne_id = null
    ) {
        $this->saison      = $saison;
        $this->statut      = $statut;
        $this->annee       = $annee;
        $this->emetteur_id = $emetteur_id;
        $this->ligne_id    = $ligne_id;
    }

    public function collection()
    {
        $query = Engagement::with(['emetteur.user', 'ligneProposee.ligne.budget', 'fournisseur'])
            ->latest();

        if ($this->saison) {
            $query->whereHas('ligneProposee.ligne.budget', function ($q) {
                $q->where('saison', $this->saison);
            });
        }

        if ($this->annee) {
            $query->whereHas('ligneProposee.ligne.budget', function ($q) {
                $q->where('annee', $this->annee);
            });
        }

        if ($this->emetteur_id) {
            $query->where('emetteur_id', $this->emetteur_id);
        }

        if ($this->ligne_id) {
            $query->whereHas('ligneProposee', function ($q) {
                $q->where('ligne_id', $this->ligne_id);
            });
        }

        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        return $query->get()->map(fn ($e) => [
            'N° EB'             => str_pad($e->id, 5, '0', STR_PAD_LEFT),
            'Date'              => $e->date?->format('d/m/Y') ?? '—',
            'Émetteur'          => $e->emetteur?->user?->name ?? '—',
            'Fournisseur'       => $e->fournisseur_nom ?? $e->fournisseur?->nom ?? '—',
            'Budget (Saison)'   => $e->ligneProposee?->ligne?->budget?->saison ?? '—',
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
