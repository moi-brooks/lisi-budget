<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

        /* Header */
        .header { display: table; width: 100%; margin-bottom: 28px; border-bottom: 3px solid #CC3333; padding-bottom: 16px; }
        .header-left  { display: table-cell; width: 60%; vertical-align: middle; }
        .header-right { display: table-cell; width: 40%; text-align: right; vertical-align: middle; }
        .company-name { font-size: 13px; font-weight: bold; color: #CC3333; letter-spacing: 1px; margin-top: 4px; }
        .company-sub  { font-size: 9px; color: #64748b; margin-top: 2px; }
        .doc-title    { font-size: 22px; font-weight: bold; color: #1e293b; }
        .doc-number   { font-size: 11px; color: #64748b; margin-top: 4px; }

        /* Status badge */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-attente  { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-approuve { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-rejete   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Info cards */
        .info-grid { display: table; width: 100%; margin-bottom: 24px; border-collapse: separate; border-spacing: 8px; }
        .info-card { display: table-cell; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 14px; vertical-align: top; width: 33%; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; font-weight: bold; margin-bottom: 5px; }
        .info-value { font-size: 12px; font-weight: bold; color: #1e293b; }
        .info-sub   { font-size: 10px; color: #64748b; margin-top: 2px; }

        /* Ligne budgétaire */
        .ligne-box { background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 10px 14px; margin-bottom: 24px; }
        .ligne-box .info-label { color: #6366f1; }
        .ligne-code { font-family: DejaVu Sans Mono, monospace; font-size: 11px; color: #4f46e5; background: #fff; border: 1px solid #c7d2fe; border-radius: 3px; padding: 2px 6px; margin-right: 8px; }
        .ligne-nom  { font-size: 12px; font-weight: bold; color: #3730a3; }

        /* Table */
        .table-title { font-size: 13px; font-weight: bold; color: #1e293b; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 2px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead tr { background: #4f46e5; color: #fff; }
        thead th { padding: 9px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 9px 10px; font-size: 11px; color: #334155; vertical-align: top; }
        tbody td.right { text-align: right; font-weight: bold; color: #4f46e5; }
        tbody td.center { text-align: center; }
        .art-desc { font-size: 9px; color: #94a3b8; margin-top: 2px; }

        /* Totals */
        .totals-wrap { display: table; width: 100%; }
        .totals-spacer { display: table-cell; width: 55%; }
        .totals-box    { display: table-cell; width: 45%; vertical-align: top; }
        .totals-row { display: table; width: 100%; margin-bottom: 4px; }
        .totals-label { display: table-cell; font-size: 11px; color: #64748b; padding: 5px 10px; }
        .totals-value { display: table-cell; font-size: 11px; font-weight: bold; color: #1e293b; text-align: right; padding: 5px 10px; }
        .totals-final { background: #4f46e5; border-radius: 6px; margin-top: 6px; }
        .totals-final .totals-label { color: #e0e7ff; font-size: 12px; font-weight: bold; }
        .totals-final .totals-value { color: #fff; font-size: 16px; font-weight: bold; }

        /* Motif refus */
        .refus-box { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; }
        .refus-title { font-size: 10px; font-weight: bold; color: #dc2626; text-transform: uppercase; margin-bottom: 4px; }
        .refus-text  { font-size: 11px; color: #7f1d1d; font-style: italic; }

        /* Footer */
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 12px; display: table; width: 100%; }
        .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; }
        .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('lisi_logo_transparent.png') }}" style="height: 40px; width: auto;" alt="LISI">
            <div class="company-name">E-Intendance</div>
            <div class="company-sub">Système de Gestion Budgétaire — LISI</div>
        </div>
        <div class="header-right">
            <div class="doc-title">BON DE COMMANDE</div>
            <div class="doc-number">N° {{ str_pad($engagement->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div style="margin-top:6px;">
                @php
                    $badgeClass = match($engagement->statut) {
                        'approuve' => 'badge-approuve',
                        'rejete'   => 'badge-rejete',
                        default    => 'badge-attente',
                    };
                    $badgeLabel = match($engagement->statut) {
                        'approuve' => 'Approuvé',
                        'rejete'   => 'Rejeté',
                        default    => 'En attente',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        </div>
    </div>

    {{-- MOTIF REFUS --}}
    @if($engagement->statut === 'rejete' && $engagement->motif_refus)
        <div class="refus-box">
            <div class="refus-title">Motif de refus</div>
            <div class="refus-text">{{ $engagement->motif_refus }}</div>
        </div>
    @endif

    {{-- INFO CARDS --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Émetteur</div>
            <div class="info-value">{{ $engagement->emetteur?->user?->name ?? 'N/A' }}</div>
            <div class="info-sub">{{ $engagement->emetteur?->profession ?? '' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Fournisseur</div>
            <div class="info-value">{{ $engagement->fournisseur?->nom ?? 'Non défini' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Date de commande</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($engagement->date)->format('d/m/Y') }}</div>
            <div class="info-sub">Créé le {{ $engagement->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- LIGNE BUDGÉTAIRE --}}
    <div class="ligne-box">
        <div class="info-label">Ligne budgétaire imputée</div>
        <div style="margin-top:5px;">
            <span class="ligne-code">{{ $engagement->ligneProposee?->ligne?->code_complet ?? 'N/A' }}</span>
            <span class="ligne-nom">{{ $engagement->ligneProposee?->ligne?->nom ?? 'N/A' }}</span>
        </div>
    </div>

    {{-- ARTICLES --}}
    <div class="table-title">Articles & Besoins</div>
    <table>
        <thead>
            <tr>
                <th style="width:40%">Intitulé</th>
                <th class="center" style="width:10%">Qté</th>
                <th class="right" style="width:20%">Prix Unitaire HT</th>
                <th class="right" style="width:20%">Total HT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($engagement->besoins as $besoin)
                <tr>
                    <td>
                        {{ $besoin->intitule }}
                        @if($besoin->description)
                            <div class="art-desc">{{ $besoin->description }}</div>
                        @endif
                    </td>
                    <td class="center">{{ $besoin->quantite }}</td>
                    <td class="right">{{ number_format($besoin->prix_unitaire, 2, ',', ' ') }} DH</td>
                    <td class="right">{{ number_format($besoin->montant, 2, ',', ' ') }} DH</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#94a3b8; font-style:italic; padding:16px;">
                        Aucun article.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <div class="totals-wrap">
        <div class="totals-spacer"></div>
        <div class="totals-box">
            <div class="totals-row">
                <div class="totals-label">Total HT</div>
                <div class="totals-value">{{ number_format($engagement->total_ht, 2, ',', ' ') }} DH</div>
            </div>
            <div class="totals-row">
                <div class="totals-label">TVA ({{ $engagement->tva }} %)</div>
                <div class="totals-value">{{ number_format($engagement->total_ttc - $engagement->total_ht, 2, ',', ' ') }} DH</div>
            </div>
            <div class="totals-row totals-final">
                <div class="totals-label">Total TTC</div>
                <div class="totals-value">{{ number_format($engagement->total_ttc, 2, ',', ' ') }} DH</div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="footer-left">
            Document généré le {{ now()->format('d/m/Y à H:i') }} — E-Intendance (LISI)
        </div>
        <div class="footer-right">
            BC N° {{ str_pad($engagement->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>

</body>
</html>
