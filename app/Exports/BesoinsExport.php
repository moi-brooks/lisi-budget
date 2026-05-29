<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Besoin;

class BesoinsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $budgetId;
    protected $annee;

    public function __construct(int $budgetId, $annee)
    {
        $this->budgetId = $budgetId;
        $this->annee = $annee;
    }

    public function collection()
    {
        return Besoin::whereHas('engagement', function($q) {
            $q->where('statut', 'approuve')
              ->whereHas('ligneProposee', function($q2) {
                  $q2->whereHas('ligne', function($q3) {
                      $q3->where('budget_id', $this->budgetId);
                  });
              });
        })
        ->with('engagement.ligneProposee.ligne')
        ->get()
        ->sortBy([
            ['engagement.ligneProposee.ligne.nom', 'asc'],
            ['id', 'asc'],
        ]);
    }

    public function headings(): array
    {
        return [
            ['Expressions de besoins - ' . $this->annee], // Title row
            [
                'Nature du besoin',
                'Description',
                'Prix unitaire (DH)',
                'Quantité',
                'Montant estimé (DH)'
            ]
        ];
    }

    public function map($besoin): array
    {
        $nature = $besoin->engagement->ligneProposee->ligne->nom ?? '—';
        $description = $besoin->intitule . ($besoin->description ? "\n" . $besoin->description : "");
        
        $pu = $besoin->prix_unitaire;
        $prix_unitaire = (floatval($pu) == intval($pu)) ? intval($pu) : number_format($pu, 2, ',', ' ');
        
        $mt = $besoin->montant;
        $montant = (floatval($mt) == intval($mt)) ? intval($mt) : number_format($mt, 2, ',', ' ');

        return [
            $nature,
            $description,
            $prix_unitaire . " DH",
            $besoin->quantite,
            $montant . " DH"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $totalSum = $this->collection()->sum('montant');
        
        // Add total row manually if needed, but Maatwebsite can do it via WithEvents or just map.
        // For simplicity, we add it here or in the collection.
        // Actually, we'll append it to the sheet.
        $sheet->appendRow([
            'TOTAL',
            '',
            '',
            '',
            number_format($totalSum, 2, ',', ' ') . " DH"
        ]);
        
        $newLastRow = $sheet->getHighestRow();

        return [
            // Style for first row (Title)
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Style for header row
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9']
                ]
            ],
            // Style for total row
            $newLastRow => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return "Expressions de besoins " . $this->annee;
    }
}
