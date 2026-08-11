@extends('layouts.app')

@section('title', 'Élèves')

@section('breadcrumb')
    Accueil/tous les élèves de l'etablissement
@endsection

@section('content')

<div class="max-w-7xl mx-auto py-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col md:flex-row
                md:items-center md:justify-between
                gap-4 mb-6">

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

                {{-- Icône recherche --}}

                <div
                    class="absolute inset-y-0 left-0
                           flex items-center pl-4
                           pointer-events-none">

                    <i class="fas fa-search text-slate-400"></i>

                </div>


                {{-- Champ recherche --}}

                <input
                    type="text"

                    id="searchInput"

                    value="{{ $search ?? '' }}"

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

                    class="{{ !empty($search)
                        ? ''
                        : 'hidden' }}

                        absolute inset-y-0 right-0
                        px-4 text-slate-400
                        hover:text-slate-700">

                    <i class="fas fa-times"></i>

                </button>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMATIONS DE RÉSULTATS --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col md:flex-row
                md:items-center md:justify-between
                gap-2 mb-4">

        <div class="text-sm text-slate-500">

            @if($eleves->total() > 0)

                Affichage de

                <span class="font-semibold text-slate-700">
                    {{ $eleves->firstItem() }}
                </span>

                à

                <span class="font-semibold text-slate-700">
                    {{ $eleves->lastItem() }}
                </span>

                sur

                <span class="font-semibold text-slate-700">
                    {{ $eleves->total() }}
                </span>

                élève(s)

            @else

                Aucun élève trouvé.

            @endif

        </div>


        @if(!empty($search))

            <div class="text-sm text-slate-500">

                Recherche :

                <span
                    class="font-semibold text-blue-600">

                    "{{ $search }}"

                </span>

            </div>

        @endif

    </div>


    {{-- ===================================================== --}}
    {{-- TABLEAU --}}
    {{-- ===================================================== --}}

    <div class="bg-white rounded-xl shadow-sm
                border overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                {{-- ================================================= --}}
                {{-- THEAD --}}
                {{-- ================================================= --}}

                <thead class="bg-slate-50 border-b">

                    <tr>

                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600">

                            Matricule

                        </th>


                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600">

                            Élève

                        </th>


                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600">

                            Sexe

                        </th>


                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600">

                            Téléphone

                        </th>


                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600">

                            Statut

                        </th>


                        <th
                            class="px-6 py-4
                                   text-sm font-semibold
                                   text-slate-600
                                   text-right">

                            Actions

                        </th>

                    </tr>

                </thead>


                {{-- ================================================= --}}
                {{-- TBODY --}}
                {{-- ================================================= --}}

                <tbody class="divide-y">

                    @forelse($eleves as $eleve)

                        <tr
                            class="hover:bg-slate-50
                                   transition">


                            {{-- ========================================= --}}
                            {{-- MATRICULE --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <span
                                    class="font-semibold
                                           text-blue-600">

                                    {{ $eleve->matricule }}

                                </span>

                            </td>


                            {{-- ========================================= --}}
                            {{-- ÉLÈVE --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex items-center
                                           gap-3">


                                    {{-- PHOTO --}}

                                    @if($eleve->photo)

                                        <img
                                            src="{{ asset(
                                                'storage/' .
                                                $eleve->photo
                                            ) }}"

                                            alt="Photo de
                                                {{ $eleve->nom_complet }}"

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


                                    {{-- NOM --}}

                                    <div>

                                        <p
                                            class="font-semibold
                                                   text-slate-700">

                                            {{ $eleve->nom_complet }}

                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- ========================================= --}}
                            {{-- SEXE --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <span class="text-slate-600">

                                    {{ $eleve->sexe_libelle }}

                                </span>

                            </td>


                            {{-- ========================================= --}}
                            {{-- TELEPHONE --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <span class="text-slate-600">

                                    {{ $eleve->telephone ?: '-' }}

                                </span>

                            </td>


                            {{-- ========================================= --}}
                            {{-- STATUT --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <span
                                    class="px-3 py-1
                                           rounded-full
                                           text-sm

                                           {{ $eleve->actif
                                                ? 'bg-green-100
                                                   text-green-700'
                                                : 'bg-red-100
                                                   text-red-700' }}">

                                    {{ $eleve->statut_libelle }}

                                </span>

                            </td>


                            {{-- ========================================= --}}
                            {{-- ACTIONS --}}
                            {{-- ========================================= --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex
                                           justify-end
                                           items-center
                                           gap-2">


                                    {{-- CONSULTATION --}}

                                    <a
                                        href="{{ route(
                                            'eleves.show',
                                            $eleve
                                        ) }}"

                                        title="Consulter"

                                        class="w-9 h-9
                                               flex
                                               items-center
                                               justify-center

                                               rounded-lg

                                               bg-slate-100
                                               text-slate-600

                                               hover:bg-slate-200
                                               transition">

                                        <i
                                            class="fas fa-eye">
                                        </i>

                                    </a>


                                    {{-- MODIFICATION --}}

                                    <a
                                        href="{{ route(
                                            'eleves.edit',
                                            $eleve
                                        ) }}"

                                        title="Modifier"

                                        class="w-9 h-9
                                               flex
                                               items-center
                                               justify-center

                                               rounded-lg

                                               bg-blue-100
                                               text-blue-600

                                               hover:bg-blue-200
                                               transition">

                                        <i
                                            class="fas fa-edit">
                                        </i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        {{-- ============================================= --}}
                        {{-- AUCUN RESULTAT --}}
                        {{-- ============================================= --}}

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-14
                                       text-center">

                                <div
                                    class="flex flex-col
                                           items-center">


                                    <div
                                        class="w-16 h-16
                                               rounded-full
                                               bg-slate-100

                                               flex
                                               items-center
                                               justify-center
                                               mb-4">

                                        <i
                                            class="fas fa-users
                                                   text-2xl
                                                   text-slate-400">
                                        </i>

                                    </div>


                                    @if(!empty($search))

                                        <p
                                            class="font-medium
                                                   text-slate-600">

                                            Aucun élève trouvé.

                                        </p>


                                        <p
                                            class="text-sm
                                                   text-slate-400
                                                   mt-1">

                                            Aucun résultat pour
                                            "{{ $search }}".

                                        </p>

                                    @else

                                        <p
                                            class="font-medium
                                                   text-slate-600">

                                            Aucun élève enregistré.

                                        </p>

                                        <p
                                            class="text-sm
                                                   text-slate-400
                                                   mt-1">

                                            Commencez par ajouter
                                            un élève.

                                        </p>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($eleves->hasPages())

            <div
                class="px-6 py-4
                       border-t
                       flex justify-center">

                {{ $eleves->links() }}

            </div>

        @endif

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


    let timer = null;


    /*
    |--------------------------------------------------------------------------
    | Recherche
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('input', function () {


        clearTimeout(timer);


        const search =
            this.value.trim();


        /*
        |--------------------------------------------------------------------------
        | Afficher / cacher le bouton effacer
        |--------------------------------------------------------------------------
        */

        if (search.length > 0) {

            clearSearch.classList.remove('hidden');

        } else {

            clearSearch.classList.add('hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | Attendre avant d'envoyer la requête
        |--------------------------------------------------------------------------
        */

        timer = setTimeout(function () {


            const url =
                new URL(
                    "{{ route('eleves.index') }}",
                    window.location.origin
                );


            if (search !== '') {

                url.searchParams.set(
                    'search',
                    search
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Retour à la première page
            |--------------------------------------------------------------------------
            */

            url.searchParams.set(
                'page',
                '1'
            );


            window.location.href =
                url.toString();


        }, 1500); // 1 seconde de recherche après la dernière frappe

    });


    /*
    |--------------------------------------------------------------------------
    | Effacer la recherche
    |--------------------------------------------------------------------------
    */

    clearSearch.addEventListener('click', function () {


        searchInput.value = '';


        window.location.href =
            "{{ route('eleves.index') }}";


    });

});

</script>

@endsection
