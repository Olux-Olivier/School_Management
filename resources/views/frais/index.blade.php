@extends('layouts.admin')

@section('title', 'Gestion des frais')
@section('breadcrumb')
    Accueil / Gestion des frais
@endsection

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des frais
            </h1>

            <p class="text-sm text-slate-500 mt-1">

                Année scolaire :

                <span class="font-semibold text-slate-700">
                    {{ $anneeScolaire->libelle }}
                </span>

            </p>

        </div>


        <a
            href="{{ route('frais.create') }}"
            class="inline-flex
                   items-center
                   justify-center
                   px-5 py-2.5
                   bg-blue-600
                   text-white
                   rounded-lg
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-plus mr-2"></i>

            Ajouter un frais

        </a>

        <a
            href="{{ route('frais.dashboard') }}"
            class="inline-flex
                   items-center
                   justify-center
                   px-5 py-2.5
                   bg-blue-600
                   text-white
                   rounded-lg
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-plus mr-2"></i>

            Retour à l'accueil

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGE SUCCÈS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="mb-6
                   p-4
                   rounded-lg
                   bg-green-50
                   border border-green-200
                   text-green-700">

            <div class="flex items-center">

                <i class="fas fa-check-circle mr-2"></i>

                {{ session('success') }}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MESSAGE ERREUR --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div
            class="mb-6
                   p-4
                   rounded-lg
                   bg-red-50
                   border border-red-200
                   text-red-700">

            <div class="flex items-center">

                <i class="fas fa-exclamation-circle mr-2"></i>

                {{ session('error') }}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- RECHERCHE / FILTRES --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               p-5
               mb-6">

        <form
            action="{{ route('frais.index') }}"
            method="GET">

            <div
                class="grid grid-cols-1
                       md:grid-cols-3
                       gap-4">


                {{-- RECHERCHE --}}

                <div class="md:col-span-2">

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
                            placeholder="Intitulé, classe, section ou option..."

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
                            @selected(request('section') === 'maternelle')>

                            Maternelle

                        </option>

                        <option
                            value="primaire"
                            @selected(request('section') === 'primaire')>

                            Primaire

                        </option>

                        <option
                            value="secondaire"
                            @selected(request('section') === 'secondaire')>

                            Secondaire

                        </option>

                        <option
                            value="humanites"
                            @selected(request('section') === 'humanites')>

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

                @if(request()->hasAny(['search', 'section']))

                    <a
                        href="{{ route('frais.index') }}"
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
    {{-- TABLEAU --}}
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

                        <th class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Frais

                        </th>

                        <th class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Classe

                        </th>

                        <th class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Section

                        </th>

                        <th class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Montant

                        </th>

                        <th class="px-6 py-4
                                   text-right
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase
                                   tracking-wider">

                            Actions

                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($frais as $fraisItem)

                        <tr class="hover:bg-slate-50 transition">


                            {{-- FRAIS --}}

                            <td class="px-6 py-4">

                                <div class="font-semibold text-slate-700">

                                    {{ $fraisItem->intitule }}

                                </div>

                            </td>


                            {{-- CLASSE --}}

                            <td class="px-6 py-4">

                                <div class="font-medium text-slate-700">

                                    {{ $fraisItem->classe->nom ?? '—' }}

                                </div>

                                @if($fraisItem->classe?->option)

                                    <div class="text-xs text-slate-500 mt-1">

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
                                        $fraisItem->classe->section ?? '—'
                                    ) }}

                                </span>

                            </td>


                            {{-- MONTANT --}}

                            <td class="px-6 py-4
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


                            {{-- ACTION --}}

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route(
                                        'frais.edit',
                                        $fraisItem
                                    ) }}"

                                    class="inline-flex
                                           items-center
                                           px-3 py-2
                                           rounded-lg
                                           bg-amber-50
                                           text-amber-700
                                           hover:bg-amber-100
                                           transition">

                                    <i class="fas fa-edit mr-2"></i>

                                    Modifier

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center">

                                <div
                                    class="flex
                                           flex-col
                                           items-center
                                           text-slate-400">

                                    <i
                                        class="fas fa-file-invoice-dollar
                                               text-4xl
                                               mb-3">
                                    </i>

                                    <p class="font-medium text-slate-500">

                                        Aucun frais trouvé.

                                    </p>

                                    <p class="text-sm mt-1">

                                        Aucun frais n'est enregistré
                                        pour cette année scolaire.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

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
