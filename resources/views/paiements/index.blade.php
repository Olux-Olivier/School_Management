@extends('layouts.app')

@section('title', 'Paiements')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

    {{-- ================================================================
         FILTRES
    ================================================================= --}}

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="fas fa-filter"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">Filtres de recherche</h2>
                <p class="text-xs text-slate-500">Affinez la liste des élèves inscrits.</p>
            </div>
        </div>

        <form
            method="GET"
            action="{{ route('paiements.index') }}"
            id="paiementSearchForm"
        >

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- ----------------------------------------------------
                     ANNÉE SCOLAIRE
                ----------------------------------------------------- --}}

                <div>

                    <label
                        for="annee_scolaire_id"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Année scolaire
                    </label>

                    <select
                        name="annee_scolaire_id"
                        id="annee_scolaire_id"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                    >

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"
                                {{ (string) $anneeScolaireId === (string) $annee->id ? 'selected' : '' }}
                            >
                                {{ $annee->libelle ?? $annee->nom ?? $annee->date_debut . ' - ' . $annee->date_fin }}

                                @if($annee->actif)
                                    — Active
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ----------------------------------------------------
                     SECTION
                ----------------------------------------------------- --}}

                <div>

                    <label
                        for="section"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Section
                    </label>

                    <select
                        name="section"
                        id="section"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                    >

                        <option value="">
                            Toutes les sections
                        </option>

                        <option
                            value="maternelle"
                            {{ request('section') === 'maternelle' ? 'selected' : '' }}
                        >
                            Maternelle
                        </option>

                        <option
                            value="primaire"
                            {{ request('section') === 'primaire' ? 'selected' : '' }}
                        >
                            Primaire
                        </option>

                        <option
                            value="secondaire"
                            {{ request('section') === 'secondaire' ? 'selected' : '' }}
                        >
                            Secondaire
                        </option>

                        <option
                            value="humanites"
                            {{ request('section') === 'humanites' ? 'selected' : '' }}
                        >
                            Humanités
                        </option>

                    </select>

                </div>


                {{-- ----------------------------------------------------
                     CLASSE
                ----------------------------------------------------- --}}

                <div>

                    <label
                        for="classe_id"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Classe
                    </label>

                    <select
                        name="classe_id"
                        id="classe_id"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                    >

                        <option value="">
                            Toutes les classes
                        </option>

                        @foreach($classes as $classe)

                            <option
                                value="{{ $classe->id }}"
                                data-niveau="{{ $classe->niveau }}"
                                {{ (string) request('classe_id') === (string) $classe->id ? 'selected' : '' }}
                            >
                                {{ $classe->nom }}

                                @if($classe->option)
                                    — {{ $classe->option }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ----------------------------------------------------
                     RECHERCHE
                ----------------------------------------------------- --}}

                <div>

                    <label
                        for="search"
                        class="block text-sm font-medium text-slate-700 mb-1"
                    >
                        Rechercher un élève
                    </label>

                    <div class="relative">

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Matricule, nom, postnom ou prénom..."
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 py-3 pl-4 pr-10 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                        >

                        @if(request('search'))

                            <button
                                type="button"
                                id="clearSearch"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                title="Effacer"
                            >
                                ×
                            </button>

                        @endif

                    </div>

                </div>

            </div>


            {{-- --------------------------------------------------------
                 BOUTONS
            --------------------------------------------------------- --}}

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a
                    href="{{ route('paiements.dashboard') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Dashboard des paiements
                </a>

                <a
                    href="{{ route('paiements.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Réinitialiser
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    Rechercher
                </button>

            </div>

        </form>

    </div>


    {{-- ================================================================
         INFORMATIONS ANNÉE
    ================================================================= --}}

    @if($anneeScolaire)

        <div class="mb-4">

            <div class="inline-flex items-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-4 py-2.5 text-sm text-blue-700">

                <span class="font-medium">
                    Année consultée :
                </span>

                <span>
                    {{ $anneeScolaire->libelle ?? $anneeScolaire->nom ?? $anneeScolaire->date_debut . ' - ' . $anneeScolaire->date_fin }}
                </span>

            </div>

        </div>

    @endif


    {{-- ================================================================
         TABLEAU DES ÉLÈVES
    ================================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            <div>

            <h2 class="font-semibold text-slate-800">
                Élèves inscrits
            </h2>

            <p class="text-xs text-slate-500 mt-1">
                Les résultats correspondent à l'année scolaire sélectionnée.
            </p>

            </div>

            <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ $inscriptions->total() }} élève(s)
            </span>

        </div>


        @if($inscriptions->count())

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Matricule
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Élève
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Classe
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Section
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Date d'inscription
                            </th>

                            <th class="text-right px-5 py-3 font-semibold text-slate-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($inscriptions as $inscription)

                            <tr class="transition hover:bg-blue-50/40">

                                {{-- MATRICULE --}}

                                <td class="px-5 py-4 font-semibold text-blue-600">

                                    {{ $inscription->eleve->matricule }}

                                </td>


                                {{-- ÉLÈVE --}}

                                <td class="px-5 py-4">

                                    <div class="font-medium text-slate-700">

                                        {{ $inscription->eleve->nom }}
                                        {{ $inscription->eleve->postnom }}
                                        {{ $inscription->eleve->prenom }}

                                    </div>

                                </td>


                                {{-- CLASSE --}}

                                <td class="px-5 py-4 text-slate-600">

                                    {{ $inscription->classe->nom }}

                                    @if($inscription->classe->option)

                                        <span class="text-slate-400">
                                            — {{ $inscription->classe->option }}
                                        </span>

                                    @endif

                                </td>


                                {{-- SECTION --}}

                                <td class="px-5 py-4">

                                    @php

                                        $section = match($inscription->classe->niveau) {
                                            0 => 'Maternelle',
                                            1 => 'Primaire',
                                            2 => 'Secondaire',
                                            3 => 'Humanités',
                                            default => '—',
                                        };

                                    @endphp

                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">

                                        {{ $section }}

                                    </span>

                                </td>


                                {{-- DATE --}}

                                <td class="px-5 py-4 text-slate-500">

                                    {{ $inscription->date_inscription?->format('d/m/Y') }}

                                </td>


                                {{-- ACTION --}}

                                <td class="px-5 py-4 text-right">

                                    <a
                                        href="{{ route('paiements.show', ['eleve' => $inscription->eleve_id,'annee_scolaire_id' => $anneeScolaireId,]) }}"
                                        class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Consulter les paiements
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}

            <div class="px-5 py-4 border-t border-slate-200">

                {{ $inscriptions->links() }}

            </div>

        @else

            <div class="px-5 py-14 text-center">

                <div class="text-slate-400 text-4xl mb-3">
                    €
                </div>

                <h3 class="font-semibold text-slate-600">
                    Aucun élève trouvé
                </h3>

                <p class="text-sm text-slate-400 mt-1">
                    Modifiez les critères de recherche puis réessayez.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- ====================================================================
     JAVASCRIPT
