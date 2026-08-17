<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        @if($estReinscription)
            Fiche de réinscription
        @else
            Fiche d'inscription
        @endif
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }

        .document-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 5px;
        }

        .section {
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .section-title {
            background: #f1f5f9;
            border-left: 4px solid #2563eb;
            padding: 9px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #e2e8f0;
            padding: 9px;
            vertical-align: top;
        }

        .label {
            width: 35%;
            background: #f8fafc;
            font-weight: bold;
            color: #475569;
        }

        .value {
            color: #1e293b;
        }

        .montant {
            font-size: 16px;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .reinscription-box {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            text-align: center;
            font-weight: bold;
        }

        .arrow {
            font-size: 16px;
            font-weight: bold;
            padding: 0 10px;
        }

        .signature-table {
            margin-top: 50px;
        }

        .signature {
            text-align: center;
            border: none;
            padding-top: 40px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }

    </style>

</head>


<body>


{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="header">

    <div class="school-name">
        SYNERGIE SCHOOL
    </div>

    <div>
        Gestion scolaire
    </div>

    <div class="document-title">

        @if($estReinscription)

            FICHE DE RÉINSCRIPTION

        @else

            FICHE D'INSCRIPTION

        @endif

    </div>

    <div class="document-subtitle">

        Année scolaire :
        {{ $inscription->anneeScolaire->libelle ?? '—' }}

    </div>

</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS ÉLÈVE --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        1. Informations de l'élève
    </div>


    <table>

        <tr>

            <td class="label">
                Matricule
            </td>

            <td class="value">
                {{ $inscription->eleve->matricule ?? '—' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Nom
            </td>

            <td class="value">
                {{ $inscription->eleve->nom ?? '—' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Postnom
            </td>

            <td class="value">
                {{ $inscription->eleve->postnom ?? '—' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Prénom
            </td>

            <td class="value">
                {{ $inscription->eleve->prenom ?? '—' }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Sexe
            </td>

            <td class="value">
                {{ $inscription->eleve->sexe_libelle ?? '—' }}
            </td>

        </tr>


        @if(!empty($inscription->eleve->date_naissance))

            <tr>

                <td class="label">
                    Date de naissance
                </td>

                <td class="value">

                    {{ \Carbon\Carbon::parse(
                        $inscription->eleve->date_naissance
                    )->format('d/m/Y') }}

                </td>

            </tr>

        @endif


        <tr>

            <td class="label">
                Téléphone
            </td>

            <td class="value">
                {{ $inscription->eleve->telephone ?? '—' }}
            </td>

        </tr>

    </table>

</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS SCOLAIRES --}}
{{-- ========================================================= --}}

<div class="section">

    @if($estReinscription)

        {{-- ================================================= --}}
        {{-- CAS RÉINSCRIPTION --}}
        {{-- ================================================= --}}

        <div class="section-title">
            2. Parcours scolaire
        </div>


        <table>

            <tr>

                <td class="label">
                    Année scolaire précédente
                </td>

                <td class="value">

                    {{ $ancienneInscription->anneeScolaire->libelle ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Ancienne classe
                </td>

                <td class="value">

                    {{ $ancienneInscription->classe->nom_complet
                        ?? $ancienneInscription->classe->nom
                        ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Année scolaire actuelle
                </td>

                <td class="value">

                    {{ $inscription->anneeScolaire->libelle ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Nouvelle classe
                </td>

                <td class="value">

                    {{ $inscription->classe->nom_complet
                        ?? $inscription->classe->nom
                        ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Section
                </td>

                <td class="value">

                    {{ $inscription->classe->section ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Option
                </td>

                <td class="value">

                    {{ $inscription->classe->option ?: '—' }}

                </td>

            </tr>

        </table>


        {{-- Résumé ancien → nouveau --}}

        <div class="reinscription-box">

            {{ $ancienneInscription->classe->nom_complet
                ?? $ancienneInscription->classe->nom
                ?? '—' }}

            <span class="arrow">
                →
            </span>

            {{ $inscription->classe->nom_complet
                ?? $inscription->classe->nom
                ?? '—' }}

        </div>


    @else

        {{-- ================================================= --}}
        {{-- CAS PREMIÈRE INSCRIPTION --}}
        {{-- ================================================= --}}

        <div class="section-title">
            2. Informations scolaires
        </div>


        <table>

            <tr>

                <td class="label">
                    Année scolaire
                </td>

                <td class="value">

                    {{ $inscription->anneeScolaire->libelle ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Section
                </td>

                <td class="value">

                    {{ $inscription->classe->section ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Classe
                </td>

                <td class="value">

                    {{ $inscription->classe->nom_complet
                        ?? $inscription->classe->nom
                        ?? '—' }}

                </td>

            </tr>


            <tr>

                <td class="label">
                    Option
                </td>

                <td class="value">

                    {{ $inscription->classe->option ?: '—' }}

                </td>

            </tr>

        </table>

    @endif

</div>


{{-- ========================================================= --}}
{{-- INSCRIPTION ET PAIEMENT --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        3. Informations de l'inscription
    </div>


    <table>

        <tr>

            <td class="label">
                Date d'inscription
            </td>

            <td class="value">

                {{ $inscription->date_inscription
                    ? $inscription->date_inscription->format('d/m/Y')
                    : '—' }}

            </td>

        </tr>


        <tr>

            <td class="label">
                Montant versé
            </td>

            <td class="value montant">

                {{ number_format(
                    $inscription->montant ?? 0,
                    2,
                    ',',
                    ' '
                ) }}

                FC

            </td>

        </tr>


        <tr>

            <td class="label">
                Statut
            </td>

            <td class="value">

                @if($inscription->actif)

                    <span class="status active">
                        Active
                    </span>

                @else

                    <span class="status inactive">
                        Inactive
                    </span>

                @endif

            </td>

        </tr>

    </table>

</div>


{{-- ========================================================= --}}
{{-- TRAÇABILITÉ --}}
{{-- ========================================================= --}}

<div class="section">

    <div class="section-title">
        4. Traçabilité
    </div>


    <table>

        <tr>

            <td class="label">
                Enregistré par
            </td>

            <td class="value">

                {{ $inscription->createdBy->nom_complet ?? '—' }}

            </td>

        </tr>


        <tr>

            <td class="label">
                Date d'enregistrement
            </td>

            <td class="value">

                {{ $inscription->created_at
                    ? $inscription->created_at->format('d/m/Y à H:i')
                    : '—' }}

            </td>

        </tr>

    </table>

</div>


{{-- ========================================================= --}}
{{-- SIGNATURES --}}
{{-- ========================================================= --}}

<table class="signature-table">

    <tr>

        <td class="signature">

            Signature du responsable

            <br><br><br>

            __________________________

        </td>


        <td class="signature">

            Signature du parent / tuteur

            <br><br><br>

            __________________________

        </td>

    </tr>

</table>


{{-- ========================================================= --}}
{{-- PIED DE PAGE --}}
{{-- ========================================================= --}}

<div class="footer">

    Document généré automatiquement par Synergie School.

</div>


</body>

</html>
