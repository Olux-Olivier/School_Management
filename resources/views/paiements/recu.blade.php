<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Reçu de paiement - {{ $historique->reference }}</title>

    <style>
        @page {
            margin: 25px 35px 30px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #ffffff;
        }

        .page {
            width: 100%;
        }

        /* ---------------------------------------------------------
           EN-TÊTE
        --------------------------------------------------------- */

        .header {
            width: 100%;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .school-cell {
            vertical-align: middle;
            padding-left: 8px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .school-info {
            font-size: 9px;
            line-height: 1.6;
            color: #4b5563;
        }

        .document-cell {
            width: 175px;
            text-align: right;
            vertical-align: middle;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 7px;
        }

        .reference {
            display: inline-block;
            border: 1px solid #374151;
            padding: 6px 9px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* ---------------------------------------------------------
           TITRE
        --------------------------------------------------------- */

        .main-title {
            text-align: center;
            margin: 12px 0 20px 0;
        }

        .main-title h1 {
            margin: 0;
            font-size: 21px;
            letter-spacing: 1.5px;
            color: #111827;
        }

        .main-title .subtitle {
            margin-top: 5px;
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ---------------------------------------------------------
           BLOCS
        --------------------------------------------------------- */

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            background: #f3f4f6;
            border-left: 4px solid #1f2937;
            padding: 7px 9px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111827;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .label {
            width: 27%;
            color: #6b7280;
            font-size: 9px;
        }

        .value {
            width: 23%;
            font-weight: bold;
            color: #111827;
        }

        /* ---------------------------------------------------------
           MONTANT
        --------------------------------------------------------- */

        .amount-box {
            border: 1px solid #d1d5db;
            margin-top: 10px;
            padding: 15px;
            text-align: center;
        }

        .amount-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
        }

        .currency {
            font-size: 12px;
            font-weight: bold;
            margin-left: 4px;
        }

        /* ---------------------------------------------------------
           SITUATION FINANCIÈRE
        --------------------------------------------------------- */

        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .financial-table td {
            border: 1px solid #d1d5db;
            padding: 9px;
        }

        .financial-label {
            color: #4b5563;
            font-size: 9px;
        }

        .financial-value {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }

        .remaining {
            font-size: 13px;
            font-weight: bold;
        }

        /* ---------------------------------------------------------
           MODE DE PAIEMENT
        --------------------------------------------------------- */

        .payment-info {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-info td {
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* ---------------------------------------------------------
           SIGNATURES
        --------------------------------------------------------- */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 15px;
        }

        .signature-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4b5563;
        }

        .signature-space {
            height: 55px;
        }

        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 80%;
            margin: 0 auto;
        }

        .signature-name {
            margin-top: 5px;
            font-size: 9px;
            color: #6b7280;
        }

        /* ---------------------------------------------------------
           NOTE
        --------------------------------------------------------- */

        .note {
            margin-top: 25px;
            padding: 9px 11px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            font-size: 8px;
            line-height: 1.6;
            color: #6b7280;
            text-align: center;
        }

        /* ---------------------------------------------------------
           PIED DE PAGE
        --------------------------------------------------------- */

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }

        .footer-line {
            border-top: 1px solid #e5e7eb;
            margin-bottom: 5px;
        }

        /* ---------------------------------------------------------
           PETITES CLASSES
        --------------------------------------------------------- */

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="header">

        <table class="header-table">
            <tr>

                <td class="logo-cell">
                    @if(file_exists(public_path('images/logo.png')))
                        <img
                            src="{{ public_path('images/logo.png') }}"
                            class="logo"
                            alt="Logo"
                        >
                    @endif
                </td>

                <td class="school-cell">

                    <div class="school-name">
                        SynergieSchool
                    </div>

                    <div class="school-info">
                        Établissement scolaire<br>
                        Adresse de l'établissement<br>
                        Téléphone : ____________________
                    </div>

                </td>

                <td class="document-cell">

                    <div class="document-title">
                        REÇU
                    </div>

                    <div class="reference">
                        {{ $historique->reference }}
                    </div>

                </td>

            </tr>
        </table>

    </div>


    {{-- =========================================================
         TITRE
    ========================================================== --}}

    <div class="main-title">

        <h1>REÇU DE PAIEMENT</h1>

        <div class="subtitle">
            Justificatif officiel de versement scolaire
        </div>

    </div>


    {{-- =========================================================
         INFORMATIONS ÉLÈVE
    ========================================================== --}}

    <div class="section">

        <div class="section-title">
            Informations de l'élève
        </div>

        <table class="info-table">

            <tr>
                <td class="label">
                    Matricule
                </td>

                <td class="value">
                    {{ $eleve->matricule ?? '—' }}
                </td>

                <td class="label">
                    Année scolaire
                </td>

                <td class="value">
                    {{ $anneeScolaire->nom ?? $anneeScolaire->libelle ?? '—' }}
                </td>
            </tr>

            <tr>
                <td class="label">
                    Nom complet
                </td>

                <td class="value">
                    {{ $eleve->nom_complet ?? trim(($eleve->nom ?? '') . ' ' . ($eleve->postnom ?? '') . ' ' . ($eleve->prenom ?? '')) }}
                </td>

                <td class="label">
                    Classe
                </td>

                <td class="value">

                    @if($inscription && $inscription->classe)

                        {{ $inscription->classe->nom }}

                        @if(!empty($inscription->classe->option))
                            {{ ' ' . $inscription->classe->option }}
                        @endif

                    @else
                        —
                    @endif

                </td>
            </tr>

            <tr>
                <td class="label">
                    Sexe
                </td>

                <td class="value">
                    {{ $eleve->sexe_libelle ?? $eleve->sexe ?? '—' }}
                </td>

                <td class="label">
                    Section
                </td>

                <td class="value">

                    @if($inscription && $inscription->classe)

                        @switch((int) $inscription->classe->niveau)

                            @case(0)
                                Maternelle
                                @break

                            @case(1)
                                Primaire
                                @break

                            @case(2)
                                Secondaire
                                @break

                            @case(3)
                                Humanités
                                @break

                            @default
                                —
                        @endswitch

                    @else
                        —
                    @endif

                </td>
            </tr>

        </table>

    </div>


    {{-- =========================================================
         DÉTAILS DU PAIEMENT
    ========================================================== --}}

    <div class="section">

        <div class="section-title">
            Détails du paiement
        </div>

        <table class="payment-info">

            <tr>

                <td class="label">
                    Nature du frais
                </td>

                <td class="value">
                    {{ $paiement->motif ?? $frais->intitule ?? '—' }}
                </td>

                <td class="label">
                    Date du versement
                </td>

                <td class="value">
                    {{ $historique->date_paiement
                        ? \Carbon\Carbon::parse($historique->date_paiement)->format('d/m/Y')
                        : '—'
                    }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Mois
                </td>

                <td class="value">

                    @if(
                        isset($paiement->mois)
                        && strtolower(trim($paiement->mois)) !== 'pas disponible'
                    )
                        {{ $paiement->mois }}
                    @else
                        —
                    @endif

                </td>

                <td class="label">
                    Mode de paiement
                </td>

                <td class="value">
                    {{ $historique->mode_paiement ?? '—' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Agent
                </td>

                <td class="value">
                    @if($historique->createdBy)
                        {{ $historique->createdBy->nom_complet
                            ?? trim(
                                ($historique->createdBy->nom ?? '') . ' ' .
                                ($historique->createdBy->postnom ?? '') . ' ' .
                                ($historique->createdBy->prenom ?? '')
                            )
                        }}
                    @else
                        —
                    @endif
                </td>

                <td class="label">
                    Référence
                </td>

                <td class="value">
                    {{ $historique->reference }}
                </td>

            </tr>

        </table>


        {{-- Montant du versement --}}

        <div class="amount-box">

            <div class="amount-label">
                Montant du présent versement
            </div>

            <div class="amount">

                {{ number_format(
                    (float) $historique->montant,
                    0,
                    ',',
                    ' '
                ) }}

                <span class="currency">
                    FC
                </span>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SITUATION FINANCIÈRE
    ========================================================== --}}

    <div class="section">

        <div class="section-title">
            Situation financière après ce versement
        </div>

        <table class="financial-table">

            <tr>

                <td class="financial-label">
                    Montant dû
                </td>

                <td class="financial-value">
                    {{ number_format(
                        $montantDu,
                        0,
                        ',',
                        ' '
                    ) }}
                    FC
                </td>

                <td class="financial-label">
                    Total payé
                </td>

                <td class="financial-value">
                    {{ number_format(
                        $montantCumule,
                        0,
                        ',',
                        ' '
                    ) }}
                    FC
                </td>

            </tr>

            <tr>

                <td class="financial-label">
                    Solde restant
                </td>

                <td
                    colspan="3"
                    class="financial-value remaining"
                >
                    {{ number_format(
                        $restant,
                        0,
                        ',',
                        ' '
                    ) }}
                    FC
                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         SIGNATURES
    ========================================================== --}}

    <table class="signature-table">

        <tr>

            <td>

                <div class="signature-title">
                    Signature de l'agent
                </div>

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                @if($historique->createdBy)

                    <div class="signature-name">
                        {{ $historique->createdBy->nom_complet
                            ?? trim(
                                ($historique->createdBy->nom ?? '') . ' ' .
                                ($historique->createdBy->postnom ?? '') . ' ' .
                                ($historique->createdBy->prenom ?? '')
                            )
                        }}
                    </div>

                @endif

            </td>

            <td>

                <div class="signature-title">
                    Cachet et signature de l'établissement
                </div>

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                <div class="signature-name">
                    Administration
                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         NOTE ADMINISTRATIVE
    ========================================================== --}}

    <div class="note">

        Ce reçu constitue une preuve officielle du versement enregistré.
        Il doit être conservé par le parent ou le responsable de l'élève.
        Toute modification ou annulation du paiement doit être effectuée
        conformément aux procédures administratives de l'établissement.

    </div>

</div>


{{-- =============================================================
     PIED DE PAGE
============================================================= --}}

<div class="footer">

    <div class="footer-line"></div>

    SynergieSchool — Reçu de paiement —
    Référence : {{ $historique->reference }}

</div>

</body>
</html>
