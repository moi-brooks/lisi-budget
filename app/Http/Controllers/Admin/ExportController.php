<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Besoin;
use App\Exports\BesoinsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    /**
     * Replicate the professor's official form in DOCX (Aggregated and Anonymous)
     */
    public function exportDocx(Budget $budget)
    {
        $phpWord = new PhpWord();
        
        // Define styles
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft'   => 1134, // ~2cm
            'marginRight'  => 1134,
            'marginTop'    => 1134,
            'marginBottom' => 1134,
            'pageSizeW'    => 11906, // A4 width
            'pageSizeH'    => 16838, // A4 height
        ]);

        // Header: Laboratory Name
        $section->addText(
            "Laboratoire : LISI (Laboratoire d'Informatique et de Systèmes Intelligents)", 
            ['bold' => true, 'size' => 12]
        );

        // Title: Table Title
        $section->addText(
            "Tableau — Expressions de besoins " . $budget->annee, 
            ['bold' => true, 'size' => 12]
        );
        $section->addTextBreak(1);

        // Table definition
        $tableStyle = [
            'borderColor' => '000000',
            'borderSize'  => 6, // ~0.5pt
            'cellMargin'  => 80,
        ];
        $phpWord->addTableStyle('BesoinsTable', $tableStyle);
        $table = $section->addTable('BesoinsTable');

        // Table Headings
        $fontHeaderStyle = ['bold' => true, 'size' => 12];
        $paragraphHeaderStyle = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];
        $cellHeaderStyle = ['bgColor' => 'D9D9D9', 'valign' => 'center'];
        
        $table->addRow();
        $table->addCell(2500, $cellHeaderStyle)->addText("Nature du besoin", $fontHeaderStyle, $paragraphHeaderStyle);
        $table->addCell(4000, $cellHeaderStyle)->addText("Description", $fontHeaderStyle, $paragraphHeaderStyle);
        $table->addCell(1500, $cellHeaderStyle)->addText("Prix unitaire\n(DH)", $fontHeaderStyle, $paragraphHeaderStyle);
        $table->addCell(1000, $cellHeaderStyle)->addText("Qté", $fontHeaderStyle, $paragraphHeaderStyle);
        $table->addCell(2000, $cellHeaderStyle)->addText("Montant estimé\n(DH)", $fontHeaderStyle, $paragraphHeaderStyle);

        // Fetch aggregated needs
        $besoins = Besoin::whereHas('engagement', function($q) use ($budget) {
            $q->where('statut', 'approuve')
              ->whereHas('ligneProposee', function($q2) use ($budget) {
                  $q2->whereHas('ligne', function($q3) use ($budget) {
                      $q3->where('budget_id', $budget->id);
                  });
              });
        })
        ->with('engagement.ligneProposee.ligne')
        ->get()
        ->sortBy([
            ['engagement.ligneProposee.ligne.nom', 'asc'],
            ['id', 'asc'],
        ]);

        $totalGlobal = 0;

        foreach ($besoins as $besoin) {
            $table->addRow();
            
            $nature = $besoin->engagement->ligneProposee->ligne->nom ?? '—';
            $description = $besoin->intitule . ($besoin->description ? "\n" . $besoin->description : "");
            
            $pu = floatval($besoin->prix_unitaire);
            $prix_unitaire = ($pu == intval($pu)) ? intval($pu) : number_format($pu, 2, ',', ' ');
            
            $mt = floatval($besoin->montant);
            $montant = ($mt == intval($mt)) ? intval($mt) : number_format($mt, 2, ',', ' ');
            $totalGlobal += $mt;

            $table->addCell(NULL)->addText($nature);
            $table->addCell(NULL)->addText($description);
            $table->addCell(NULL, ['alignment' => Jc::CENTER])->addText($prix_unitaire . " DH");
            $table->addCell(NULL, ['alignment' => Jc::CENTER])->addText($besoin->quantite);
            $table->addCell(NULL, ['alignment' => Jc::CENTER])->addText($montant . " DH");
        }

        // Final Total Row
        $section->addTextBreak(1);
        $section->addText(
            "Total estimé : " . number_format($totalGlobal, 2, ',', ' ') . " DH", 
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::RIGHT]
        );

        // Save and Download
        $filename = "Expressions_Besoins_" . $budget->annee . ".docx";
        $tempPath = storage_path('app/temp/' . $filename);
        
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    /**
     * Aggregated Excel export
     */
    public function exportExcel(Budget $budget)
    {
        $filename = "Expressions_Besoins_" . $budget->annee . ".xlsx";
        return Excel::download(new BesoinsExport($budget->id, $budget->annee), $filename);
    }
}
