@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- ====================================================== --}}
    {{-- TITRE --}}
    {{-- ====================================================== --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-800">
            Solvabilité des élèves
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Vérifiez la situation des paiements par année scolaire,
            section, classe et frais.
        </p>

    </div>


    {{-- ====================================================== --}}
    {{-- FORMULAIRE --}}
    {{-- ====================================================== --}}

    <div class="rounded-xl bg-white border border-slate-200 p-6 shadow-sm ">

        <form
            method="GET"
            action="{{ route('solvabilites.index') }}"
            id="formSolvabilite"
        >

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">


                {{-- ================================================== --}}
                {{-- ANNÉE SCOLAIRE --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="annee_scolaire_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Année scolaire
                    </label>

                    <select
                        name="annee_scolaire_id"
                        id="annee_scolaire_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner une année
                        </option>

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"
                                {{ request('annee_scolaire_id') == $annee->id ? 'selected' : '' }}
                            >
                                {{ $annee->libelle }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- SECTION --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="section"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Section
                    </label>

                    <select
                        name="section"
                        id="section"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner une section
                        </option>

                        @foreach($sections as $section)

                            <option
                                value="{{ $section }}"
                                {{ request('section') === $section ? 'selected' : '' }}
                            >
                                {{ $section }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- OPTION --}}
                {{-- ================================================== --}}

                <div id="optionContainer" class="hidden">

                    <label
                        for="option"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Option
                    </label>

                    <select
                        name="option"
                        id="option"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner une option
                        </option>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- CLASSE --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="classe_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Classe
                    </label>

                    <select
                        name="classe_id"
                        id="classe_id"
                        disabled
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm disabled:bg-slate-100 disabled:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner une classe
                        </option>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- FRAIS --}}
                {{-- ================================================== --}}

                <div>

                    <label
                        for="frais_id"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Frais
                    </label>

                    <select
                        name="frais_id"
                        id="frais_id"
                        disabled
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm disabled:bg-slate-100 disabled:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner un frais
                        </option>

                    </select>

                </div>


                {{-- ================================================== --}}
                {{-- MOIS --}}
                {{-- ================================================== --}}

                <div
                    id="moisContainer"
                    class="hidden"
                >

                    <label
                        for="mois"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Mois
                    </label>

                    <select
                        name="mois"
                        id="mois"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Sélectionner un mois
                        </option>

                        @foreach([
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
                            'Décembre'
                        ] as $mois)

                            <option
                                value="{{ $mois }}"
                                {{ request('mois') === $mois ? 'selected' : '' }}
                            >
                                {{ $mois }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- BOUTON --}}
            {{-- ====================================================== --}}

            <div class="mt-6 flex justify-end gap-3">

                <a
                    href="{{ route('solvabilites.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >

                    <i class="fas fa-rotate-left"></i>

                    Réinitialiser

                </a>


                <button
                    type="submit"
                    name="rechercher"
                    value="1"
                    id="btnRechercher"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
                >

                    <i class="fas fa-search"></i>

                    Rechercher

                </button>

            </div>

        </form>

    </div>



    {{-- ====================================================== --}}
    {{-- RÉSULTATS --}}
    {{-- ====================================================== --}}

    @if(request()->has('rechercher'))

        <div class="mt-8">

            <div class="mb-6 flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Résultats
                    </h2>

                    <p class="text-sm text-slate-500">
                        Situation de solvabilité des élèves.
                    </p>

                </div>


                <a
                    href="{{ route('solvabilites.pdf', request()->query()) }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-900"
                >

                    <i class="fas fa-file-pdf"></i>

                    Télécharger PDF

                </a>

            </div>


            {{-- ================================================== --}}
            {{-- EN ORDRE --}}
            {{-- ================================================== --}}

            @include(
                'solvabilites.partials.table',
                [
                    'liste' => $enOrdre,
                    'titre' => 'En ordre',
                    'couleur' => 'green'
                ]
            )


            {{-- ================================================== --}}
            {{-- PARTIELLEMENT PAYÉ --}}
            {{-- ================================================== --}}

            @include(
                'solvabilites.partials.table',
                [
                    'liste' => $partiellementPaye,
                    'titre' => 'Partiellement payé',
                    'couleur' => 'yellow'
                ]
            )


            {{-- ================================================== --}}
            {{-- NON EN ORDRE --}}
            {{-- ================================================== --}}

            @include(
                'solvabilites.partials.table',
                [
                    'liste' => $nonEnOrdre,
                    'titre' => 'Non en ordre',
                    'couleur' => 'red'
                ]
            )

        </div>

    @endif

</div>



{{-- ========================================================== --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const anneeSelect = document.getElementById('annee_scolaire_id');

    const sectionSelect = document.getElementById('section');

    const optionContainer = document.getElementById('optionContainer');

    const optionSelect = document.getElementById('option');

    const classeSelect = document.getElementById('classe_id');

    const fraisSelect = document.getElementById('frais_id');

    const moisContainer = document.getElementById('moisContainer');

    const moisSelect = document.getElementById('mois');


    const classeSelectionnee = "{{ request('classe_id') }}";

    const optionSelectionnee = "{{ request('option') }}";

    const fraisSelectionne = "{{ request('frais_id') }}";


    /*
    |--------------------------------------------------------------------------
    | Fonctions utilitaires
    |--------------------------------------------------------------------------
    */

    function resetClasses() {

        classeSelect.innerHTML = `
            <option value="">
                Sélectionner une classe
            </option>
        `;

        classeSelect.disabled = true;
    }


    function resetFrais() {

        fraisSelect.innerHTML = `
            <option value="">
                Sélectionner un frais
            </option>
        `;

        fraisSelect.disabled = true;

        moisContainer.classList.add('hidden');

        moisSelect.value = '';
    }


    /*
    |--------------------------------------------------------------------------
    | Charger les options
    |--------------------------------------------------------------------------
    */

    async function chargerOptions() {

        optionSelect.innerHTML = `
            <option value="">
                Chargement...
            </option>
        `;

        optionSelect.disabled = true;


        try {

            const url =
                "{{ route('solvabilites.options') }}" +
                "?section=" +
                encodeURIComponent(sectionSelect.value);


            const response = await fetch(url);

            const options = await response.json();


            optionSelect.innerHTML = `
                <option value="">
                    Sélectionner une option
                </option>
            `;


            options.forEach(function (option) {

                const selected =
                    option === optionSelectionnee
                        ? 'selected'
                        : '';


                optionSelect.innerHTML += `
                    <option value="${option}" ${selected}>
                        ${option}
                    </option>
                `;
            });


            optionSelect.disabled = false;


        } catch (error) {

            console.error(error);

            optionSelect.innerHTML = `
                <option value="">
                    Erreur de chargement
                </option>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Charger les classes
    |--------------------------------------------------------------------------
    */

    async function chargerClasses() {

        resetClasses();

        resetFrais();


        if (!sectionSelect.value) {
            return;
        }


        /*
        | Humanités sans option
        */

        if (
            sectionSelect.value === 'Humanités' &&
            !optionSelect.value
        ) {

            return;
        }


        classeSelect.innerHTML = `
            <option value="">
                Chargement...
            </option>
        `;


        try {

            let url =
                "{{ route('solvabilites.classes') }}" +
                "?section=" +
                encodeURIComponent(sectionSelect.value);


            /*
            | Ajouter l'option uniquement pour Humanités
            */

            if (
                sectionSelect.value === 'Humanités' &&
                optionSelect.value
            ) {

                url +=
                    "&option=" +
                    encodeURIComponent(optionSelect.value);
            }


            const response = await fetch(url);

            const classes = await response.json();


            classeSelect.innerHTML = `
                <option value="">
                    Sélectionner une classe
                </option>
            `;


            classes.forEach(function (classe) {

                let libelle = classe.nom;


                /*
                | Pour Humanités, on peut afficher l'option
                */

                if (
                    classe.section === 'Humanités' &&
                    classe.option
                ) {

                    libelle +=
                        ' — ' +
                        classe.option;
                }


                const selected =
                    String(classe.id) === String(classeSelectionnee)
                        ? 'selected'
                        : '';


                classeSelect.innerHTML += `
                    <option value="${classe.id}" ${selected}>
                        ${libelle}
                    </option>
                `;
            });


            classeSelect.disabled = false;


            /*
            | Si une classe était déjà sélectionnée
            */

            if (classeSelectionnee) {

                await chargerFrais();
            }


        } catch (error) {

            console.error(error);

            classeSelect.innerHTML = `
                <option value="">
                    Erreur de chargement
                </option>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Charger les frais
    |--------------------------------------------------------------------------
    */

    async function chargerFrais() {

        resetFrais();


        if (
            !anneeSelect.value ||
            !classeSelect.value
        ) {

            return;
        }


        fraisSelect.innerHTML = `
            <option value="">
                Chargement...
            </option>
        `;


        try {

            const url =
                "{{ route('solvabilites.frais') }}" +
                "?annee_scolaire_id=" +
                encodeURIComponent(anneeSelect.value) +
                "&classe_id=" +
                encodeURIComponent(classeSelect.value);


            const response = await fetch(url);

            const frais = await response.json();


            fraisSelect.innerHTML = `
                <option value="">
                    Sélectionner un frais
                </option>
            `;


            frais.forEach(function (fraisItem) {

                const selected =
                    String(fraisItem.id) === String(fraisSelectionne)
                        ? 'selected'
                        : '';


                fraisSelect.innerHTML += `
                    <option
                        value="${fraisItem.id}"
                        data-intitule="${fraisItem.intitule}"
                        ${selected}
                    >
                        ${fraisItem.intitule}
                        — ${Number(fraisItem.montant).toLocaleString('fr-FR', {
                            minimumFractionDigits: 2
                        })}
                    </option>
                `;
            });


            fraisSelect.disabled = false;


            /*
            | Restaurer le mois si nécessaire
            */

            if (fraisSelectionne) {

                afficherMois();

            }


        } catch (error) {

            console.error(error);

            fraisSelect.innerHTML = `
                <option value="">
                    Erreur de chargement
                </option>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Afficher / masquer le mois
    |--------------------------------------------------------------------------
    */

    function afficherMois() {

        const option =
            fraisSelect.options[
                fraisSelect.selectedIndex
            ];


        if (!option) {
            return;
        }


        const intitule =
            (option.dataset.intitule || '')
                .trim()
                .toLowerCase();


        if (intitule === 'minerval') {

            moisContainer.classList.remove('hidden');

        } else {

            moisContainer.classList.add('hidden');

            moisSelect.value = '';
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Changement année
    |--------------------------------------------------------------------------
    */

    anneeSelect.addEventListener('change', function () {

        /*
        | L'année ne change pas la liste des classes.
        | Elle change uniquement les frais disponibles.
        */

        resetFrais();

        if (classeSelect.value) {
            chargerFrais();
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Changement section
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener('change', async function () {

        optionSelect.innerHTML = `
            <option value="">
                Sélectionner une option
            </option>
        `;

        optionSelect.disabled = true;

        resetClasses();

        resetFrais();


        if (!sectionSelect.value) {

            optionContainer.classList.add('hidden');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Humanités
        |--------------------------------------------------------------------------
        */

        if (sectionSelect.value === 'Humanités') {

            optionContainer.classList.remove('hidden');

            await chargerOptions();

            /*
            | On attend le choix de l'option.
            */

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Autres sections
        |--------------------------------------------------------------------------
        */

        optionContainer.classList.add('hidden');

        await chargerClasses();

    });


    /*
    |--------------------------------------------------------------------------
    | Changement option
    |--------------------------------------------------------------------------
    */

    optionSelect.addEventListener('change', async function () {

        resetClasses();

        resetFrais();


        if (!optionSelect.value) {

            return;
        }


        await chargerClasses();

    });


    /*
    |--------------------------------------------------------------------------
    | Changement classe
    |--------------------------------------------------------------------------
    */

    classeSelect.addEventListener('change', async function () {

        resetFrais();


        if (!classeSelect.value) {

            return;
        }


        await chargerFrais();

    });


    /*
    |--------------------------------------------------------------------------
    | Changement frais
    |--------------------------------------------------------------------------
    */

    fraisSelect.addEventListener('change', function () {

        afficherMois();

    });


    /*
    |--------------------------------------------------------------------------
    | Restaurer les sélections après recherche
    |--------------------------------------------------------------------------
    */

    async function initialiser() {

        if (!sectionSelect.value) {
            return;
        }


        /*
        | Humanités
        */

        if (sectionSelect.value === 'Humanités') {

            optionContainer.classList.remove('hidden');

            await chargerOptions();


            /*
            | L'option doit exister avant de charger les classes.
            */

            if (optionSelect.value) {

                await chargerClasses();
            }

        }


        /*
        | Autres sections
        */

        else {

            optionContainer.classList.add('hidden');

            await chargerClasses();
        }

    }


    initialiser();

});

</script>

@endsection
