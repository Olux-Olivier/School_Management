@extends('layouts.app')

@section('title', 'Réinscriptions')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Réinscriptions
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Liste des élèves de l'année scolaire précédente
                    qui doivent être réinscrits.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="mb-6 p-4 rounded-lg
                   bg-green-50
                   border border-green-200
                   text-green-700">

            <div class="flex items-center gap-3">

                <i class="fas fa-check-circle"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    @if(session('error'))

        <div
            class="mb-6 p-4 rounded-lg
                   bg-red-50
                   border border-red-200
                   text-red-700">

            <div class="flex items-center gap-3">

                <i class="fas fa-exclamation-circle"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ANNÉES SCOLAIRES --}}
    {{-- ========================================================= --}}

    @if($anneeScolairePrecedente)

        <div
            class="mb-6
                   grid grid-cols-1
                   md:grid-cols-2
                   gap-4">

            {{-- Année précédente --}}

            <div
                class="bg-white
                       border border-slate-200
                       rounded-xl
                       p-5
                       shadow-sm">

                <div class="flex items-center gap-4">

                    <div
                        class="w-11 h-11
                               rounded-lg
                               bg-slate-100
                               flex items-center
                               justify-center
                               text-slate-500">

                        <i class="fas fa-calendar-alt"></i>

                    </div>

                    <div>

                        <p class="text-xs
                                  uppercase
                                  tracking-wide
                                  text-slate-400
                                  font-medium">

                            Année précédente

                        </p>

                        <p class="text-lg
                                  font-semibold
                                  text-slate-800">

                            {{ $anneeScolairePrecedente->libelle }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- Année active --}}

            <div
                class="bg-white
                       border border-green-200
                       rounded-xl
                       p-5
                       shadow-sm">

                <div class="flex items-center gap-4">

                    <div
                        class="w-11 h-11
                               rounded-lg
                               bg-green-100
                               flex items-center
                               justify-center
                               text-green-600">

                        <i class="fas fa-calendar-check"></i>

                    </div>

                    <div>

                        <p class="text-xs
                                  uppercase
                                  tracking-wide
                                  text-green-600
                                  font-medium">

                            Année scolaire active

                        </p>

                        <p class="text-lg
                                  font-semibold
                                  text-slate-800">

                            {{ $anneeScolaireActive->libelle }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- FILTRES --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               border border-slate-200
               shadow-sm
               p-5
               mb-6">

        <form
            method="GET"
            action="{{ route('reinscriptions.index') }}">

            <div
                class="grid grid-cols-1
                       lg:grid-cols-3
                       gap-4">


                {{-- ================================================= --}}
                {{-- RECHERCHE --}}
                {{-- ================================================= --}}

                <div class="lg:col-span-1">

                    <label
                        for="search"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Rechercher un élève

                    </label>


                    <div class="relative">

                        <div
                            class="absolute
                                   inset-y-0
                                   left-0
                                   flex
                                   items-center
                                   pl-4
                                   pointer-events-none">

                            <i class="fas fa-search text-slate-400"></i>

                        </div>


                        <input
                            type="text"
                            name="search"
                            id="search"

                            value="{{ request('search') }}"

                            placeholder="Matricule, nom, postnom ou prénom..."

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   pl-11
                                   pr-4
                                   py-3
                                   focus:ring-2
                                   focus:ring-blue-500
                                   focus:border-blue-500
                                   focus:outline-none">

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- SECTION --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="section"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Section

                    </label>


                    <select
                        name="section"
                        id="section"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4
                               py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            Toutes les sections
                        </option>


                        <option
                            value="maternelle"

                            {{ request('section') === 'maternelle'
                                ? 'selected'
                                : '' }}>

                            Maternelle

                        </option>


                        <option
                            value="primaire"

                            {{ request('section') === 'primaire'
                                ? 'selected'
                                : '' }}>

                            Primaire

                        </option>


                        <option
                            value="secondaire"

                            {{ request('section') === 'secondaire'
                                ? 'selected'
                                : '' }}>

                            Secondaire

                        </option>


                        <option
                            value="humanites"

                            {{ request('section') === 'humanites'
                                ? 'selected'
                                : '' }}>

                            Humanités

                        </option>

                    </select>

                </div>



                {{-- ================================================= --}}
                {{-- CLASSE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="classe_id"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Classe

                    </label>


                    <select
                        name="classe_id"
                        id="classe_id"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4
                               py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            Toutes les classes
                        </option>


                        @foreach($classes as $classe)

                            <option
                                value="{{ $classe->id }}"

                                data-niveau="{{ $classe->niveau }}"

                                {{ request('classe_id') == $classe->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $classe->nom_complet }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BOUTONS --}}
            {{-- ================================================= --}}

            <div
                class="mt-4
                       flex
                       flex-col
                       sm:flex-row
                       gap-3">

                <button
                    type="submit"

                    class="inline-flex
                           items-center
                           justify-center
                           px-5
                           py-2.5
                           rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700
                           transition">

                    <i class="fas fa-search mr-2"></i>

                    Rechercher

                </button>


                <a
                    href="{{ route('reinscriptions.index') }}"

                    class="inline-flex
                           items-center
                           justify-center
                           px-5
                           py-2.5
                           rounded-lg
                           bg-slate-200
                           text-slate-700
                           hover:bg-slate-300
                           transition">

                    <i class="fas fa-redo mr-2"></i>

                    Réinitialiser

                </a>

            </div>

        </form>





    {{-- ========================================================= --}}
    {{-- LISTE --}}
    {{-- ========================================================= --}}


        {{-- ===================================================== --}}
        {{-- EN-TÊTE TABLEAU --}}
        {{-- ===================================================== --}}

        <div
            class="px-6
                   py-5
                   border-b border-t mt-5 border-slate-200
                   flex
                   flex-col
                   sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-3">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">

                    Élèves à réinscrire

                </h2>

                <p class="text-sm text-slate-500 mt-1">

                    {{ $inscriptions->total() }}
                    élève(s) à réinscrire

                </p>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- TABLEAU --}}
        {{-- ===================================================== --}}

        @if($inscriptions->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                class="px-6
                                       py-3
                                       text-left
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Élève

                            </th>


                            <th
                                class="px-6
                                       py-3
                                       text-left
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Matricule

                            </th>


                            <th
                                class="px-6
                                       py-3
                                       text-left
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Section

                            </th>


                            <th
                                class="px-6
                                       py-3
                                       text-left
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Classe précédente

                            </th>


                            <th
                                class="px-6
                                       py-3
                                       text-right
                                       text-xs
                                       font-semibold
                                       uppercase
                                       tracking-wider
                                       text-slate-500">

                                Action

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="bg-white
                               divide-y
                               divide-slate-100">

                        @foreach($inscriptions as $inscription)

                            <tr
                                class="hover:bg-slate-50
                                       transition">


                                {{-- Élève --}}

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-10 h-10
                                                   flex-shrink-0
                                                   rounded-full
                                                   bg-blue-100
                                                   flex items-center
                                                   justify-center
                                                   text-blue-600">

                                            <i class="fas fa-user"></i>

                                        </div>


                                        <div>

                                            <p
                                                class="font-semibold
                                                       text-slate-800">

                                                {{ $inscription->eleve->nom }}
                                                {{ $inscription->eleve->postnom }}
                                                {{ $inscription->eleve->prenom }}

                                            </p>

                                        </div>

                                    </div>

                                </td>



                                {{-- Matricule --}}

                                <td
                                    class="px-6
                                           py-4
                                           text-sm
                                           font-medium
                                           text-blue-600">

                                    {{ $inscription->eleve->matricule }}

                                </td>



                                {{-- Section --}}

                                <td class="px-6 py-4">

                                    @php

                                        $section = match(
                                            $inscription->classe->niveau
                                        ) {

                                            0 => 'Maternelle',

                                            1 => 'Primaire',

                                            2 => 'Secondaire',

                                            3 => 'Humanités',

                                            default => 'Inconnue',

                                        };

                                    @endphp


                                    <span
                                        class="inline-flex
                                               items-center
                                               px-2.5
                                               py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-slate-100
                                               text-slate-700">

                                        {{ $section }}

                                    </span>

                                </td>



                                {{-- Classe --}}

                                <td
                                    class="px-6
                                           py-4
                                           text-sm
                                           text-slate-700">

                                    {{ $inscription->classe->nom_complet }}

                                </td>



                                {{-- Action --}}

                                <td
                                    class="px-6
                                           py-4
                                           text-right">

                                    <a
                                        href="{{ route(
                                            'reinscriptions.create',
                                            $inscription
                                        ) }}"

                                        class="inline-flex
                                               items-center
                                               px-4
                                               py-2
                                               rounded-lg
                                               bg-blue-600
                                               text-white
                                               text-sm
                                               font-medium
                                               hover:bg-blue-700
                                               transition">

                                        <i
                                            class="fas fa-user-plus mr-2">
                                        </i>

                                        Réinscrire

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            <div
                class="px-6
                       py-4
                       border-t border-slate-200">

                {{ $inscriptions->links() }}

            </div>


        @else

            {{-- ================================================= --}}
            {{-- AUCUN ÉLÈVE --}}
            {{-- ================================================= --}}

            <div
                class="px-6
                       py-16
                       text-center">

                <div
                    class="w-16
                           h-16
                           mx-auto
                           rounded-full
                           bg-green-100
                           flex
                           items-center
                           justify-center
                           text-green-600
                           text-2xl">

                    <i class="fas fa-check"></i>

                </div>


                <h3
                    class="mt-4
                           text-lg
                           font-semibold
                           text-slate-800">

                    Aucun élève à réinscrire

                </h3>


                <p
                    class="mt-1
                           text-sm
                           text-slate-500">

                    Tous les élèves concernés ont déjà été
                    réinscrits dans l'année scolaire active,
                    ou aucun élève ne correspond à votre recherche.

                </p>

            </div>

        @endif

    </div>

