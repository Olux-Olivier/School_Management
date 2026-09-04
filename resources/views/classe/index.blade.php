@extends('layouts.app')

@section('title', 'Classes')

@section('content')

<div class="max-w-7xl mx-auto py-8">

{{-- En-tête --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-700">
            Gestion des classes
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Gérez les classes de l'établissement.
        </p>
    </div>

    <a
        href="{{ route('classes.create') }}"
        class="inline-flex items-center justify-center
               px-5 py-2.5 rounded-lg
               bg-blue-600 text-white
               hover:bg-blue-700 transition">

        <i class="fas fa-plus mr-2"></i>

        Ajouter une classe

    </a>

</div>


{{-- Message de succès --}}
@if(session('success'))

    <div
        class="mb-6 px-4 py-3 rounded-lg
               bg-green-100 text-green-700 border border-green-200">

        {{ session('success') }}

    </div>

@endif


{{-- Zone principale --}}
<div class="bg-white rounded-xl shadow-sm border">


    {{-- Barre de recherche --}}
    <div class="p-5">

        <div class="relative max-w-md">

            <span
                class="absolute inset-y-0 left-0 flex items-center pl-3
                       text-slate-400">

                <i class="fas fa-search"></i>

            </span>

            <input
                type="text"
                id="searchClasse"
                placeholder="Rechercher une classe..."
                class="w-full pl-10 pr-4 py-2.5
                       border rounded-lg
                       focus:ring-2 focus:ring-blue-500
                       focus:outline-none">

        </div>

    </div>


    {{-- Tableau --}}
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

                <tr>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Classe
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Section
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Niveau
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Option
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Variante
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">
                        Statut
                    </th>

                    <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody
                id="classesTable"
                class="divide-y divide-slate-100">

                @forelse($classes as $classe)

                    <tr
                        class="classe-row hover:bg-slate-50 transition"
                        data-search="{{ strtolower(
                            $classe->nom_complet . ' ' .
                            $classe->section . ' ' .
                            $classe->niveau_libelle . ' ' .
                            ($classe->option ?? '') . ' ' .
                            ($classe->variante ?? '')
                        ) }}">

                        {{-- Classe --}}
                        <td class="px-6 py-4">

                            <div class="font-semibold text-slate-700">

                                {{ $classe->nom_complet }}

                            </div>

                        </td>


                        {{-- Section --}}
                        <td class="px-6 py-4 text-slate-600">

                            {{ $classe->section }}

                        </td>


                        {{-- Niveau --}}
                        <td class="px-6 py-4 text-slate-600">

                            {{ $classe->niveau_libelle }}

                        </td>


                        {{-- Option --}}
                        <td class="px-6 py-4">

                            @if($classe->niveau == 3)

                                <span class="text-slate-700">

                                    {{ $classe->option }}

                                </span>

                            @else

                                <span class="text-slate-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Variante --}}
                        <td class="px-6 py-4">

                            @if($classe->variante)

                                <span
                                    class="inline-flex items-center justify-center
                                           min-w-8 h-8 px-2
                                           rounded-lg
                                           bg-indigo-100 text-indigo-700
                                           font-semibold">

                                    {{ $classe->variante }}

                                </span>

                            @else

                                <span class="text-slate-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Statut --}}
                        <td class="px-6 py-4">

                            @if($classe->actif)

                                <span
                                    class="inline-flex px-3 py-1
                                           rounded-full text-sm
                                           bg-green-100 text-green-700">

                                    Actif

                                </span>

                            @else

                                <span
                                    class="inline-flex px-3 py-1
                                           rounded-full text-sm
                                           bg-red-100 text-red-700">

                                    Inactif

                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                {{-- Consulter --}}
                                <a
                                    href="{{ route('classes.show', $classe) }}"
                                    title="Consulter"
                                    class="w-9 h-9 flex items-center justify-center
                                           rounded-lg
                                           bg-blue-100 text-blue-600
                                           hover:bg-blue-200 transition">

                                    <i class="fas fa-eye"></i>

                                </a>


                                {{-- Modifier --}}
                                <a
                                    href="{{ route('classes.edit', $classe) }}"
                                    title="Modifier"
                                    class="w-9 h-9 flex items-center justify-center
                                           rounded-lg
                                           bg-yellow-100 text-yellow-600
                                           hover:bg-yellow-200 transition">

                                    <i class="fas fa-edit"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-12 text-center text-slate-500">

                            <i class="fas fa-school text-3xl mb-3"></i>

                            <p>
                                Aucune classe enregistrée.
                            </p>

                        </td>

                    </tr>

                @endforelse


                {{-- Aucun résultat de recherche --}}
                <tr
                    id="noResult"
                    class="hidden">

                    <td
                        colspan="7"
                        class="px-6 py-10 text-center text-slate-500">

                        <i class="fas fa-search text-2xl mb-2"></i>

                        <p>
                            Aucune classe ne correspond à votre recherche.
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
        document.getElementById('searchClasse');

    const rows =
        document.querySelectorAll('.classe-row');

    const noResult =
        document.getElementById('noResult');


    searchInput.addEventListener('input', function () {

        const search =
            this.value.toLowerCase().trim();

        let visibleRows = 0;


        rows.forEach(function (row) {

            const text =
                row.dataset.search.toLowerCase();

            if (text.includes(search)) {

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

        if (visibleRows === 0) {

            noResult.classList.remove('hidden');

        } else {

            noResult.classList.add('hidden');

        }

    });

});

</script>

@endsection
