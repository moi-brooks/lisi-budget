<?php

namespace App\Http\Controllers\Emetteur;

use App\Http\Controllers\Controller;
use App\Models\Besoin;
use App\Models\Engagement;
use App\Models\Fournisseur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class EngagementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('statut', 'en_attente');
        $emetteur = auth()->user()->emetteur;

        if (!$emetteur) return redirect()->route('emetteur.dashboard');

        $engagements = $emetteur->engagements()
            ->with(['fournisseur', 'ligneProposee.ligne'])
            ->where('statut', $status)
            ->latest()
            ->get();

        return view('emetteur.engagement.index', compact('engagements', 'status'));
    }

    public function create()
    {
        $emetteur = auth()->user()->emetteur;
        
        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.engagements.index')->with('error', 'L\'exercice budgétaire est clôturé. Aucun nouvel engagement possible.');
        }

        // Can only create Engagement on APPROVED propositions
        $lignesApprouvees = $emetteur->lignesProposees()
            ->with('ligne')
            ->where('statut', 'approuve')
            ->get();
            
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('emetteur.engagement.create', compact('lignesApprouvees', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $emetteur = auth()->user()->emetteur;

        if ($emetteur->budget->is_closed) {
            return redirect()->route('emetteur.engagements.index')->with('error', 'L\'exercice budgétaire est clôturé.');
        }

        $validated = $request->validate([
            'ligne_proposee_id' => 'required|exists:ligne_budget_proposees,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
            'tva' => 'required|numeric|min:0|max:100',
            
            // First besoin line items
            'intitule.*' => 'required|string',
            'description.*' => 'nullable|string',
            'quantite.*' => 'required|integer|min:1',
            'prix_unitaire.*' => 'required|numeric|min:0.01',
        ]);

        // Security check
        $ligne = $emetteur->lignesProposees()->findOrFail($validated['ligne_proposee_id']);
        if ($ligne->statut !== 'approuve') {
            abort(403, 'Ligne non approuvée.');
        }

        // --- NEW: Budget Check ---
        $totalHT = 0;
        if ($request->has('intitule')) {
            foreach ($request->intitule as $key => $intitule) {
                $totalHT += ($request->quantite[$key] * $request->prix_unitaire[$key]);
            }
        }
        $totalTTC = $totalHT + ($totalHT * ($validated['tva'] / 100));
        
        if ($totalTTC > $ligne->montant_disponible) {
            $dispo = number_format($ligne->montant_disponible, 2, ',', ' ');
            $demande = number_format($totalTTC, 2, ',', ' ');
            return back()->withInput()->with('error', "Dépassement de budget ! Vous demandez {$demande} DH alors qu'il ne reste que {$dispo} DH disponibles sur cette ligne.");
        }
        // -------------------------

        $engagement = $emetteur->engagements()->create([
            'ligne_proposee_id' => $validated['ligne_proposee_id'],
            'fournisseur_id' => $validated['fournisseur_id'],
            'date' => $validated['date'],
            'commentaire' => $validated['commentaire'] ?? null,
            'tva' => $validated['tva'],
        ]);

        // Ensure array inputs exist safely
        if ($request->has('intitule')) {
            foreach ($request->intitule as $key => $intitule) {
                $qte = $request->quantite[$key];
                $prix = $request->prix_unitaire[$key];
                $engagement->besoins()->create([
                    'intitule' => $intitule,
                    'description' => $request->description[$key] ?? null,
                    'quantite' => $qte,
                    'prix_unitaire' => $prix,
                    'montant' => $qte * $prix,
                ]);
            }
        }
        
        $engagement->calculerTotal();
        $engagement->save();

        return redirect()->route('emetteur.engagements.show', $engagement)->with('success', 'Expression de besoins créée.');
    }

    public function show($engagement)
    {
        $engagement = Engagement::with(['emetteur.user', 'ligneProposee.ligne', 'fournisseur', 'besoins'])
            ->findOrFail($engagement);

        if ($engagement->emetteur_id !== auth()->user()->emetteur->id) {
            abort(403);
        }

        return view('emetteur.engagement.show', compact('engagement'));
    }

    public function download($id)
    {
        $engagement = Engagement::with(['emetteur.user', 'fournisseur', 'besoins', 'ligneProposee.ligne'])
            ->findOrFail($id);

        if ($engagement->emetteur_id !== auth()->user()->emetteur->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.bon_commande', compact('engagement'))
            ->setPaper('a4', 'portrait');

        $filename = 'EB-' . str_pad($engagement->id, 5, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }

    public function storeBesoin(Request $request, $id)
    {
        $engagement = Engagement::findOrFail($id);
        
        if ($engagement->emetteur_id !== auth()->user()->emetteur->id || $engagement->statut !== 'en_attente') {
            abort(403);
        }

        if ($engagement->emetteur->budget->is_closed) {
            return back()->with('error', 'L\'exercice budgétaire est clôturé.');
        }

        $validated = $request->validate([
            'intitule' => 'required|string',
            'description' => 'nullable|string',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0.01',
        ]);

        $qte = $validated['quantite'];
        $prix = $validated['prix_unitaire'];

        $engagement->besoins()->create([
            'intitule' => $validated['intitule'],
            'description' => $validated['description'] ?? null,
            'quantite' => $qte,
            'prix_unitaire' => $prix,
            'montant' => $qte * $prix,
        ]);

        $engagement->calculerTotal();
        $engagement->save();

        return back()->with('success', 'Article ajouté.');
    }

    public function exportEb()
    {
        $emetteur = auth()->user()->emetteur;
        $annee = $emetteur->budget->annee;

        $besoins = Besoin::whereHas('engagement', function($q) use ($emetteur) {
            $q->where('statut', 'approuve')->where('emetteur_id', $emetteur->id);
        })
        ->with('engagement.ligneProposee.ligne')
        ->get()
        ->sortBy('engagement.ligneProposee.ligne.nom');

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft' => 1134, 'marginRight' => 1134,
            'marginTop' => 1134, 'marginBottom' => 1134,
            'pageSizeW' => 11906, 'pageSizeH' => 16838,
        ]);

        $section->addText(
            "Laboratoire : LISI (Laboratoire d'Informatique et de Systèmes Intelligents)",
            ['bold' => true, 'size' => 12]
        );
        $section->addText(
            "Émetteur : " . $emetteur->user->name,
            ['bold' => true, 'size' => 12]
        );
        $section->addText(
            "Tableau — Expression des besoins " . $annee,
            ['bold' => true, 'size' => 12]
        );
        $section->addTextBreak(1);

        $phpWord->addTableStyle('EBTable', [
            'borderColor' => '000000', 'borderSize' => 6, 'cellMargin' => 80,
        ]);
        $table = $section->addTable('EBTable');

        $hStyle = ['bold' => true, 'size' => 12];
        $hPStyle = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];
        $hCell = ['bgColor' => 'D9D9D9', 'valign' => 'center'];

        $table->addRow();
        $table->addCell(2500, $hCell)->addText("Nature du besoin", $hStyle, $hPStyle);
        $table->addCell(4000, $hCell)->addText("Description", $hStyle, $hPStyle);
        $table->addCell(1500, $hCell)->addText("Prix unitaire\n(DH)", $hStyle, $hPStyle);
        $table->addCell(1000, $hCell)->addText("Qté", $hStyle, $hPStyle);
        $table->addCell(2000, $hCell)->addText("Montant estimé\n(DH)", $hStyle, $hPStyle);

        $total = 0;
        foreach ($besoins as $besoin) {
            $table->addRow();
            $nature = $besoin->engagement->ligneProposee->ligne->nom ?? '—';
            $desc = $besoin->intitule . ($besoin->description ? "\n" . $besoin->description : '');
            $pu = floatval($besoin->prix_unitaire);
            $mt = floatval($besoin->montant);
            $total += $mt;
            $table->addCell(null)->addText($nature);
            $table->addCell(null)->addText($desc);
            $table->addCell(null, ['alignment' => Jc::CENTER])->addText(number_format($pu, 2, ',', ' ') . " DH");
            $table->addCell(null, ['alignment' => Jc::CENTER])->addText($besoin->quantite);
            $table->addCell(null, ['alignment' => Jc::CENTER])->addText(number_format($mt, 2, ',', ' ') . " DH");
        }

        $section->addTextBreak(1);
        $section->addText(
            "Total estimé : " . number_format($total, 2, ',', ' ') . " DH",
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::RIGHT]
        );

        $filename = "EB_" . $emetteur->user->name . "_" . $annee . ".docx";
        $tempPath = storage_path('app/temp/' . $filename);
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function markLivre(Request $request, $id)
    {
        $besoin = Besoin::findOrFail($id);
        
        if ($besoin->engagement->emetteur_id !== auth()->user()->emetteur->id) {
            return $request->expectsJson() 
                ? response()->json(['success' => false, 'message' => 'Non autorisé.'], 403)
                : abort(403);
        }

        $validated = $request->validate([
            'is_delivered' => 'required|boolean'
        ]);

        $besoin->update(['is_delivered' => $validated['is_delivered']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_delivered' => (bool)$besoin->is_delivered,
                'message' => 'Statut de livraison mis à jour.'
            ]);
        }

        return back()->with('success', 'Statut de livraison mis à jour.');
    }

}