</div>



{{-- ============================================================= --}}
{{-- JAVASCRIPT : SECTION → CLASSE --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const section =
        document.getElementById('section');

    const classe =
        document.getElementById('classe_id');


    function filtrerClasses()
    {

        const selectedSection =
            section.value;


        let niveau = '';


        /*
        |--------------------------------------------------------------------------
        | Section → niveau
        |--------------------------------------------------------------------------
        */

        switch (selectedSection) {

            case 'maternelle':

                niveau = '0';

                break;


            case 'primaire':

                niveau = '1';

                break;


            case 'secondaire':

                niveau = '2';

                break;


            case 'humanites':

                niveau = '3';

                break;


            default:

                niveau = '';

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Filtrer les classes
        |--------------------------------------------------------------------------
        */

        Array.from(
            classe.options
        ).forEach(function (option) {

            if (!option.value) {

                option.hidden = false;

                return;
            }


            const classeNiveau =
                option.dataset.niveau;


            if (
                niveau === ''
                ||
                classeNiveau === niveau
            ) {

                option.hidden = false;

            } else {

                option.hidden = true;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe actuellement sélectionnée
        |--------------------------------------------------------------------------
        */

        const selectedOption =
            classe.options[
                classe.selectedIndex
            ];


        if (
            selectedOption
            &&
            selectedOption.value
            &&
            selectedOption.hidden
        ) {

            classe.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Changement de section
    |--------------------------------------------------------------------------
    */

    section.addEventListener(
        'change',
        function () {

            /*
            | Si on change de section,
            | on remet la classe à zéro.
            */

            classe.value = '';

            filtrerClasses();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    filtrerClasses();

});

</script>

@endsection
