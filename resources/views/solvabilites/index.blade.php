@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ========================================================== --}}
    {{-- TITRE --}}
    {{-- ========================================================== --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Consultation de la solvabilité
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Consultez la situation des élèves pour un frais donné.
        </p>

    </div>


    {{-- ========================================================== --}}
    {{-- MESSAGE --}}
    {{-- ========================================================== --}}

    @if(session('error'))

        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- ========================================================== --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================== --}}

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">

        <form
            method="GET"
            action="{{ route('solvabilites.index') }}"
            id="solvabiliteForm"
        >

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">


                {{-- ANNÉE --}}
                <div>

                    <label
                        for="annee_scolaire_id"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Année scolaire
                    </label>

                    <select
                        id="annee_scolaire_id"
                        name="annee_scolaire_id"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Sélectionner
                        </option>

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"
                                {{ (string) $anneeScolaireId === (string) $annee->id ? 'selected' : '' }}
                            >
                                {{ $annee->libelle }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- SECTION --}}
                <div>

                    <label
                        for="section"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Section
                    </label>

                    <select
                        id="section"
                        name="section"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Sélectionner
                        </option>

                        @foreach($sections as $sectionItem)

                            <option
                                value="{{ $sectionItem }}"
                                {{ $section === $sectionItem ? 'selected' : '' }}
                            >
                                {{ $sectionItem }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CLASSE --}}
                <div>

                    <label
                        for="classe_id"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Classe
                    </label>

                    <select
                        id="classe_id"
                        name="classe_id"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Sélectionner une section
                        </option>

                        @foreach($classes as $classeItem)

                            <option
                                value="{{ $classeItem->id }}"
                                {{ (string) $classeId === (string) $classeItem->id ? 'selected' : '' }}
                            >
                                {{ $classeItem->nom_complet ?? $classeItem->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- OPTION HUMANITÉS --}}
                <div
                    id="optionContainer"
                    class="{{ $section && strtolower(str_replace(['é','è','ê','ë'], 'e', $section)) === 'humanites' ? '' : 'hidden' }}"
                >

                    <label
                        for="option"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Option
                    </label>

                    <select
                        id="option"
                        name="option"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Toutes les options
                        </option>

                        @foreach($options as $optionItem)

                            <option
                                value="{{ $optionItem }}"
                                {{ $option === $optionItem ? 'selected' : '' }}
                            >
                                {{ $optionItem }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- FRAIS --}}
                <div>

                    <label
                        for="frais_id"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Frais
                    </label>

                    <select
                        id="frais_id"
                        name="frais_id"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Sélectionner une classe
                        </option>

                        @foreach($frais as $fraisItem)

                            <option
                                value="{{ $fraisItem->id }}"
                                data-intitule="{{ strtolower(trim($fraisItem->intitule)) }}"
                                {{ (string) $fraisId === (string) $fraisItem->id ? 'selected' : '' }}
                            >
                                {{ $fraisItem->intitule }}
                                —
                                {{ number_format((float) $fraisItem->montant, 2, ',', ' ') }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- MOIS --}}
                <div
                    id="moisContainer"
                    class="{{ $fraisSelectionne && strtolower(trim($fraisSelectionne->intitule)) === 'minerval' ? '' : 'hidden' }}"
                >

                    <label
                        for="mois"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Mois
                    </label>

                    <select
                        id="mois"
                        name="mois"
                        class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Sélectionner un mois
                        </option>

                        @php
                            $moisListe = [
                                'Janvier',
                                'Février',
                                'Mars',
                                'Avril',
                                'Mai',
                                'Juin',
                                'Juillet',
                                'Août',
                                'Septembre',
                                'Octobre',
                                'Novembre',
                                'Décembre',
                            ];
                        @endphp

                        @foreach($moisListe as $moisItem)

                            <option
                                value="{{ $moisItem }}"
                                {{ $mois === $moisItem ? 'selected' : '' }}
                            >
                                {{ $moisItem }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="flex justify-end gap-3 mt-6">

                <a
                    href="{{ route('solvabilites.index') }}"
                    class="inline-flex items-center px-5 py-3 rounded-xl
                           bg-slate-100 text-slate-700 font-medium
                           hover:bg-slate-200 transition"
                >
                    Réinitialiser
                </a>

                <button
                    type="submit"
                    name="rechercher"
                    value="1"
                    class="inline-flex items-center gap-2 px-6 py-3
                           rounded-xl bg-blue-600 text-white font-medium
                           hover:bg-blue-700 transition"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z"
                        />
                    </svg>

                    Rechercher

                </button>

            </div>

        </form>

    </div>


    {{-- ========================================================== --}}
    {{-- RÉSULTATS --}}
    {{-- ========================================================== --}}

    @if($rechercheEffectuee)

        {{-- ====================================================== --}}
        {{-- INFORMATIONS RECHERCHE --}}
        {{-- ====================================================== --}}

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>

                    <p class="text-sm text-slate-500">
                        Consultation
                    </p>

                    <h2 class="text-lg font-semibold text-slate-800 mt-1">

                        {{ $fraisSelectionne->intitule }}

                        @if($mois)
                            — {{ $mois }}
                        @endif

                    </h2>

                    <p class="text-sm text-slate-500 mt-1">

                        {{ $fraisSelectionne->classe->nom_complet
                            ?? $fraisSelectionne->classe->nom
                            ?? '' }}

                        •
                        {{ $fraisSelectionne->anneeScolaire->libelle
                            ?? '' }}

                    </p>

                </div>

                {{-- PDF --}}
                <a
                    href="{{ route('solvabilites.pdf', request()->query()) }}"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2
                           px-5 py-3 rounded-xl
                           bg-red-600 text-white font-medium
                           hover:bg-red-700 transition"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 2h9l5 5v15H6V2Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 2v6h6"
                        />

                    </svg>

                    Télécharger PDF

                </a>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- COMPTEURS --}}
        {{-- ====================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            <div class="bg-white border border-green-200 rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-slate-500">
                    En ordre
                </p>

                <p class="text-3xl font-bold text-green-600 mt-1">
                    {{ $enOrdre->count() }}
                </p>

            </div>


            <div class="bg-white border border-yellow-200 rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-slate-500">
                    Partiellement payé
                </p>

                <p class="text-3xl font-bold text-yellow-600 mt-1">
                    {{ $partiellementPaye->count() }}
                </p>

            </div>


            <div class="bg-white border border-red-200 rounded-2xl p-5 shadow-sm">

                <p class="text-sm text-slate-500">
                    Non en ordre
                </p>

                <p class="text-3xl font-bold text-red-600 mt-1">
                    {{ $nonEnOrdre->count() }}
                </p>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- TABLE EN ORDRE --}}
        {{-- ====================================================== --}}

        @include('solvabilites.partials.table', [
            'liste' => $enOrdre,
            'titre' => 'En ordre',
            'couleur' => 'green'
        ])


        {{-- ====================================================== --}}
        {{-- TABLE PARTIEL --}}
        {{-- ====================================================== --}}

        @include('solvabilites.partials.table', [
            'liste' => $partiellementPaye,
            'titre' => 'Partiellement payé',
            'couleur' => 'yellow'
        ])


        {{-- ====================================================== --}}
        {{-- TABLE NON EN ORDRE --}}
        {{-- ====================================================== --}}

        @include('solvabilites.partials.table', [
            'liste' => $nonEnOrdre,
            'titre' => 'Non en ordre',
            'couleur' => 'red'
        ])

    @endif

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT RECHERCHE DYNAMIQUE --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const annee = document.getElementById('annee_scolaire_id');
    const section = document.getElementById('section');
    const classe = document.getElementById('classe_id');
    const option = document.getElementById('option');
    const optionContainer = document.getElementById('optionContainer');
    const frais = document.getElementById('frais_id');
    const mois = document.getElementById('mois');
    const moisContainer = document.getElementById('moisContainer');


    /*
    |--------------------------------------------------------------------------
    | UTILITAIRE : CHARGEMENT
    |--------------------------------------------------------------------------
    */

    function afficherChargement(select, texte = 'Chargement...') {

        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = texte;

        select.appendChild(option);

        select.disabled = true;
    }


    function activer(select) {
        select.disabled = false;
    }


    function vider(select, texte) {

        select.innerHTML = '';

        const option = document.createElement('option');

        option.value = '';
        option.textContent = texte;

        select.appendChild(option);

    }


    /*
    |--------------------------------------------------------------------------
    | ANNÉE
    |--------------------------------------------------------------------------
    */

    annee.addEventListener('change', function () {

        /*
        | L'année change les frais.
        | On remet donc toute la chaîne à zéro.
        */

        section.value = '';

        vider(
            classe,
            'Sélectionner une section'
        );

        vider(
            frais,
            'Sélectionner une classe'
        );

        optionContainer.classList.add('hidden');

        moisContainer.classList.add('hidden');

        option.value = '';

        mois.value = '';

        classe.disabled = true;
        frais.disabled = true;

    });


    /*
    |--------------------------------------------------------------------------
    | SECTION
    |--------------------------------------------------------------------------
    */

    section.addEventListener('change', async function () {

        const valeur = section.value;

        vider(
            classe,
            'Sélectionner une classe'
        );

        vider(
            frais,
            'Sélectionner une classe'
        );

        frais.disabled = true;

        option.value = '';

        optionContainer.classList.add('hidden');

        moisContainer.classList.add('hidden');

        mois.value = '';

        if (!valeur) {
            classe.disabled = true;
            return;
        }

        afficherChargement(
            classe,
            'Chargement des classes...'
        );

        /*
        | OPTIONS HUMANITÉS
        */

        const texteNormalise = valeur
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

        if (texteNormalise === 'humanites') {

            try {

                const response = await fetch(
                    "{{ route('solvabilites.options') }}"
                    + "?section="
                    + encodeURIComponent(valeur)
                );

                const options = await response.json();

                option.innerHTML =
                    '<option value="">Toutes les options</option>';

                options.forEach(function (item) {

                    const opt =
                        document.createElement('option');

                    opt.value = item;
                    opt.textContent = item;

                    option.appendChild(opt);

                });

                optionContainer.classList.remove('hidden');

            } catch (error) {

                console.error(error);

            }
        }


        /*
        | CLASSES
        */

        try {

            const response = await fetch(
                "{{ route('solvabilites.classes') }}"
                + "?section="
                + encodeURIComponent(valeur)
            );

            const classes = await response.json();

            classe.innerHTML =
                '<option value="">Sélectionner une classe</option>';

            classes.forEach(function (item) {

                const opt =
                    document.createElement('option');

                opt.value = item.id;

                /*
                | Pour Humanités, afficher l'option.
                */

                if (
                    item.option &&
                    texteNormalise === 'humanites'
                ) {

                    opt.textContent =
                        item.nom
                        + ' — '
                        + item.option;

                } else {

                    opt.textContent = item.nom;

                }

                classe.appendChild(opt);

            });

            activer(classe);

        } catch (error) {

            console.error(error);

            vider(
                classe,
                'Erreur de chargement'
            );

            activer(classe);
        }

    });


    /*
    |--------------------------------------------------------------------------
    | CLASSE
    |--------------------------------------------------------------------------
    */

    classe.addEventListener('change', async function () {

        const classeId = classe.value;

        vider(
            frais,
            'Sélectionner un frais'
        );

        moisContainer.classList.add('hidden');
        mois.value = '';

        if (!classeId) {

            frais.disabled = true;

            return;
        }

        afficherChargement(
            frais,
            'Chargement des frais...'
        );

        try {

            const response = await fetch(
                "{{ route('solvabilites.frais') }}"
                + "?annee_scolaire_id="
                + encodeURIComponent(annee.value)
                + "&classe_id="
                + encodeURIComponent(classeId)
            );

            const listeFrais =
                await response.json();

            frais.innerHTML =
                '<option value="">Sélectionner un frais</option>';

            listeFrais.forEach(function (item) {

                const opt =
                    document.createElement('option');

                opt.value = item.id;

                opt.dataset.intitule =
                    item.intitule
                        .toLowerCase()
                        .trim();

                opt.textContent =
                    item.intitule
                    + ' — '
                    + Number(item.montant)
                        .toLocaleString('fr-FR', {
                            minimumFractionDigits: 2
                        });

                frais.appendChild(opt);

            });

            activer(frais);

        } catch (error) {

            console.error(error);

            vider(
                frais,
                'Erreur de chargement'
            );

            activer(frais);
        }

    });


    /*
    |--------------------------------------------------------------------------
    | FRAIS
    |--------------------------------------------------------------------------
    */

    frais.addEventListener('change', function () {

        const selected =
            frais.options[frais.selectedIndex];

        mois.value = '';

        if (!selected || !selected.value) {

            moisContainer.classList.add('hidden');

            return;
        }

        const intitule =
            selected.dataset.intitule || '';

        if (intitule === 'minerval') {

            moisContainer.classList.remove('hidden');

        } else {

            moisContainer.classList.add('hidden');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | AU CHARGEMENT
    |--------------------------------------------------------------------------
    */

    if (!annee.value) {

        classe.disabled = true;
        frais.disabled = true;

    }

});

</script>

@endsection
