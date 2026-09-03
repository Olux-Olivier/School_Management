<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>État de solvabilité</title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 4px 0;
            color: #64748b;
        }

        .criteres {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .criteres td {
            border: 1px solid #cbd5e1;
            padding: 7px;
        }

        .criteres .label {
            width: 18%;
            font-weight: bold;
            background: #f8fafc;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            padding: 8px 10px;
            font-size: 13px;
            font-weight: bold;
            color: white;
            margin-bottom: 0;
        }

        .green {
            background: #15803d;
        }

        .yellow {
            background: #ca8a04;
        }

        .red {
            background: #dc2626;
        }

        table.resultats {
            width: 100%;
            border-collapse: collapse;
        }

        table.resultats th {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }

        table.resultats td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            font-size: 9px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .statut-green {
            color: #15803d;
            font-weight: bold;
        }

        .statut-yellow {
            color: #ca8a04;
            font-weight: bold;
        }

        .statut-red {
            color: #dc2626;
            font-weight: bold;
        }

        .aucun {
            text-align: center;
            padding: 12px;
            border: 1px solid #cbd5e1;
            color: #64748b;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            text-align: center;
            font-size: 8px;
            color: #64748b;
        }

    </style>

</head>


<body>


{{-- ========================================================== --}}
{{-- EN-TÊTE --}}
{{-- ========================================================== --}}

<div class="header">

    <h1>
        ÉTAT DE SOLVABILITÉ
    </h1>

    <p>
        SynergieSchool
    </p>

</div>


{{-- ========================================================== --}}
{{-- CRITÈRES --}}
{{-- ========================================================== --}}

<table class="criteres">

    <tr>

        <td class="label">
            Année scolaire
        </td>

        <td>
            {{ $anneeScolaire->libelle }}
        </td>


        <td class="label">
            Section
        </td>

        <td>
            {{ $classe->section }}
        </td>

    </tr>


    <tr>

        <td class="label">
            Classe
        </td>

        <td>

            {{ $classe->nom_complet ?? $classe->nom }}

            @if($classe->option)
                — {{ $classe->option }}
            @endif

        </td>


        <td class="label">
            Frais
        </td>

        <td>

            {{ $frais->intitule }}

            —

            {{ number_format(
                (float) $frais->montant,
                2,
                ',',
                ' '
            ) }}

        </td>

    </tr>


    @if($request->mois)

        <tr>

            <td class="label">
                Mois
            </td>

            <td colspan="3">
                {{ $request->mois }}
            </td>

        </tr>

    @endif

</table>



{{-- ========================================================== --}}
{{-- EN ORDRE --}}
{{-- ========================================================== --}}

<div class="section">

    <div class="section-title green">

        EN ORDRE

        —
        {{ $enOrdre->count() }} élève(s)

    </div>


    @if($enOrdre->count())

        <table class="resultats">

            <thead>

                <tr>

                    <th style="width: 4%;">
                        N°
                    </th>

                    <th style="width: 13%;">
                        Matricule
                    </th>

                    <th style="width: 25%;">
                        Élève
                    </th>

                    <th style="width: 16%;">
                        Classe
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant dû
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant payé
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Restant
                    </th>

                    <th style="width: 9%;" class="text-center">
                        Statut
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($enOrdre as $index => $ligne)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $ligne->eleve->matricule ?? '-' }}
                        </td>

                        <td>

                            {{ $ligne->eleve->nom ?? '' }}

                            {{ $ligne->eleve->postnom ?? '' }}

                            {{ $ligne->eleve->prenom ?? '' }}

                        </td>

                        <td>

                            {{ $ligne->classe->nom_complet
                                ?? $ligne->classe->nom
                                ?? '-' }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_du,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_paye,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->restant,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-center statut-green">

                            {{ $ligne->statut }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="aucun">
            Aucun élève dans cette catégorie.
        </div>

    @endif

</div>



{{-- ========================================================== --}}
{{-- PARTIELLEMENT PAYÉ --}}
{{-- ========================================================== --}}

<div class="section">

    <div class="section-title yellow">

        PARTIELLEMENT PAYÉ

        —
        {{ $partiellementPaye->count() }} élève(s)

    </div>


    @if($partiellementPaye->count())

        <table class="resultats">

            <thead>

                <tr>

                    <th style="width: 4%;">
                        N°
                    </th>

                    <th style="width: 13%;">
                        Matricule
                    </th>

                    <th style="width: 25%;">
                        Élève
                    </th>

                    <th style="width: 16%;">
                        Classe
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant dû
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant payé
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Restant
                    </th>

                    <th style="width: 9%;" class="text-center">
                        Statut
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($partiellementPaye as $index => $ligne)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $ligne->eleve->matricule ?? '-' }}
                        </td>

                        <td>

                            {{ $ligne->eleve->nom ?? '' }}

                            {{ $ligne->eleve->postnom ?? '' }}

                            {{ $ligne->eleve->prenom ?? '' }}

                        </td>

                        <td>

                            {{ $ligne->classe->nom_complet
                                ?? $ligne->classe->nom
                                ?? '-' }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_du,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_paye,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->restant,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-center statut-yellow">

                            {{ $ligne->statut }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="aucun">
            Aucun élève dans cette catégorie.
        </div>

    @endif

</div>



{{-- ========================================================== --}}
{{-- NON EN ORDRE --}}
{{-- ========================================================== --}}

<div class="section">

    <div class="section-title red">

        NON EN ORDRE

        —
        {{ $nonEnOrdre->count() }} élève(s)

    </div>


    @if($nonEnOrdre->count())

        <table class="resultats">

            <thead>

                <tr>

                    <th style="width: 4%;">
                        N°
                    </th>

                    <th style="width: 13%;">
                        Matricule
                    </th>

                    <th style="width: 25%;">
                        Élève
                    </th>

                    <th style="width: 16%;">
                        Classe
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant dû
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Montant payé
                    </th>

                    <th style="width: 11%;" class="text-right">
                        Restant
                    </th>

                    <th style="width: 9%;" class="text-center">
                        Statut
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($nonEnOrdre as $index => $ligne)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $ligne->eleve->matricule ?? '-' }}
                        </td>

                        <td>

                            {{ $ligne->eleve->nom ?? '' }}

                            {{ $ligne->eleve->postnom ?? '' }}

                            {{ $ligne->eleve->prenom ?? '' }}

                        </td>

                        <td>

                            {{ $ligne->classe->nom_complet
                                ?? $ligne->classe->nom
                                ?? '-' }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_du,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->montant_paye,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-right">

                            {{ number_format(
                                (float) $ligne->restant,
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                        <td class="text-center statut-red">

                            {{ $ligne->statut }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="aucun">
            Aucun élève dans cette catégorie.
        </div>

    @endif

</div>



{{-- ========================================================== --}}
{{-- PIED DE PAGE --}}
{{-- ========================================================== --}}

<div class="footer">

    Document généré le
    {{ now()->format('d/m/Y à H:i') }}

</div>


</body>

</html>
