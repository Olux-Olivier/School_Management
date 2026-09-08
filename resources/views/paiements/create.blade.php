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

                <h1 class="text-2xl font-bold text-slate-800">
                    Nouveau paiement
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Enregistrer un versement pour l'élève.
                </p>

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

            <span>
                Retour à l’historique
            </span>

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

            <ul class="space-y-1 text-sm text-red-700">

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

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">


            {{-- MATRICULE --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Matricule
                </p>

                <p class="mt-1 font-semibold text-slate-700">
                    {{ $eleve->matricule }}
                </p>

            </div>


            {{-- ÉLÈVE --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Élève
                </p>

                <p class="mt-1 font-semibold text-slate-700">

                    {{ $eleve->nom }}
                    {{ $eleve->postnom }}
                    {{ $eleve->prenom }}

                </p>

            </div>


            {{-- CLASSE --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Classe
                </p>

                <p class="mt-1 font-semibold text-slate-700">

                    {{ $inscription->classe->nom }}

                    @if($inscription->classe->option)

                        — {{ $inscription->classe->option }}

                    @endif

                    @if($inscription->classe->variante)

                        — {{ $inscription->classe->variante }}

                    @endif

                </p>

            </div>


            {{-- SECTION --}}

            <div>

                <p class="text-xs uppercase tracking-wide text-slate-400">
                    Section
                </p>

                <p class="mt-1 font-semibold text-indigo-600">
                    {{ $section }}
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
        >


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                {{-- ====================================================
                     ANNÉE SCOLAIRE
                ===================================================== --}}

                <div>

                    <label
                        for="annee_scolaire_select"
                        class="mb-1 block text-sm font-medium text-slate-700"
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

                    <p class="mt-1 text-xs text-slate-400">
                        Sélectionnez l'année scolaire concernée par le paiement.
                    </p>

                </div>


                {{-- ====================================================
                     MOTIF / FRAIS
                ===================================================== --}}

                <div>

                    <label
                        for="frais_id"
                        class="mb-1 block text-sm font-medium text-slate-700"
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

                    <p class="mt-1 text-xs text-slate-400">
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
                        class="mb-1 block text-sm font-medium text-slate-700"
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

                        @foreach([
                            'Septembre',
                            'Octobre',
                            'Novembre',
                            'Décembre',
                            'Janvier',
                            'Février',
                            'Mars',
                            'Avril',
                            'Mai',
                            'Juin'
                        ] as $mois)

                            <option
                                value="{{ $mois }}"
                                {{ old('mois') === $mois ? 'selected' : '' }}
                            >
                                {{ $mois }}
                            </option>

                        @endforeach

                    </select>

                    <p class="mt-1 text-xs text-slate-400">
                        Le mois est obligatoire uniquement pour le minerval.
                    </p>

                </div>


                {{-- ====================================================
                     MONTANT DU FRAIS
                ===================================================== --}}

                <div>

                    <label
                        for="montant_du"
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Montant du frais
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

                    <p class="mt-1 text-xs text-slate-400">
                        Montant total prévu pour ce frais.
                    </p>

                </div>


                {{-- ====================================================
                     MONTANT DU VERSEMENT
                ===================================================== --}}

                <div>

                    <label
                        for="montant_paye"
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Montant du versement
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
                            placeholder="Saisir le montant versé"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 pr-14 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        >

                        <span
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"
                        >
                            FC
                        </span>

                    </div>

                    <p class="mt-1 text-xs text-slate-400">
                        Montant réellement versé lors de cette opération.
                    </p>

                </div>


                {{-- ====================================================
                     RESTANT APRÈS CE VERSEMENT
                ===================================================== --}}

                <div>

                    <label
                        for="restant"
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Restant après ce versement
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

                    <p class="mt-1 text-xs text-slate-400">
                        Calcul indicatif. Le serveur effectue le calcul définitif.
                    </p>

                </div>


                {{-- ====================================================
                     DATE DU VERSEMENT
                ===================================================== --}}

                <div>

                    <label
                        for="date_paiement"
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Date du versement
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
                        class="mb-1 block text-sm font-medium text-slate-700"
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


                {{-- ====================================================
                     RÉFÉRENCE
                ===================================================== --}}

                <div class="md:col-span-2">

                    <label
                        class="mb-1 block text-sm font-medium text-slate-700"
                    >
                        Référence
                    </label>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">

                        <p class="text-sm text-slate-500">

                            La référence sera générée automatiquement pour
                            chaque versement lors de l'enregistrement.

                        </p>

                        <p class="mt-1 text-xs text-slate-400">

                            Chaque versement aura sa propre référence afin
                            d'assurer une traçabilité complète.

                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 RÉSUMÉ
            ========================================================= --}}

            <div
                id="resumePaiement"
                class="mt-6 hidden rounded-xl border border-indigo-100 bg-indigo-50 p-5"
            >

                <h2 class="mb-3 text-sm font-semibold text-indigo-800">
                    Résumé du versement
                </h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">


                    <div>

                        <p class="text-xs text-indigo-500">
                            Motif
                        </p>

                        <p
                            id="resumeMotif"
                            class="mt-1 font-semibold text-indigo-900"
                        >
                            —
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-indigo-500">
                            Montant du frais
                        </p>

                        <p
                            id="resumeMontantDu"
                            class="mt-1 font-semibold text-indigo-900"
                        >
                            0 FC
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-indigo-500">
                            Ce versement
                        </p>

                        <p
                            id="resumeMontantPaye"
                            class="mt-1 font-semibold text-indigo-900"
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

                    Enregistrer le versement

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
    | Vérifier Minerval
    |--------------------------------------------------------------------------
    */

    function estMinerval() {

        const option =
            getFraisSelectionne();

        const motif =
            option?.dataset.motif || '';

        return motif.toLowerCase() === 'minerval';

    }


    /*
    |--------------------------------------------------------------------------
    | Gestion du mois
    |--------------------------------------------------------------------------
    */

    function gererMois() {

        if (estMinerval()) {

            moisContainer.classList.remove('hidden');

            moisSelect.required = true;

        } else {

            moisContainer.classList.add('hidden');

            moisSelect.required = false;

            moisSelect.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Calcul du restant indicatif
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
        | Montant total du frais
        |--------------------------------------------------------------------------
        */

        montantDu.value =
            formaterMontant(montant);


        /*
        |--------------------------------------------------------------------------
        | Restant indicatif
        |--------------------------------------------------------------------------
        |
        | Attention :
        | Ici on calcule seulement :
        |
        | montant du frais - montant de CE versement
        |
        | Si un paiement précédent existe, le serveur calculera
        | le véritable restant.
        |
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

            gererMois();

            calculerMontants();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Modification du versement
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
