@extends('layouts.app')

@section('title', 'Inscriptions')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}
    

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">
                Inscriptions
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Liste des inscriptions enregistrées dans l'école.
            </p>

        </div>


        <a
            href="{{ route('inscriptions.create') }}"
            class="inline-flex items-center justify-center
                   px-5 py-2.5
                   rounded-lg
                   bg-blue-600
                   text-white
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-plus mr-2"></i>

            Nouvelle inscription

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="mb-6 p-4 rounded-lg
                   bg-green-50
                   border border-green-200
                   text-green-700">

            <div class="flex items-center">

                <i class="fas fa-check-circle mr-3"></i>

                {{ session('success') }}

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- RECHERCHE --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               p-5 mb-6">

        <form
            method="GET"
            action="{{ route('inscriptions.index') }}"
            id="searchForm">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">


                {{-- Recherche élève --}}

                <div class="md:col-span-2">

                    <label
                        for="search"
                        class="block text-sm font-medium text-slate-700 mb-2">

                        Rechercher

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
                            name="search"
                            id="search"

                            value="{{ request('search') }}"

                            placeholder="Matricule, nom, postnom ou prénom..."

                            autocomplete="off"

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   pl-11 pr-4 py-3
                                   focus:ring-2
                                   focus:ring-blue-500
                                   focus:border-blue-500
                                   focus:outline-none">

                    </div>

                </div>


                {{-- Année scolaire --}}

                <div>

                    <label
                        for="annee_scolaire_id"
                        class="block text-sm font-medium text-slate-700 mb-2">

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
                                {{ request('annee_scolaire_id') == $annee->id
                                    ? 'selected'
                                    : '' }}>

                                {{ $annee->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Statut --}}

                <div>

                    <label
                        for="actif"
                        class="block text-sm font-medium text-slate-700 mb-2">

                        Statut

                    </label>

                    <select
                        name="actif"
                        id="actif"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            Tous
                        </option>

                        <option
                            value="1"
                            {{ request('actif') === '1'
                                ? 'selected'
                                : '' }}>

                            Actives

                        </option>

                        <option
                            value="0"
                            {{ request('actif') === '0'
                                ? 'selected'
                                : '' }}>

                            Inactives

                        </option>

                    </select>

                </div>

            </div>


            {{-- Boutons --}}

            <div class="flex flex-wrap justify-end gap-3 mt-4">

                <a
                    href="{{ route('inscriptions.index') }}"
                    class="px-4 py-2.5
                           rounded-lg
                           bg-slate-200
                           text-slate-700
                           hover:bg-slate-300
                           transition">

                    <i class="fas fa-rotate-left mr-2"></i>

                    Réinitialiser

                </a>

                <button
                    type="submit"

                    class="px-4 py-2.5
                           rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700
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


        {{-- En-tête tableau --}}

        <div
            class="px-6 py-5
                   border-b border-slate-200
                   flex flex-col sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-3">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Liste des inscriptions
                </h2>

                <p class="text-sm text-slate-500 mt-1">

                    {{ $inscriptions->total() }}
                    inscription(s) au total

                </p>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-slate-50">

                    <tr>

                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Élève

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Année scolaire

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Classe

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Date

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Montant

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Statut

                        </th>


                        <th
                            class="px-6 py-4
                                   text-right
                                   font-semibold
                                   text-slate-600
                                   whitespace-nowrap">

                            Actions

                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">


                    @forelse($inscriptions as $inscription)

                        <tr class="hover:bg-slate-50 transition">


                            {{-- Élève --}}

                            <td class="px-6 py-4">

                                <div>

                                    <p class="font-semibold text-slate-800">

                                        {{ $inscription->eleve->nom ?? '' }}
                                        {{ $inscription->eleve->postnom ?? '' }}
                                        {{ $inscription->eleve->prenom ?? '' }}

                                    </p>

                                    <p class="text-xs text-blue-600 mt-1">

                                        {{ $inscription->eleve->matricule ?? '—' }}

                                    </p>

                                </div>

                            </td>


                            {{-- Année --}}

                            <td class="px-6 py-4 whitespace-nowrap">

                                <span class="font-medium text-slate-700">

                                    {{ $inscription->anneeScolaire->libelle ?? '—' }}

                                </span>

                            </td>


                            {{-- Classe --}}

                            <td class="px-6 py-4">

                                <div>

                                    <p class="font-medium text-slate-700">

                                        {{ $inscription->classe->nom_complet
                                            ?? $inscription->classe->nom
                                            ?? '—' }}

                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">

                                        {{ $inscription->classe->section ?? '' }}

                                    </p>

                                </div>

                            </td>


                            {{-- Date --}}

                            <td class="px-6 py-4 whitespace-nowrap">

                                {{ $inscription->date_inscription
                                    ? $inscription->date_inscription->format('d/m/Y')
                                    : '—' }}

                            </td>


                            {{-- Montant --}}

                            <td class="px-6 py-4 whitespace-nowrap">

                                <span class="font-semibold text-slate-700">

                                    {{ number_format(
                                        $inscription->montant ?? 0,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                    FC

                                </span>

                            </td>


                            {{-- Statut --}}

                            <td class="px-6 py-4">

                                @if($inscription->actif)

                                    <span
                                        class="inline-flex items-center
                                               px-3 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-green-100
                                               text-green-700">

                                        <span
                                            class="w-1.5 h-1.5
                                                   rounded-full
                                                   bg-green-500
                                                   mr-2">
                                        </span>

                                        Active

                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center
                                               px-3 py-1
                                               rounded-full
                                               text-xs
                                               font-medium
                                               bg-red-100
                                               text-red-700">

                                        <span
                                            class="w-1.5 h-1.5
                                                   rounded-full
                                                   bg-red-500
                                                   mr-2">
                                        </span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td class="px-6 py-4">

                                <div
                                    class="flex items-center
                                           justify-end
                                           gap-2">


                                    {{-- Voir --}}

                                    <a
                                        href="{{ route(
                                            'inscriptions.show',
                                            $inscription
                                        ) }}"

                                        title="Consulter"

                                        class="w-9 h-9
                                               flex items-center
                                               justify-center
                                               rounded-lg
                                               bg-blue-50
                                               text-blue-600
                                               hover:bg-blue-100
                                               transition">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- Modifier --}}

                                    <a
                                        href="{{ route(
                                            'inscriptions.edit',
                                            $inscription
                                        ) }}"

                                        title="Modifier"

                                        class="w-9 h-9
                                               flex items-center
                                               justify-center
                                               rounded-lg
                                               bg-amber-50
                                               text-amber-600
                                               hover:bg-amber-100
                                               transition">

                                        <i class="fas fa-edit"></i>

                                    </a>


                                    {{-- PDF --}}

                                    <a
                                        href="{{ route(
                                            'inscriptions.pdf',
                                            $inscription
                                        ) }}"

                                        target="_blank"

                                        title="Télécharger le PDF"

                                        class="w-9 h-9
                                               flex items-center
                                               justify-center
                                               rounded-lg
                                               bg-red-50
                                               text-red-600
                                               hover:bg-red-100
                                               transition">

                                        <i class="fas fa-file-pdf"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div
                                        class="w-14 h-14
                                               rounded-full
                                               bg-slate-100
                                               flex items-center
                                               justify-center
                                               text-slate-400
                                               mb-4">

                                        <i class="fas fa-file-signature text-xl"></i>

                                    </div>

                                    <p class="font-medium text-slate-700">
                                        Aucune inscription trouvée
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Les inscriptions apparaîtront ici.
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

        @if($inscriptions->hasPages())

            <div
                class="px-6 py-4
                       border-t border-slate-200">

                {{ $inscriptions->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECHERCHE AVEC DÉLAI DE 2 SECONDES --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const search =
        document.getElementById('search');

    const form =
        document.getElementById('searchForm');

    let searchTimer;


    search.addEventListener('input', function () {

        clearTimeout(searchTimer);


        searchTimer = setTimeout(function () {

            form.submit();

        }, 2000);

    });

});

</script>

@endsection
