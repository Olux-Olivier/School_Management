@extends('layouts.app')

@section('title', 'Élèves')

@section('breadcrumb')
    Accueil / Eleves
@endsection

@section('content')

<div class="max-w-7xl mx-auto py-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col md:flex-row
                md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-700">
                Élèves
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Gestion et consultation des élèves.
            </p>

        </div>

        <a
            href="{{ route('eleves.create') }}"
            class="inline-flex items-center justify-center
                   px-5 py-2.5 rounded-lg
                   bg-blue-600 text-white
                   hover:bg-blue-700 transition">

            <i class="fas fa-plus mr-2"></i>

            Ajouter un élève

        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- MESSAGE SUCCÈS --}}
    {{-- ===================================================== --}}

    @if(session('success'))

        <div
            class="mb-6 px-4 py-3 rounded-lg
                   bg-green-100 text-green-700
                   border border-green-200">

            {{ session('success') }}

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- RECHERCHE --}}
    {{-- ===================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border mb-6">

        <div class="p-5">

            <label
                for="searchInput"
                class="block text-sm font-medium
                       text-slate-700 mb-2">

                Rechercher un élève

            </label>

            <div class="relative">

                <div
                    class="absolute inset-y-0 left-0
                           flex items-center pl-4
                           pointer-events-none">

                    <i class="fas fa-search text-slate-400"></i>

                </div>

                <input
                    type="text"
                    id="searchInput"

                    placeholder="Matricule, nom, postnom, prénom ou téléphone..."

                    autocomplete="off"

                    class="w-full border rounded-lg
                           pl-11 pr-10 py-3

                           focus:ring-2
                           focus:ring-blue-500
                           focus:outline-none">

                {{-- Bouton effacer --}}

                <button
                    type="button"
                    id="clearSearch"

                    class="hidden absolute inset-y-0 right-0
                           px-4 text-slate-400
                           hover:text-slate-700">

                    <i class="fas fa-times"></i>

                </button>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- TABLEAU --}}
    {{-- ===================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b">

                    <tr>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600">

                            Matricule

                        </th>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600">

                            Élève

                        </th>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600">

                            Sexe

                        </th>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600">

                            Téléphone

                        </th>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600">

                            Statut

                        </th>

                        <th class="px-6 py-4 text-sm font-semibold
                                   text-slate-600 text-right">

                            Actions

                        </th>

                    </tr>

                </thead>


                <tbody
                    id="elevesTableBody"
                    class="divide-y">

                    @forelse($eleves as $eleve)

                        <tr
                            class="eleve-row hover:bg-slate-50 transition"

                            data-search="

                                {{ strtolower(
                                    $eleve->matricule . ' ' .
                                    $eleve->nom . ' ' .
                                    $eleve->postnom . ' ' .
                                    $eleve->prenom . ' ' .
                                    $eleve->telephone
                                ) }}

                            ">


                            {{-- =============================== --}}
                            {{-- MATRICULE --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <span
                                    class="font-semibold
                                           text-blue-600">

                                    {{ $eleve->matricule }}

                                </span>

                            </td>


                            {{-- =============================== --}}
                            {{-- NOM COMPLET --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">


                                    {{-- PHOTO --}}

                                    @if($eleve->photo)

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $eleve->photo
                                            ) }}"

                                            alt="Photo"

                                            class="w-10 h-10
                                                   rounded-full
                                                   object-cover
                                                   border">

                                    @else

                                        <div
                                            class="w-10 h-10
                                                   rounded-full
                                                   bg-slate-100
                                                   flex items-center
                                                   justify-center">

                                            <i
                                                class="fas fa-user
                                                       text-slate-400">
                                            </i>

                                        </div>

                                    @endif


                                    <div>

                                        <p
                                            class="font-semibold
                                                   text-slate-700">

                                            {{ $eleve->nom_complet }}

                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- =============================== --}}
                            {{-- SEXE --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <span class="text-slate-600">

                                    {{ $eleve->sexe_libelle }}

                                </span>

                            </td>


                            {{-- =============================== --}}
                            {{-- TELEPHONE --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <span class="text-slate-600">

                                    {{ $eleve->telephone ?: '-' }}

                                </span>

                            </td>


                            {{-- =============================== --}}
                            {{-- STATUT --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <span
                                    class="px-3 py-1
                                           rounded-full text-sm

                                           {{ $eleve->actif
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700' }}">

                                    {{ $eleve->statut_libelle }}

                                </span>

                            </td>


                            {{-- =============================== --}}
                            {{-- ACTIONS --}}
                            {{-- =============================== --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex justify-end
                                           items-center gap-2">


                                    {{-- Consultation --}}

                                    <a
                                        href="{{ route(
                                            'eleves.show',
                                            $eleve
                                        ) }}"

                                        title="Consulter"

                                        class="w-9 h-9
                                               flex items-center
                                               justify-center
                                               rounded-lg

                                               bg-slate-100
                                               text-slate-600

                                               hover:bg-slate-200
                                               transition">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- Modification --}}

                                    <a
                                        href="{{ route(
                                            'eleves.edit',
                                            $eleve
                                        ) }}"

                                        title="Modifier"

                                        class="w-9 h-9
                                               flex items-center
                                               justify-center
                                               rounded-lg

                                               bg-blue-100
                                               text-blue-600

                                               hover:bg-blue-200
                                               transition">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12
                                       text-center
                                       text-slate-500">

                                <div class="flex flex-col
                                            items-center">

                                    <i
                                        class="fas fa-users
                                               text-4xl
                                               text-slate-300
                                               mb-3">
                                    </i>

                                    <p>
                                        Aucun élève enregistré.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse


                    {{-- ================================================= --}}
                    {{-- AUCUN RÉSULTAT DE RECHERCHE --}}
                    {{-- ================================================= --}}

                    <tr id="noSearchResult" class="hidden">

                        <td
                            colspan="6"
                            class="px-6 py-12
                                   text-center
                                   text-slate-500">

                            <i
                                class="fas fa-search
                                       text-3xl
                                       text-slate-300
                                       mb-3">
                            </i>

                            <p>
                                Aucun élève ne correspond
                                à votre recherche.
                            </p>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECHERCHE DYNAMIQUE --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const searchInput =
        document.getElementById('searchInput');


    const clearSearch =
        document.getElementById('clearSearch');


    const rows =
        document.querySelectorAll('.eleve-row');


    const noSearchResult =
        document.getElementById('noSearchResult');


    searchInput.addEventListener('input', function () {

        const search =
            this.value
                .toLowerCase()
                .trim();


        let visibleRows = 0;


        /*
        |--------------------------------------------------------------------------
        | Afficher / cacher le bouton X
        |--------------------------------------------------------------------------
        */

        if (search.length > 0) {

            clearSearch.classList.remove('hidden');

        } else {

            clearSearch.classList.add('hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | Parcourir les élèves
        |--------------------------------------------------------------------------
        */

        rows.forEach(function (row) {

            const content =
                row.dataset.search
                    .toLowerCase();


            if (
                search === '' ||
                content.includes(search)
            ) {

                row.classList.remove('hidden');

                visibleRows++;

            } else {

                row.classList.add('hidden');

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Aucun résultat
        |--------------------------------------------------------------------------
        */

        if (
            search !== '' &&
            visibleRows === 0
        ) {

            noSearchResult.classList.remove('hidden');

        } else {

            noSearchResult.classList.add('hidden');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Effacer la recherche
    |--------------------------------------------------------------------------
    */

    clearSearch.addEventListener('click', function () {

        searchInput.value = '';

        searchInput.dispatchEvent(
            new Event('input')
        );

        searchInput.focus();

    });

});

</script>

@endsection
