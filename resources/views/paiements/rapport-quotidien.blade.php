<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Rapport quotidien des versements
    </title>

    <style>

        @page {
            margin: 30px 25px 40px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #334155;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #1e293b;
        }

        .header p {
            margin: 5px 0;
            color: #64748b;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            width: 50%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .summary-label {
            font-size: 9px;
            color: #64748b;
        }

        .summary-value {
            margin-top: 4px;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #f1f5f9;
            color: #475569;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 7px 5px;
            text-align: left;
        }

        table.report td {
            border: 1px solid #e2e8f0;
            padding: 6px 5px;
            vertical-align: top;
        }

        table.report tr:nth-child(even) td {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .student-name {
            font-weight: bold;
            color: #334155;
        }

        .matricule {
            margin-top: 2px;
            font-size: 8px;
            color: #94a3b8;
        }

        .classe {
            font-weight: bold;
            color: #334155;
        }

        .section {
            margin-top: 2px;
            font-size: 8px;
            color: #64748b;
        }

        .total-row td {
            background: #f1f5f9 !important;
            font-weight: bold;
            color: #0f172a;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            color: #94a3b8;
            font-size: 8px;
        }

    </style>

</head>


<body>

    {{-- En-tête --}}
    <div class="header">

        <h1>
            Rapport quotidien des versements
        </h1>

        <p>
            Date :
            <strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </strong>
        </p>

        @if($anneeScolaireActive)

            <p>
                Année scolaire :
                <strong>
                    {{ $anneeScolaireActive->libelle ?? $anneeScolaireActive->nom }}
                </strong>
            </p>

        @endif

        <p>
            Généré le
            {{ now()->translatedFormat('d F Y à H:i') }}
        </p>

    </div>


    {{-- Résumé --}}
    <table class="summary">

        <tr>

            <td>

                <div class="summary-label">
                    Nombre de versements
                </div>

                <div class="summary-value">
                    {{ $nombrePaiements }}
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total encaissé
                </div>

                <div class="summary-value">
                    {{ number_format($totalJour, 0, ',', ' ') }} FC
                </div>

            </td>

        </tr>

    </table>


    {{-- Tableau --}}
    <table class="report">

        <thead>

            <tr>

                <th class="text-center">
                    N°
                </th>

                <th>
                    Référence
                </th>

                <th>
                    Matricule
                </th>

                <th>
                    Élève
                </th>

                <th>
                    Section
                </th>

                <th>
                    Motif
                </th>

                <th>
                    Mois
                </th>

                <th class="text-right">
                    Montant
                </th>

                <th>
                    Mode
                </th>

                <th>
                    Agent
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($paiements as $historique)

                @php

                    $paiement = $historique->paiement;

                    $eleve = $paiement?->eleve;

                    /*
                     * L'inscription est déjà fournie par le contrôleur
                     * si tu as repris la logique de detailsJour().
                     */
                    $inscription = $historique->inscription;

                    $classe = $inscription?->classe;

                    /*
                     * Détermination de la section.
                     */
                    $section = match ((int) ($classe?->niveau ?? -1)) {

                        0 => 'Maternelle',

                        1 => 'Primaire',

                        2 => 'Secondaire',

                        3 => 'Humanités',

                        default => '—',

                    };

                @endphp


                <tr>

                    {{-- N° --}}
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>


                    {{-- Référence --}}
                    <td>

                        <strong>
                            {{ $historique->reference ?? '—' }}
                        </strong>

                    </td>


                    {{-- Matricule --}}
                    <td>

                        {{ $eleve?->matricule ?? '—' }}

                    </td>


                    {{-- Élève --}}
                    <td>

                        @if($eleve)

                            <div class="student-name">

                                {{ $eleve->nom }}
                                {{ $eleve->postnom }}

                            </div>

                            @if($eleve->prenom)

                                <div>
                                    {{ $eleve->prenom }}
                                </div>

                            @endif

                        @else

                            <span>
                                Élève supprimé
                            </span>

                        @endif

                    </td>


                    {{-- Section / classe --}}
                    <td>

                        @if($classe)

                            <div class="classe">

                                {{ $classe->nom }}

                                @if($classe->option)

                                    {{ $classe->option }}

                                @endif

                            </div>

                            <div class="section">

                                {{ $section }}

                            </div>

                        @else

                            <span>
                                —
                            </span>

                        @endif

                    </td>


                    {{-- Motif --}}
                    <td>

                        {{ $paiement?->motif ?? '—' }}

                    </td>


                    {{-- Mois --}}
                    <td>

                        @if($paiement?->mois === 'Pas disponible')

                            <span>
                                Pas disponible
                            </span>

                        @else

                            {{ $paiement?->mois ?? 'Pas disponible' }}

                        @endif

                    </td>


                    {{-- Montant --}}
                    <td class="text-right">

                        <strong>

                            {{ number_format($historique->montant ?? 0, 0, ',', ' ') }}

                            FC

                        </strong>

                    </td>


                    {{-- Mode --}}
                    <td>

                        {{ $historique->mode_paiement ?? '—' }}

                    </td>


                    {{-- Agent --}}
                    <td>

                        {{ $historique->createdBy?->nom_complet ?? '—' }}

                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="10" class="text-center">

                        Aucun versement enregistré pour cette date.

                    </td>

                </tr>

            @endforelse


            {{-- Total --}}
            @if($paiements->count() > 0)

                <tr class="total-row">

                    <td colspan="7" class="text-right">

                        Total encaissé

                    </td>

                    <td class="text-right">

                        {{ number_format($totalJour, 0, ',', ' ') }}

                        FC

                    </td>

                    <td colspan="2"></td>

                </tr>

            @endif

        </tbody>

    </table>


    <div class="footer">

        Rapport quotidien des versements —
        {{ $anneeScolaireActive->libelle ?? '' }}

    </div>

</body>

</html>
