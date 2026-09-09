<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Besoin;

class BesoinsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, ShouldAutoSize, WithTitle
{
    protected $budgetId;
    protected $annee;

    /** Collection mise en cache pour éviter une double requête. */
    protected $besoins;

    public function __construct(int $budgetId, $annee)
    {
        $this->budgetId = $budgetId;
        $this->annee = $annee;
    }

    public function collection()
    {
        if ($this->besoins !== null) {
            return $this->besoins;
        }

        return $this->besoins = Besoin::whereHas('engagement', function ($q) {
            $q->where('statut', 'approuve')
              ->whereHas('ligneProposee', function ($q2) {
                  $q2->whereHas('ligne', function ($q3) {
                      $q3->where('budget_id', $this->budgetId);
                  });
              });
        })
        ->with('engagement.ligneProposee.ligne')
        ->get()
        ->sortBy([
            ['engagement.ligneProposee.ligne.nom', 'asc'],
            ['id', 'asc'],
        ])
        ->values();
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
                'Montant estimé (DH)',
            ],
        ];
    }

    public function map($besoin): array
    {
        $nature = $besoin->engagement->ligneProposee->ligne->nom ?? '—';
        $description = $besoin->intitule . ($besoin->description ? "\n" . $besoin->description : '');

        $pu = $besoin->prix_unitaire;
        $prix_unitaire = (floatval($pu) == intval($pu)) ? intval($pu) : number_format($pu, 2, ',', ' ');

        $mt = $besoin->montant;
        $montant = (floatval($mt) == intval($mt)) ? intval($mt) : number_format($mt, 2, ',', ' ');

        return [
            $nature,
            $description,
            $prix_unitaire . ' DH',
            $besoin->quantite,
            $montant . ' DH',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Ligne 1 : titre
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Ligne 2 : en-têtes
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];
    }

    /**
     * Ajoute la ligne TOTAL après les données (appendRow() n'existe pas
     * sur Worksheet — on écrit directement dans la feuille via AfterSheet).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalSum = $this->collection()->sum('montant');

                $row = $sheet->getHighestRow() + 1;
                $sheet->setCellValue("A{$row}", 'TOTAL');
                $sheet->setCellValue("E{$row}", number_format($totalSum, 2, ',', ' ') . ' DH');
                $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
            },
        ];
    }

    public function title(): string
    {
        return 'Expressions de besoins ' . $this->annee;
    }
}
