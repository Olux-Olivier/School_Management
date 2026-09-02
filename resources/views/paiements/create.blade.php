@extends('layouts.app')

@section('title', 'Nouveau paiement')

@section('breadcrumb')
    Paiements / Nouveau paiement
@endsection
@section('content')

<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

    @include('paiements.partials.navigation')

    {{-- ================================================================
         EN-TÊTE
    ================================================================= --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="fas fa-money-bill-wave"></i>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">Nouveau paiement</h1>
                <p class="mt-1 text-sm text-slate-500">Enregistrer un paiement pour l'élève.</p>
            </div>

        </div>

        <a
            href="{{ route('paiements.show', [
                'eleve' => $eleve->id,
                'annee_scolaire_id' => $anneeScolaireId,
            ]) }}"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 sm:w-auto"
        >
            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
            <span>Retour à l’historique</span>
        </a>

    </div>


    {{-- ================================================================
         MESSAGES
    ================================================================= --}}

    @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3">

            <ul class="text-sm text-red-700 space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        • {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================================
         INFORMATIONS ÉLÈVE
    ================================================================= --}}

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Matricule --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Matricule
                </p>

                <p class="font-semibold text-slate-700 mt-1">
                    {{ $eleve->matricule }}
                </p>

            </div>


            {{-- Élève --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Élève
                </p>

                <p class="font-semibold text-slate-700 mt-1">

                    {{ $eleve->nom }}
                    {{ $eleve->postnom }}
                    {{ $eleve->prenom }}

                </p>

            </div>


            {{-- Classe --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Classe
                </p>

                <p class="font-semibold text-slate-700 mt-1">

                    {{ $inscription->classe->nom }}

                    @if($inscription->classe->option)

                        — {{ $inscription->classe->option }}

                    @endif

                </p>

            </div>


            {{-- Section --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Section
                </p>

                <p class="font-semibold text-indigo-600 mt-1">
                    {{ ucfirst($section) }}
                </p>

            </div>

        </div>

    </div>


    {{-- ================================================================
         FORMULAIRE
    ================================================================= --}}

    <form
        method="POST"
        action="{{ route('paiements.store') }}"
        id="paiementForm"
    >

        @csrf


        {{-- Élève --}}

        <input
            type="hidden"
            name="eleve_id"
            value="{{ $eleve->id }}"
        >


        {{-- Année scolaire --}}

        <input
            type="hidden"
            name="annee_scolaire_id"
            value="{{ $anneeScolaireId }}"
            id="annee_scolaire_id"
        >


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- ====================================================
                     ANNÉE SCOLAIRE
                ===================================================== --}}

                <div>

                    <label
                        for="annee_scolaire_select"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Année scolaire
                    </label>

                    <select
                        id="annee_scolaire_select"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"
                                {{ (string) $anneeScolaireId === (string) $annee->id ? 'selected' : '' }}
                            >

                                {{ $annee->libelle
                                    ?? $annee->nom
                                    ?? $annee->date_debut . ' - ' . $annee->date_fin
                                }}

                            </option>

                        @endforeach

                    </select>

                    <p class="text-xs text-slate-400 mt-1">
                        Sélectionnez l'année scolaire concernée par le paiement.
                    </p>

                </div>


                {{-- ====================================================
                     MOTIF / FRAIS
                ===================================================== --}}

                <div>

                    <label
                        for="frais_id"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Motif
                    </label>

                    <select
                        name="frais_id"
                        id="frais_id"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >

                        <option value="">
                            Sélectionner le motif
                        </option>

                        @foreach($frais as $fraisItem)

                            <option
                                value="{{ $fraisItem->id }}"
                                data-montant="{{ $fraisItem->montant }}"
                                data-motif="{{ $fraisItem->intitule }}"
                                {{ old('frais_id') == $fraisItem->id ? 'selected' : '' }}
                            >

                                {{ $fraisItem->intitule }}

                                —

                                {{ number_format(
                                    $fraisItem->montant,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FC

                            </option>

                        @endforeach

                    </select>

                    <p class="text-xs text-slate-400 mt-1">
                        Les motifs proposés correspondent à la section de l'élève.
                    </p>

                </div>


                {{-- ====================================================
                     MOIS
                ===================================================== --}}

                <div
                    id="moisContainer"
                    class="hidden"
                >

                    <label
                        for="mois"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Mois
                    </label>

                    <select
                        name="mois"
                        id="mois"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >

                        <option value="">
                            Sélectionner le mois
                        </option>

                        <option
                            value="Septembre"
                            {{ old('mois') === 'Septembre' ? 'selected' : '' }}
                        >
                            Septembre
                        </option>

                        <option
                            value="Octobre"
                            {{ old('mois') === 'Octobre' ? 'selected' : '' }}
                        >
                            Octobre
                        </option>

                        <option
                            value="Novembre"
                            {{ old('mois') === 'Novembre' ? 'selected' : '' }}
                        >
                            Novembre
                        </option>

                        <option
                            value="Décembre"
                            {{ old('mois') === 'Décembre' ? 'selected' : '' }}
                        >
                            Décembre
                        </option>

                        <option
                            value="Janvier"
                            {{ old('mois') === 'Janvier' ? 'selected' : '' }}
                        >
                            Janvier
                        </option>

                        <option
                            value="Février"
                            {{ old('mois') === 'Février' ? 'selected' : '' }}
                        >
                            Février
                        </option>

                        <option
                            value="Mars"
                            {{ old('mois') === 'Mars' ? 'selected' : '' }}
                        >
                            Mars
                        </option>

                        <option
                            value="Avril"
                            {{ old('mois') === 'Avril' ? 'selected' : '' }}
                        >
                            Avril
                        </option>

                        <option
                            value="Mai"
                            {{ old('mois') === 'Mai' ? 'selected' : '' }}
                        >
                            Mai
                        </option>

                        <option
                            value="Juin"
                            {{ old('mois') === 'Juin' ? 'selected' : '' }}
                        >
                            Juin
                        </option>

                    </select>

                    <p class="text-xs text-slate-400 mt-1">
                        Le mois est obligatoire uniquement pour le minerval.
                    </p>

                </div>


                {{-- ====================================================
                     MONTANT DU FRAIS
                ===================================================== --}}

                <div>

                    <label
                        for="montant_du"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Montant à payer
                    </label>

                    <div class="relative">

                        <input
                            type="text"
                            id="montant_du"
                            readonly
                            value="{{ old('montant_du') }}"
                            placeholder="0"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 pr-14 font-semibold text-slate-700"
                        >

                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"
                        >
                            FC
                        </span>

                    </div>

                    <p class="text-xs text-slate-400 mt-1">
                        Ce montant provient du frais sélectionné et ne peut pas être modifié ici.
                    </p>

                </div>


                {{-- ====================================================
                     MONTANT PAYÉ
                ===================================================== --}}

                <div>

                    <label
                        for="montant_paye"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Montant payé
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            name="montant_paye"
                            id="montant_paye"
                            min="1"
                            step="0.01"
                            required
                            value="{{ old('montant_paye') }}"
                            placeholder="Saisir le montant payé"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 pr-14 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        >

                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"
                        >
                            FC
                        </span>

                    </div>

                </div>


                {{-- ====================================================
                     RESTANT
                ===================================================== --}}

                <div>

                    <label
                        for="restant"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Restant
                    </label>

                    <div class="relative">

                        <input
                            type="text"
                            id="restant"
                            readonly
                            value="0"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 pr-14 font-semibold text-amber-600"
                        >

                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"
                        >
                            FC
                        </span>

                    </div>

                    <p class="text-xs text-slate-400 mt-1">
                        Le restant est calculé automatiquement.
                    </p>

                </div>


                {{-- ====================================================
                     DATE PAIEMENT
                ===================================================== --}}

                <div>

                    <label
                        for="date_paiement"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Date du paiement
                    </label>

                    <input
                        type="date"
                        name="date_paiement"
                        id="date_paiement"
                        value="{{ old(
                            'date_paiement',
                            now()->format('Y-m-d')
                        ) }}"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >

                </div>


                {{-- ====================================================
                     MODE DE PAIEMENT
                ===================================================== --}}

                <div>

                    <label
                        for="mode_paiement"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Mode de paiement
                    </label>

                    <select
                        name="mode_paiement"
                        id="mode_paiement"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    >

                        <option value="">
                            Sélectionner
                        </option>

                        <option
                            value="especes"
                            {{ old('mode_paiement') === 'especes' ? 'selected' : '' }}
                        >
                            Espèces
                        </option>

                        <option
                            value="mobile_money"
                            {{ old('mode_paiement') === 'mobile_money' ? 'selected' : '' }}
                        >
                            Mobile Money
                        </option>

                        <option
                            value="virement"
                            {{ old('mode_paiement') === 'virement' ? 'selected' : '' }}
                        >
                            Virement
                        </option>

                        <option
                            value="cheque"
                            {{ old('mode_paiement') === 'cheque' ? 'selected' : '' }}
                        >
                            Chèque
                        </option>

                    </select>

                </div>

            </div>


            {{-- ========================================================
                 RÉFÉRENCE
            ========================================================= --}}

            <div class="mt-5">

                <label
                    class="block text-sm font-medium text-slate-700 mb-1"
                >
                    Référence
                </label>

                <div class="rounded-lg bg-slate-50 border border-slate-200 px-4 py-3">

                    <p class="text-sm text-slate-500">
                        La référence sera générée automatiquement lors de
                        l'enregistrement du paiement.
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Exemple : 00001-HUM, 00002-HUM, 00001-SEC, etc.
                    </p>

                </div>

            </div>


            {{-- ========================================================
                 RÉSUMÉ
            ========================================================= --}}

            <div
                id="resumePaiement"
                class="hidden mt-6 rounded-xl border border-indigo-100 bg-indigo-50 p-5"
            >

                <h2 class="text-sm font-semibold text-indigo-800 mb-3">
                    Résumé du paiement
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <div>

                        <p class="text-xs text-indigo-500">
                            Motif
                        </p>

                        <p
                            id="resumeMotif"
                            class="font-semibold text-indigo-900 mt-1"
                        >
                            —
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-indigo-500">
                            Montant dû
                        </p>

                        <p
                            id="resumeMontantDu"
                            class="font-semibold text-indigo-900 mt-1"
                        >
                            0 FC
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-indigo-500">
                            Montant payé
                        </p>

                        <p
                            id="resumeMontantPaye"
                            class="font-semibold text-indigo-900 mt-1"
                        >
                            0 FC
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 BOUTONS
            ========================================================= --}}

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('paiements.show', [
                        'eleve' => $eleve->id,
                        'annee_scolaire_id' => $anneeScolaireId,
                    ]) }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 font-medium text-white shadow-sm transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/30"
                >
                    <i class="fas fa-check" aria-hidden="true"></i>
                    Enregistrer le paiement
                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Éléments
    |--------------------------------------------------------------------------
    */

    const fraisSelect =
        document.getElementById('frais_id');

    const moisContainer =
        document.getElementById('moisContainer');

    const moisSelect =
        document.getElementById('mois');

    const montantDu =
        document.getElementById('montant_du');

    const montantPaye =
        document.getElementById('montant_paye');

    const restant =
        document.getElementById('restant');

    const resumePaiement =
        document.getElementById('resumePaiement');

    const resumeMotif =
        document.getElementById('resumeMotif');

    const resumeMontantDu =
        document.getElementById('resumeMontantDu');

    const resumeMontantPaye =
        document.getElementById('resumeMontantPaye');

    const anneeScolaireSelect =
        document.getElementById('annee_scolaire_select');


    /*
    |--------------------------------------------------------------------------
    | Formater un montant
    |--------------------------------------------------------------------------
    */

    function formaterMontant(montant) {

        return Number(montant || 0)
            .toLocaleString('fr-FR');

    }


    /*
    |--------------------------------------------------------------------------
    | Récupérer le frais sélectionné
    |--------------------------------------------------------------------------
    */

    function getFraisSelectionne() {

        return fraisSelect.options[
            fraisSelect.selectedIndex
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Vérifier si le motif est Minerval
    |--------------------------------------------------------------------------
    */

    function estMinerval() {

        const option =
            getFraisSelectionne();

        const motif =
            option?.dataset.motif || '';

        return (
            motif === 'Minerval' ||
            motif === 'minerval'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Gestion du mois
    |--------------------------------------------------------------------------
    */

    function gererMois() {

        if (estMinerval()) {

            /*
            |--------------------------------------------------------------------------
            | Minerval
            |--------------------------------------------------------------------------
            */

            moisContainer.classList.remove('hidden');

            moisSelect.required = true;

        } else {

            /*
            |--------------------------------------------------------------------------
            | Autre frais
            |--------------------------------------------------------------------------
            */

            moisContainer.classList.add('hidden');

            moisSelect.required = false;

            moisSelect.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Calcul du montant
    |--------------------------------------------------------------------------
    */

    function calculerMontants() {

        const option =
            getFraisSelectionne();

        const montant =
            parseFloat(
                option?.dataset.montant || 0
            );

        const paye =
            parseFloat(
                montantPaye.value || 0
            );


        /*
        |--------------------------------------------------------------------------
        | Montant dû
        |--------------------------------------------------------------------------
        */

        montantDu.value =
            formaterMontant(montant);


        /*
        |--------------------------------------------------------------------------
        | Restant
        |--------------------------------------------------------------------------
        */

        const difference =
            Math.max(
                montant - paye,
                0
            );

        restant.value =
            formaterMontant(difference);


        /*
        |--------------------------------------------------------------------------
        | Résumé
        |--------------------------------------------------------------------------
        */

        if (option && option.value) {

            resumePaiement.classList.remove('hidden');

            resumeMotif.textContent =
                option.dataset.motif || '—';

            resumeMontantDu.textContent =
                formaterMontant(montant) + ' FC';

            resumeMontantPaye.textContent =
                formaterMontant(paye) + ' FC';

        } else {

            resumePaiement.classList.add('hidden');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Sélection du motif
    |--------------------------------------------------------------------------
    */

    fraisSelect.addEventListener(
        'change',
        function () {

            /*
            | Gérer le mois
            */

            gererMois();


            /*
            | Calculer les montants
            */

            calculerMontants();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Modification du montant payé
    |--------------------------------------------------------------------------
    */

    montantPaye.addEventListener(
        'input',
        function () {

            calculerMontants();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Changement d'année scolaire
    |--------------------------------------------------------------------------
    */

    anneeScolaireSelect.addEventListener(
        'change',
        function () {

            const url =
                new URL(
                    window.location.href
                );


            url.searchParams.set(
                'annee_scolaire_id',
                this.value
            );


            window.location.href =
                url.toString();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    gererMois();

    calculerMontants();

});

</script>

@endsection
