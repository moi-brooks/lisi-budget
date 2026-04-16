<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; padding: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #334155; line-height: 1.6; background: #fff; padding: 20px; }

        /* Structural Layout */
        .w-full { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .centered { text-align: center; }
        .right { text-align: right; }
        .v-top { vertical-align: top; }
        .v-middle { vertical-align: middle; }

        /* Header Branding */
        .doc-title { font-size: 19pt; font-weight: bold; color: #1e293b; text-transform: uppercase; margin: 15px 0 5px 0; letter-spacing: 1.5px; }
        .doc-ref { font-size: 10.5pt; color: #64748b; font-weight: bold; letter-spacing: 0.5px; }
        .university-info { font-size: 9.5pt; color: #1e293b; font-weight: bold; }
        .lab-info { font-size: 8.5pt; color: #94a3b8; }

        /* Status Pill */
        .status-badge { display: inline-block; padding: 4px 15px; border-radius: 20px; border: 1px solid #e2e8f0; font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #64748b; margin-top: 15px; }

        /* Metadata Grid */
        .meta-container { margin: 25px 0; border: 1.5px solid #f1f5f9; border-radius: 6px; }
        .meta-cell { padding: 12px; border: 1px solid #f1f5f9; width: 33.33%; text-align: left; }
        .meta-label { font-size: 7.5pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px; display: block; }
        .meta-value { font-size: 10pt; font-weight: bold; color: #1e293b; display: block; }

        /* Articles Table */
        .section-header { font-size: 11pt; font-weight: bold; color: #1e293b; margin-bottom: 10px; border-bottom: 2px solid #f1f5f9; padding-bottom: 5px; }
        .items-table { margin-bottom: 25px; border: 1.5px solid #e2e8f0; }
        .items-table th { background: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #475569; text-align: left; }
        .items-table td { padding: 12px; border: 1px solid #e2e8f0; font-size: 9pt; color: #334155; }

        /* Financials */
        .totals-table { width: 280px; margin-left: auto; border: 1.5px solid #1e293b; border-radius: 4px; overflow: hidden; }
        .totals-table td { padding: 10px 15px; font-size: 10pt; border: none; }
        .totals-table .label { color: #64748b; background: #fcfcfc; border-right: 1px solid #f1f5f9; }
        .totals-table .value { font-weight: bold; color: #1e293b; text-align: right; }
        .totals-table .total-row { background: #1e293b; color: #fff; }
        .totals-table .total-row td { border: none; color: #fff; }
        .totals-table .total-row .v { font-size: 13pt; font-weight: 900; }

        /* Signatures */
        .sig-section { margin-top: 40px; }
        .sig-cell { padding: 0 10px; width: 33.33%; text-align: center; }
        .sig-label { font-size: 8.5pt; font-weight: bold; color: #475569; text-transform: uppercase; margin-bottom: 15px; }
        .sig-box { border: 1px dashed #cbd5e1; height: 120px; border-radius: 8px; background: #fbfbfb; }

        /* Footer */
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; font-size: 7.5pt; color: #cbd5e1; text-align: center; border-top: 1px solid #f8fafc; padding-top: 5px; }
    </style>
</head>
<body>

    {{-- HEADER SECTION --}}
    <table class="w-full">
        <tr>
            <td class="centered">
                <table style="margin: 0 auto; border-collapse: collapse;">
                    <tr>
                        <td class="v-middle" style="padding-right: 25px;">
                            <img src="{{ public_path('logo_fssm_transparent.png') }}" style="height: 60px; width: auto;">
                        </td>
                        <td class="v-middle" style="padding-left: 25px; border-left: 2px solid #f1f5f9;">
                            <img src="{{ public_path('lisi_logo_transparent.png') }}" style="height: 45px; width: auto;">
                        </td>
                    </tr>
                </table>
                <div class="university-info" style="margin-top: 20px;">Université Cadi Ayyad — Marrakech</div>
                <div class="lab-info">Laboratoire d'Informatique et Systèmes Intelligents (LISI) — FSSM</div>
                
                <h1 class="doc-title">Expression de Besoins</h1>
                <div class="doc-ref">BON DE COMMANDE INTERNE N° EB-{{ str_pad($engagement->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="status-badge">
                    {{ match($engagement->statut) {
                        'approuve' => 'Validée / Approved',
                        'rejete' => 'Refusée / Rejected',
                        default => 'En Attente / Pending'
                    } }}
                </div>
            </td>
        </tr>
    </table>

    {{-- REJECTION NOTE --}}
    @if($engagement->statut === 'rejete' && $engagement->motif_refus)
        <table class="w-full">
            <tr>
                <td>
                    <div style="background: #fff1f2; border: 1.5px solid #fecaca; border-radius: 8px; padding: 15px; margin-top: 10px;">
                        <span style="font-size: 8pt; font-weight: bold; color: #b91c1c; text-transform: uppercase;">Motif du rejet administratif :</span>
                        <p style="font-size: 10pt; color: #7f1d1d; margin-top: 5px; font-style: italic;">"{{ $engagement->motif_refus }}"</p>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    {{-- METADATA GRID --}}
    <table class="w-full meta-container">
        <tr>
            <td class="meta-cell v-top">
                <span class="meta-label">Demandeur</span>
                <span class="meta-value">{{ $engagement->emetteur?->user?->name ?? 'N/A' }}</span>
                <div style="font-size: 8pt; color: #94a3b8; margin-top: 4px;">Date d'émission : {{ $engagement->created_at?->format('d/m/Y') }}</div>
            </td>
            <td class="meta-cell v-top">
                <span class="meta-label">Fournisseur Suggéré</span>
                <span class="meta-value">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</span>
            </td>
            <td class="meta-cell v-top">
                <span class="meta-label">Affectation Budgétaire</span>
                <span class="meta-value" style="font-family: monospace; font-size: 9pt;">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
                <div style="font-size: 8pt; color: #94a3b8; margin-top: 4px;">{{ $engagement->ligneProposee?->ligne?->nom ?? 'LISI Budget' }}</div>
            </td>
        </tr>
    </table>

    {{-- ARTICLES TABLE --}}
    <div class="section-header">Description des Besoins</div>
    <table class="w-full items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Intitulé & Description</th>
                <th class="centered" style="width: 10%;">Qté</th>
                <th class="right" style="width: 20%;">P.U HT (DH)</th>
                <th class="right" style="width: 20%;">Total HT (DH)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($engagement->besoins as $besoin)
                <tr>
                    <td class="v-top">
                        <div style="font-weight: bold; color: #1e293b;">{{ $besoin->intitule }}</div>
                        @if($besoin->description)
                            <div style="font-size: 8pt; color: #94a3b8; margin-top: 2px;">{{ $besoin->description }}</div>
                        @endif
                    </td>
                    <td class="centered v-top">{{ $besoin->quantite }}</td>
                    <td class="right v-top">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }}</td>
                    <td class="right v-top" style="font-weight: bold;">{{ number_format($besoin->montant, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS SUMMARY --}}
    <table class="totals-table">
        <tr>
            <td class="label">Total Brut HT</td>
            <td class="value">{{ number_format($engagement->total_ht, 2, ',', ' ') }}</td>
        </tr>
        <tr>
            <td class="label">TVA ({{ (int)$engagement->tva }}%)</td>
            <td class="value">{{ number_format($engagement->total_ttc - $engagement->total_ht, 2, ',', ' ') }}</td>
        </tr>
        <tr class="total-row">
            <td style="font-weight: bold;">NET À PAYER TTC</td>
            <td class="v right" style="font-family: monospace;">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} DH</td>
        </tr>
    </table>

    {{-- SIGNATURES SECTION --}}
    <div class="sig-section">
        <table class="w-full">
            <tr>
                <td class="sig-cell">
                    <div class="sig-label">Le Demandeur</div>
                    <div class="sig-box"></div>
                </td>
                <td class="sig-cell">
                    <div class="sig-label">L'Intendance (LISI)</div>
                    <div class="sig-box"></div>
                </td>
                <td class="sig-cell">
                    <div class="sig-label">Direction Labo</div>
                    <div class="sig-box"></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Document d'Expression de Besoins LISI — Marrakech — Généré par E-Intendance
    </div>

</body>
</html>
