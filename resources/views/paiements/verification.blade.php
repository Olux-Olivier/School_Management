@extends('layouts.app')

@section('title', 'Vérification d\'un versement')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- ============================================================
        EN-TÊTE
    ============================================================= --}}
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fas fa-receipt text-blue-600 text-lg"></i>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Vérification d'un versement
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Vérifiez un versement à l'aide de son numéro de référence.
                </p>
            </div>
        </div>
    </div>


    {{-- ============================================================
        FORMULAIRE DE RECHERCHE
    ============================================================= --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">

        <form
            method="GET"
            action="{{ route('paiements.verification') }}"
        >

            <div class="flex flex-col md:flex-row gap-3">

                <div class="flex-1">

                    <label
                        for="reference"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Numéro de référence
                    </label>

                    <input
                        type="text"
                        name="reference"
                        id="reference"
                        value="{{ old('reference', $reference ?? '') }}"
                        placeholder="Ex. ESP-12345-67890-12345"
                        autocomplete="off"
                        required
                        class="w-full rounded-lg border border-slate-300
                               px-4 py-2.5 text-slate-800
                               uppercase
                               focus:border-blue-500
                               focus:ring-2 focus:ring-blue-200
                               focus:outline-none"
                    >

                </div>


                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full md:w-auto inline-flex items-center
                               justify-center gap-2 px-5 py-2.5
                               rounded-lg bg-blue-600 text-white
                               font-medium hover:bg-blue-700
                               transition"
                    >
                        <i class="fas fa-search"></i>
                        Vérifier
                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- ============================================================
        MESSAGE D'ERREUR
    ============================================================= --}}
    @if(session('error'))

        <div class="mb-6 rounded-xl border border-red-200
                    bg-red-50 px-5 py-4">

            <div class="flex items-start gap-3">

                <div class="mt-0.5">
                    <i class="fas fa-circle-exclamation text-red-600"></i>
                </div>

                <div>
                    <p class="font-semibold text-red-800">
                        Vérification impossible
                    </p>

                    <p class="text-sm text-red-700 mt-1">
                        {{ session('error') }}
                    </p>
                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        RÉSULTAT
    ============================================================= --}}
    @if(isset($historique) && $historique)

        @php
            $eleve = $paiement->eleve;
            $frais = $paiement->frais;
            $annee = $paiement->anneeScolaire;
            $classe = $inscription?->classe;

            $montantDu = (float) $paiement->montant_du;
            $montantAvant = (float) ($montantAvant ?? 0);
            $montantVersement = (float) ($montantVersement ?? $historique->montant);
            $montantApres = (float) ($montantApres ?? ($montantAvant + $montantVersement));
            $soldeApres = (float) ($soldeApres ?? max(0, $montantDu - $montantApres));

            $pourcentage = $montantDu > 0
                ? min(100, ($montantApres / $montantDu) * 100)
                : 0;

            $section = $classe
                ? match ((int) $classe->niveau) {
                    0 => 'Maternelle',
                    1 => 'Primaire',
                    2 => 'Secondaire',
                    3 => 'Humanités',
                    default => 'Autre',
                }
                : null;
        @endphp


        {{-- ========================================================
            STATUT DE VÉRIFICATION
        ========================================================= --}}
        <div class="rounded-xl border border-green-200
                    bg-green-50 p-5 mb-6">

            <div class="flex flex-col md:flex-row md:items-center
                        md:justify-between gap-4">

                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-full bg-green-100
                                flex items-center justify-center">

                        <i class="fas fa-check text-green-600 text-xl"></i>

                    </div>

                    <div>

                        <h2 class="font-bold text-green-800 text-lg">
                            Versement vérifié
                        </h2>

                        <p class="text-sm text-green-700 mt-1">
                            Cette référence correspond à un versement
                            enregistré dans le système.
                        </p>

                    </div>

                </div>


                <div class="text-left md:text-right">

                    <p class="text-xs text-green-700 uppercase">
                        Référence
                    </p>

                    <p class="font-bold text-green-800 break-all">
                        {{ $historique->reference }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
            INFORMATIONS DU VERSEMENT
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-money-bill-transfer text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Informations du versement
                    </h2>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Référence --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Numéro de référence
                    </p>

                    <p class="mt-1 font-bold text-blue-700 text-lg break-all">
                        {{ $historique->reference }}
                    </p>

                </div>


                {{-- Date --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Date du versement
                    </p>

                    <p class="mt-1 font-medium text-slate-800">
                        {{ $historique->date_paiement?->format('d/m/Y') ?? '-' }}
                    </p>

                </div>


                {{-- Montant --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Montant versé
                    </p>

                    <p class="mt-1 font-bold text-slate-800 text-xl">
                        {{ number_format($montantVersement, 2, ',', ' ') }}
                        FC
                    </p>

                </div>


                {{-- Mode --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Mode de paiement
                    </p>

                    <p class="mt-1 font-medium text-slate-800">
                        {{ $historique->mode_paiement ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
            INFORMATIONS DE L'ÉLÈVE
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-user-graduate text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Informations de l'élève
                    </h2>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Matricule --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Matricule
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $eleve?->matricule ?? '-' }}
                    </p>

                </div>


                {{-- Nom complet --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Nom complet
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $eleve?->nom_complet ?? '-' }}
                    </p>

                </div>


                {{-- Sexe --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Sexe
                    </p>

                    <p class="mt-1 text-slate-700">
                        {{ $eleve?->sexe_libelle ?? '-' }}
                    </p>

                </div>


                {{-- Téléphone --}}
                @if(!empty($eleve?->telephone))

                    <div>

                        <p class="text-xs text-slate-500 uppercase">
                            Téléphone
                        </p>

                        <p class="mt-1 text-slate-700">
                            {{ $eleve->telephone }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================
            INFORMATIONS SCOLAIRES
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-school text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Informations scolaires
                    </h2>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Année scolaire --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Année scolaire
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $annee?->libelle ?? '-' }}
                    </p>

                </div>


                {{-- Section --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Section
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $section ?? '-' }}
                    </p>

                </div>


                {{-- Classe --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Classe
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $classe?->nom ?? '-' }}
                    </p>

                </div>


                {{-- Option --}}
                @if(!empty($classe?->option))

                    <div>

                        <p class="text-xs text-slate-500 uppercase">
                            Option
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $classe->option }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================
            FRAIS CONCERNÉ
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-file-invoice-dollar text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Détails du frais
                    </h2>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Intitulé --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Frais concerné
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $frais?->intitule ?? $paiement->motif ?? '-' }}
                    </p>

                </div>


                {{-- Mois --}}
                @if(strtolower(trim($paiement->motif ?? '')) === 'minerval')

                    <div>

                        <p class="text-xs text-slate-500 uppercase">
                            Mois
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $paiement->mois ?? '-' }}
                        </p>

                    </div>

                @endif


                {{-- Montant total --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Montant total du frais
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ number_format($montantDu, 2, ',', ' ') }}
                        FC
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
            SITUATION FINANCIÈRE
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-chart-line text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Situation financière du versement
                    </h2>

                </div>

            </div>


            <div class="p-5">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Avant --}}
                    <div class="rounded-xl border border-slate-200
                                bg-slate-50 p-4">

                        <p class="text-xs text-slate-500 uppercase">
                            Payé avant ce versement
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-800">
                            {{ number_format($montantAvant, 2, ',', ' ') }}
                            FC
                        </p>

                    </div>


                    {{-- Versement --}}
                    <div class="rounded-xl border border-blue-200
                                bg-blue-50 p-4">

                        <p class="text-xs text-blue-700 uppercase font-medium">
                            Ce versement
                        </p>

                        <p class="mt-2 text-xl font-bold text-blue-800">
                            {{ number_format($montantVersement, 2, ',', ' ') }}
                            FC
                        </p>

                    </div>


                    {{-- Après --}}
                    <div class="rounded-xl border border-slate-200
                                bg-slate-50 p-4">

                        <p class="text-xs text-slate-500 uppercase">
                            Payé après ce versement
                        </p>

                        <p class="mt-2 text-xl font-bold text-slate-800">
                            {{ number_format($montantApres, 2, ',', ' ') }}
                            FC
                        </p>

                    </div>

                </div>


                {{-- Solde --}}
                <div class="mt-4 rounded-xl border
                    {{ $soldeApres > 0
                        ? 'border-orange-200 bg-orange-50'
                        : 'border-green-200 bg-green-50' }}
                    p-5">

                    <div class="flex flex-col md:flex-row
                                md:items-center md:justify-between gap-3">

                        <div>

                            <p class="text-xs uppercase font-medium
                                {{ $soldeApres > 0
                                    ? 'text-orange-700'
                                    : 'text-green-700' }}">
                                Solde restant après ce versement
                            </p>

                            <p class="mt-1 text-2xl font-bold
                                {{ $soldeApres > 0
                                    ? 'text-orange-700'
                                    : 'text-green-700' }}">

                                {{ number_format($soldeApres, 2, ',', ' ') }}
                                FC

                            </p>

                        </div>


                        @if($soldeApres <= 0)

                            <div class="inline-flex items-center gap-2
                                        rounded-full bg-green-100
                                        px-4 py-2 text-sm font-medium
                                        text-green-700">

                                <i class="fas fa-check-circle"></i>

                                Frais entièrement payé

                            </div>

                        @else

                            <div class="inline-flex items-center gap-2
                                        rounded-full bg-orange-100
                                        px-4 py-2 text-sm font-medium
                                        text-orange-700">

                                <i class="fas fa-clock"></i>

                                Solde restant

                            </div>

                        @endif

                    </div>

                </div>


                {{-- Progression --}}
                <div class="mt-6">

                    <div class="flex justify-between items-center
                                text-xs text-slate-500 mb-2">

                        <span>
                            Progression du paiement
                        </span>

                        <span class="font-semibold text-slate-700">
                            {{ number_format($pourcentage, 0) }} %
                        </span>

                    </div>


                    <div class="w-full h-3 bg-slate-200
                                rounded-full overflow-hidden">

                        <div
                            class="h-3 bg-blue-600 rounded-full"
                            style="width: {{ $pourcentage }}%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            TRAÇABILITÉ
        ========================================================= --}}
        <div class="bg-white rounded-xl shadow-sm border
                    border-slate-200 overflow-hidden mb-6">

            <div class="px-5 py-4 border-b border-slate-200
                        bg-slate-50">

                <div class="flex items-center gap-2">

                    <i class="fas fa-user-shield text-blue-600"></i>

                    <h2 class="font-semibold text-slate-800">
                        Traçabilité
                    </h2>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Créé par --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Versement enregistré par
                    </p>

                    <p class="mt-1 font-medium text-slate-800">
                        {{ $historique->createdBy?->nom_complet ?? '-' }}
                    </p>

                </div>


                {{-- Date création --}}
                <div>

                    <p class="text-xs text-slate-500 uppercase">
                        Date d'enregistrement
                    </p>

                    <p class="mt-1 font-medium text-slate-800">
                        {{ $historique->created_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>

                </div>


                {{-- Modification --}}
                @if($historique->updatedBy)

                    <div>

                        <p class="text-xs text-slate-500 uppercase">
                            Dernière modification par
                        </p>

                        <p class="mt-1 font-medium text-slate-800">
                            {{ $historique->updatedBy->nom_complet }}
                        </p>

                    </div>

                @endif


                {{-- Date modification --}}
                @if($historique->updated_at)

                    <div>

                        <p class="text-xs text-slate-500 uppercase">
                            Dernière modification
                        </p>

                        <p class="mt-1 font-medium text-slate-800">
                            {{ $historique->updated_at->format('d/m/Y H:i') }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================
            ACTIONS
        ========================================================= --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3">

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center justify-center gap-2
                       px-5 py-2.5 rounded-lg
                       bg-slate-700 text-white
                       font-medium hover:bg-slate-800
                       transition"
            >
                <i class="fas fa-print"></i>
                Imprimer
            </button>

        </div>

    @endif

</div>


{{-- ================================================================
    IMPRESSION
================================================================ --}}
<style>

@media print {

    body {
        background: white !important;
    }

    nav,
    aside,
    header,
    footer,
    .no-print {
        display: none !important;
    }

    .shadow-sm {
        box-shadow: none !important;
    }

    button {
        display: none !important;
    }

}

</style>

@endsection

