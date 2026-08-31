@extends('layouts.admin')

@section('title', 'Historique des frais')
@section('breadcrumb')
    Accueil / Historique des frais
@endsection

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4
                mb-8">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Historique des frais

            </h1>

            <p class="text-sm text-slate-500 mt-1">

                Consultez l'évolution des frais
                au fil des années scolaires.

            </p>

        </div>


        <a
            href="{{ route('frais.index') }}"

            class="inline-flex
                   items-center
                   justify-center
                   px-5 py-2.5
                   bg-blue-600
                   text-white
                   rounded-lg
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-arrow-left mr-2"></i>

            Frais année active

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ANNÉE ACTIVE --}}
    {{-- ========================================================= --}}

    @if($anneeScolaireActive)

        <div
            class="mb-6
                   p-5
                   rounded-xl
                   bg-blue-50
                   border border-blue-200">

            <div class="flex items-center">

                <div
                    class="w-11 h-11
                           rounded-lg
                           bg-blue-600
                           text-white
                           flex
                           items-center
                           justify-center
                           mr-4">

                    <i class="fas fa-calendar-check"></i>

                </div>


                <div>

                    <p class="text-sm text-blue-600 font-medium">

                        Année scolaire active

                    </p>

                    <p class="text-xl font-bold text-blue-900">

                        {{ $anneeScolaireActive->libelle }}

                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTIQUES --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               lg:grid-cols-3
               gap-5
               mb-8">


        {{-- ANNÉES --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-5">

            <div class="flex items-center">

                <div
                    class="w-11 h-11
                           rounded-lg
                           bg-slate-100
                           text-slate-600
                           flex
                           items-center
                           justify-center
                           mr-4">

                    <i class="fas fa-calendar"></i>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Années scolaires

                    </p>

                    <p class="text-2xl font-bold text-slate-800">

                        {{ $nombreAnnees }}

                    </p>

                </div>

            </div>

        </div>


        {{-- TOTAL FRAIS --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-5">

            <div class="flex items-center">

                <div
                    class="w-11 h-11
                           rounded-lg
                           bg-emerald-100
                           text-emerald-600
                           flex
                           items-center
                           justify-center
                           mr-4">

                    <i class="fas fa-file-invoice-dollar"></i>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Total des frais enregistrés

                    </p>

                    <p class="text-2xl font-bold text-slate-800">

                        {{ $nombreFrais }}

                    </p>

                </div>

            </div>

        </div>


        {{-- FRAIS ANNÉE ACTIVE --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-5">

            <div class="flex items-center">

                <div
                    class="w-11 h-11
                           rounded-lg
                           bg-blue-100
                           text-blue-600
                           flex
                           items-center
                           justify-center
                           mr-4">

                    <i class="fas fa-chart-line"></i>

                </div>

                <div>

                    <p class="text-sm text-slate-500">

                        Frais année active

                    </p>

                    <p class="text-2xl font-bold text-slate-800">

                        {{ $nombreFraisAnneeActive }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTRES --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               p-5
               mb-6">

        <form
            action="{{ route('frais.historique') }}"
            method="GET">


            <div
                class="grid grid-cols-1
                       md:grid-cols-3
                       gap-4">


                {{-- RECHERCHE --}}

                <div>

                    <label
                        for="search"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Rechercher

                    </label>

                    <div class="relative">

                        <div
                            class="absolute
                                   inset-y-0
                                   left-0
                                   pl-4
                                   flex
                                   items-center
                                   pointer-events-none">

                            <i
                                class="fas fa-search
                                       text-slate-400">
                            </i>

                        </div>


                        <input
                            type="text"
                            name="search"
                            id="search"

                            value="{{ request('search') }}"

                            placeholder="Frais, classe ou option..."

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


                {{-- ANNÉE --}}

                <div>

                    <label
                        for="annee_scolaire_id"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Année scolaire

                    </label>


                    <select
                        name="annee_scolaire_id"
                        id="annee_scolaire_id"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">

                            Toutes les années

                        </option>


                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"

                                @selected(
                                    request('annee_scolaire_id')
                                    == $annee->id
                                )>

                                {{ $annee->libelle }}

                                @if($annee->actif)

                                    — ACTIVE

                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- SECTION --}}

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
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">

                            Toutes les sections

                        </option>


                        <option
                            value="maternelle"

                            @selected(
                                request('section')
                                === 'maternelle'
                            )>

                            Maternelle

                        </option>


                        <option
                            value="primaire"

                            @selected(
                                request('section')
                                === 'primaire'
                            )>

                            Primaire

                        </option>


                        <option
                            value="secondaire"

                            @selected(
                                request('section')
                                === 'secondaire'
                            )>

                            Secondaire

                        </option>


                        <option
                            value="humanites"

                            @selected(
                                request('section')
                                === 'humanites'
                            )>

                            Humanités

                        </option>

                    </select>

                </div>

            </div>


            {{-- BOUTONS --}}

            <div
                class="flex
                       flex-col-reverse
                       sm:flex-row
                       sm:justify-end
                       gap-3
                       mt-4">


                @if(request()->hasAny([
                    'search',
                    'annee_scolaire_id',
                    'section'
                ]))

                    <a
                        href="{{ route('frais.historique') }}"

                        class="px-5 py-2.5
                               rounded-lg
                               bg-slate-200
                               text-slate-700
                               text-center
                               hover:bg-slate-300
                               transition">

                        <i class="fas fa-rotate-left mr-2"></i>

                        Réinitialiser

                    </a>

                @endif


                <button
                    type="submit"

                    class="px-5 py-2.5
                           rounded-lg
                           bg-slate-700
                           text-white
                           hover:bg-slate-800
                           transition">

                    <i class="fas fa-search mr-2"></i>

                    Rechercher

                </button>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLEAU HISTORIQUE --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead
                    class="bg-slate-50
                           border-b
                           border-slate-200">

                    <tr>

                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Année scolaire

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Frais

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Classe

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Section

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Montant

                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-slate-100">


                    @forelse($frais as $fraisItem)

                        <tr
                            class="hover:bg-slate-50
                                   transition">


                            {{-- ANNÉE --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex
                                           items-center
                                           gap-2">

                                    <span
                                        class="font-semibold
                                               text-slate-700">

                                        {{ $fraisItem->anneeScolaire->libelle }}

                                    </span>


                                    @if(
                                        $anneeScolaireActive
                                        &&
                                        $fraisItem->annee_scolaire_id
                                        ==
                                        $anneeScolaireActive->id
                                    )

                                        <span
                                            class="inline-flex
                                                   px-2.5
                                                   py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-green-100
                                                   text-green-700">

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex
                                                   px-2.5
                                                   py-1
                                                   rounded-full
                                                   text-xs
                                                   font-medium
                                                   bg-slate-100
                                                   text-slate-500">

                                            Historique

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- FRAIS --}}

                            <td class="px-6 py-4">

                                <span
                                    class="font-semibold
                                           text-slate-700">

                                    {{ $fraisItem->intitule }}

                                </span>

                            </td>


                            {{-- CLASSE --}}

                            <td class="px-6 py-4">

                                <div
                                    class="font-medium
                                           text-slate-700">

                                    {{ $fraisItem->classe->nom }}

                                </div>


                                @if($fraisItem->classe->option)

                                    <div
                                        class="text-xs
                                               text-slate-500
                                               mt-1">

                                        {{ $fraisItem->classe->option }}

                                    </div>

                                @endif

                            </td>


                            {{-- SECTION --}}

                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex
                                           px-3 py-1
                                           rounded-full
                                           text-xs
                                           font-medium
                                           bg-blue-50
                                           text-blue-700">

                                    {{ ucfirst(
                                        $fraisItem->classe->section
                                    ) }}

                                </span>

                            </td>


                            {{-- MONTANT --}}

                            <td
                                class="px-6 py-4
                                       font-semibold
                                       text-slate-700">

                                {{ number_format(
                                    $fraisItem->montant,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FC

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12
                                       text-center">

                                <div
                                    class="flex
                                           flex-col
                                           items-center
                                           text-slate-400">

                                    <i
                                        class="fas fa-clock-rotate-left
                                               text-4xl
                                               mb-3">
                                    </i>


                                    <p
                                        class="font-medium
                                               text-slate-500">

                                        Aucun frais trouvé.

                                    </p>


                                    <p
                                        class="text-sm
                                               mt-1">

                                        Aucun résultat ne correspond
                                        aux critères sélectionnés.

                                    </p>

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

        @if($frais->hasPages())

            <div
                class="px-6 py-4
                       border-t
                       border-slate-200">

                {{ $frais->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
