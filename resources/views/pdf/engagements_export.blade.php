<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export des Engagements</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; background: #fff; padding: 20px; }

        .header { display: table; width: 100%; margin-bottom: 20px; border-bottom: 3px solid #CC3333; padding-bottom: 10px; }
        .header-left { display: table-cell; width: 60%; vertical-align: bottom; }
        .header-right { display: table-cell; width: 40%; text-align: right; vertical-align: bottom; }
        
        .company-name { font-size: 14px; font-weight: bold; color: #CC3333; margin-top: 4px; }
        .doc-title { font-size: 18px; font-weight: bold; color: #1e293b; text-transform: uppercase; }
        .doc-meta { font-size: 10px; color: #64748b; margin-top: 5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px 6px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #CC3333; color: white; font-size: 9px; text-transform: uppercase; }
        tr:nth-child(even) { background: #f8fafc; }
        
        .right { text-align: right; }
        .center { text-align: center; }
        
        .statut-approuve { color: #16a34a; font-weight: bold; }
        .statut-rejete { color: #dc2626; font-weight: bold; }
        .statut-attente { color: #ca8a04; font-weight: bold; }

        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 8px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('lisi_logo_transparent.png') }}" style="height: 30px; width: auto;" alt="LISI">
            <div class="company-name">E-Intendance</div>
        </div>
        <div class="header-right">
            <div class="doc-title">Liste des Engagements</div>
            <div class="doc-meta">
                @if($saison) Saison: {{ $saison }} | @endif
                Statut: {{ ucfirst(str_replace('_', ' ', $status)) }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">N°</th>
                <th style="width: 10%">Date</th>
                <th style="width: 15%">Émetteur</th>
                <th style="width: 15%">Fournisseur</th>
                <th style="width: 20%">Ligne budgétaire</th>
                <th class="right" style="width: 10%">M. HT (DH)</th>
                <th class="right" style="width: 10%">M. TTC (DH)</th>
                <th style="width: 15%">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($engagements as $row)
                <tr>
                    <td>{{ $row['N° BC'] }}</td>
                    <td>{{ $row['Date'] }}</td>
                    <td>{{ $row['Émetteur'] }}</td>
                    <td>{{ mb_strimwidth($row['Fournisseur'], 0, 20, '...') }}</td>
                    <td>{{ mb_strimwidth($row['Ligne budgétaire'], 0, 25, '...') }}</td>
                    <td class="right">{{ $row['Montant HT (DH)'] }}</td>
                    <td class="right"><strong>{{ $row['Montant TTC (DH)'] }}</strong></td>
                    <td class="
                        @if($row['Statut'] === 'Approuvé') statut-approuve 
                        @elseif($row['Statut'] === 'Rejeté') statut-rejete 
                        @else statut-attente @endif
                    ">
                        {{ $row['Statut'] }}
                        @if($row['Statut'] === 'Rejeté' && $row['Motif de refus'] !== '—')
                            <br><span style="font-size: 8px; font-weight: normal; color: #dc2626;">{{ mb_strimwidth($row['Motif de refus'], 0, 30, '...') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — Système E-Intendance (LISI) — Université Cadi Ayyad
    </div>

</body>
</html>
