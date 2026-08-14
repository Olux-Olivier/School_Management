@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Réinscriptions
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Gérez la réinscription des élèves pour la nouvelle année scolaire.
                </p>

            </div>


            <a
                href="{{ route('inscriptions.index') }}"

                class="inline-flex items-center
                       justify-center
                       px-5 py-2.5
                       rounded-lg
                       bg-slate-200
                       text-slate-700
                       hover:bg-slate-300
                       transition">

                <i class="fas fa-list mr-2"></i>

                Liste des inscriptions

            </a>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- ANNÉES SCOLAIRES --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">


        {{-- Année précédente --}}

        <div
            class="bg-white
                   border border-slate-200
                   rounded-xl
                   shadow-sm
                   p-6">

            <div class="flex items-center gap-4">

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-slate-100
                           flex items-center
                           justify-center
                           text-slate-500">

                    <i class="fas fa-history text-lg"></i>

                </div>

                <div>

                    <p class="text-xs
                              font-medium
                              uppercase
                              tracking-wide
                              text-slate-500">

                        Année précédente

                    </p>

                    <p class="text-xl
                              font-bold
                              text-slate-800
                              mt-1">

                        {{ $anneeScolairePrecedente
                            ? $anneeScolairePrecedente->libelle
                            : 'Aucune' }}

                    </p>

                </div>

            </div>

        </div>


        {{-- Année active --}}

        <div
            class="bg-white
                   border border-blue-200
                   rounded-xl
                   shadow-sm
                   p-6">

            <div class="flex items-center gap-4">

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-blue-50
                           flex items-center
                           justify-center
                           text-blue-600">

                    <i class="fas fa-calendar-check text-lg"></i>

                </div>

                <div>

                    <p class="text-xs
                              font-medium
                              uppercase
                              tracking-wide
                              text-blue-600">

                        Nouvelle année scolaire

                    </p>

                    <p class="text-xl
                              font-bold
                              text-slate-800
                              mt-1">

                        {{ $anneeScolaireActive->libelle }}

                    </p>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- SI AUCUNE ANNÉE PRÉCÉDENTE --}}
    {{-- ========================================================= --}}

    @if(!$anneeScolairePrecedente)

        <div
            class="bg-yellow-50
                   border border-yellow-200
                   rounded-xl
                   p-6
                   text-yellow-800">

            <div class="flex items-start gap-3">

                <i class="fas fa-exclamation-triangle mt-1"></i>

                <div>

                    <p class="font-semibold">
                        Aucune année scolaire précédente trouvée.
                    </p>

                    <p class="text-sm mt-1">
                        Il n'est pas encore possible de procéder
                        aux réinscriptions.
                    </p>

                </div>

            </div>

        </div>

    @else


        {{-- ===================================================== --}}
        {{-- RECHERCHE + FILTRE --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   p-6
                   mb-6">


            <form
                method="GET"
                action="{{ route('reinscriptions.index') }}">


                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                    {{-- Recherche --}}

                    <div class="md:col-span-2">

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


                    {{-- Classe --}}

                    <div>

                        <label
                            for="classe_id"
                            class="block
                                   text-sm
                                   font-medium
                                   text-slate-700
                                   mb-2">

                            Classe précédente

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

                                    {{ request('classe_id') == $classe->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $classe->nom_complet }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Boutons --}}

                <div
                    class="flex
                           flex-col
                           sm:flex-row
                           gap-3
                           mt-5">


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


                    @if(request()->filled('search') || request()->filled('classe_id'))

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

                            <i class="fas fa-rotate-left mr-2"></i>

                            Réinitialiser

                        </a>

                    @endif

                </div>

            </form>

        </div>



        {{-- ===================================================== --}}
        {{-- LISTE --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">


            {{-- En-tête de la liste --}}

            <div
                class="px-6
                       py-5
                       border-b
                       border-slate-200
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

                        Élèves inscrits en
                        <span class="font-medium text-slate-700">

                            {{ $anneeScolairePrecedente->libelle }}

                        </span>

                    </p>

                </div>


                <div
                    class="px-4
                           py-2
                           rounded-lg
                           bg-blue-50
                           text-blue-700
                           text-sm
                           font-semibold">

                    {{ $inscriptions->total() }} élève(s)

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- TABLEAU --}}
            {{-- ================================================= --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">


                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-slate-600">

                                #

                            </th>


                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-slate-600">

                                Matricule

                            </th>


                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-slate-600">

                                Élève

                            </th>


                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-slate-600">

                                Classe précédente

                            </th>


                            <th
                                class="px-6 py-4
                                       text-left
                                       font-semibold
                                       text-slate-600">

                                Statut

                            </th>


                            <th
                                class="px-6 py-4
                                       text-right
                                       font-semibold
                                       text-slate-600">

                                Action

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y divide-slate-100">


                        @forelse($inscriptions as $index => $inscription)

                            <tr
                                class="hover:bg-slate-50
                                       transition">


                                {{-- Numéro --}}

                                <td
                                    class="px-6 py-4
                                           text-slate-500">

                                    {{ $inscriptions->firstItem() + $index }}

                                </td>


                                {{-- Matricule --}}

                                <td class="px-6 py-4">

                                    <span
                                        class="font-medium
                                               text-blue-600">

                                        {{ $inscription->eleve->matricule ?? '—' }}

                                    </span>

                                </td>


                                {{-- Élève --}}

                                <td class="px-6 py-4">

                                    <p
                                        class="font-semibold
                                               text-slate-800">

                                        {{ $inscription->eleve->nom ?? '' }}
                                        {{ $inscription->eleve->postnom ?? '' }}
                                        {{ $inscription->eleve->prenom ?? '' }}

                                    </p>

                                </td>


                                {{-- Classe --}}

                                <td class="px-6 py-4">

                                    <span
                                        class="text-slate-700">

                                        {{ $inscription->classe->nom_complet ?? '—' }}

                                    </span>

                                </td>


                                {{-- Statut --}}

                                <td class="px-6 py-4">

                                    @if($dejaReinscrits->has($inscription->eleve_id))

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3
                                                   py-1
                                                   rounded-full
                                                   text-xs
                                                   font-medium
                                                   bg-green-100
                                                   text-green-700">

                                            <span
                                                class="w-1.5
                                                       h-1.5
                                                       rounded-full
                                                       bg-green-500
                                                       mr-2">
                                            </span>

                                            Déjà réinscrit

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3
                                                   py-1
                                                   rounded-full
                                                   text-xs
                                                   font-medium
                                                   bg-orange-100
                                                   text-orange-700">

                                            <span
                                                class="w-1.5
                                                       h-1.5
                                                       rounded-full
                                                       bg-orange-500
                                                       mr-2">
                                            </span>

                                            À réinscrire

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}

                                <td
                                    class="px-6 py-4
                                           text-right">


                                    @if($dejaReinscrits->has($inscription->eleve_id))

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   justify-center
                                                   px-4
                                                   py-2
                                                   rounded-lg
                                                   bg-slate-100
                                                   text-slate-400
                                                   text-sm
                                                   cursor-not-allowed">

                                            <i class="fas fa-check mr-2"></i>

                                            Déjà fait

                                        </span>

                                    @else

                                        <a
                                            href="{{ route(
                                                'reinscriptions.create',
                                                $inscription
                                            ) }}"

                                            class="inline-flex
                                                   items-center
                                                   justify-center
                                                   px-4
                                                   py-2
                                                   rounded-lg
                                                   bg-blue-600
                                                   text-white
                                                   text-sm
                                                   hover:bg-blue-700
                                                   transition">

                                            <i class="fas fa-user-plus mr-2"></i>

                                            Réinscrire

                                        </a>

                                    @endif

                                </td>

                            </tr>

                        @empty


                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6
                                           py-12
                                           text-center">


                                    <div
                                        class="flex
                                               flex-col
                                               items-center">


                                        <div
                                            class="w-14
                                                   h-14
                                                   rounded-full
                                                   bg-slate-100
                                                   flex
                                                   items-center
                                                   justify-center
                                                   text-slate-400
                                                   mb-4">

                                            <i
                                                class="fas fa-user-graduate text-xl">
                                            </i>

                                        </div>


                                        <p
                                            class="font-medium
                                                   text-slate-700">

                                            Aucun élève trouvé

                                        </p>


                                        <p
                                            class="text-sm
                                                   text-slate-500
                                                   mt-1">

                                            Aucun élève correspondant
                                            aux critères de recherche.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            @if($inscriptions->hasPages())

                <div
                    class="px-6
                           py-4
                           border-t
                           border-slate-200">

                    {{ $inscriptions
                        ->withQueryString()
                        ->links() }}

                </div>

            @endif

        </div>

    @endif

</div>

@endsection