===================================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('paiementSearchForm');

    const anneeSelect = document.getElementById('annee_scolaire_id');

    const sectionSelect = document.getElementById('section');

    const classeSelect = document.getElementById('classe_id');

    const searchInput = document.getElementById('search');

    const clearSearch = document.getElementById('clearSearch');


    /*
    |--------------------------------------------------------------------------
    | Année scolaire
    |--------------------------------------------------------------------------
    |
    | Lorsqu'on change d'année, on recharge la page.
    |
    */

    anneeSelect.addEventListener('change', function () {

        form.submit();

    });


    /*
    |--------------------------------------------------------------------------
    | Section → Classe
    |--------------------------------------------------------------------------
    */

    function filtrerClasses() {

        const section = sectionSelect.value;


        Array.from(classeSelect.options).forEach(function (option, index) {

            if (index === 0) {

                option.hidden = false;

                return;
            }


            const niveau = option.dataset.niveau;


            let afficher = true;


            if (section === 'maternelle') {

                afficher = niveau === '0';

            }
            else if (section === 'primaire') {

                afficher = niveau === '1';

            }
            else if (section === 'secondaire') {

                afficher = niveau === '2';

            }
            else if (section === 'humanites') {

                afficher = niveau === '3';

            }


            option.hidden = !afficher;

        });


        /*
        | Si la classe sélectionnée ne correspond plus à la section,
        | on la réinitialise.
        */

        const optionSelectionnee =
            classeSelect.options[classeSelect.selectedIndex];


        if (
            optionSelectionnee &&
            optionSelectionnee.hidden
        ) {

            classeSelect.value = '';

        }

    }


    filtrerClasses();


    sectionSelect.addEventListener('change', function () {

        classeSelect.value = '';

        filtrerClasses();

    });


    /*
    |--------------------------------------------------------------------------
    | Recherche
    |--------------------------------------------------------------------------
    |
    | Même principe que les autres pages :
    | la recherche est déclenchée après 2 secondes.
    |
    */

    let searchTimer = null;


    searchInput.addEventListener('input', function () {

        clearTimeout(searchTimer);


        searchTimer = setTimeout(function () {

            form.submit();

        }, 2000);

    });


    /*
    |--------------------------------------------------------------------------
    | Effacer la recherche
    |--------------------------------------------------------------------------
    */

    if (clearSearch) {

        clearSearch.addEventListener('click', function () {

            searchInput.value = '';

            form.submit();

        });

    }

});

</script>

@endsection
