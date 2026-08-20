@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4
                mb-8">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Évolution des frais

            </h1>

            <p class="text-sm text-slate-500 mt-1">

                Consultez l'évolution des montants
                au fil des années scolaires.

            </p>

        </div>


        <a
            href="{{ route('frais.historique') }}"

            class="inline-flex
                   items-center
                   justify-center
                   px-5 py-2.5
                   bg-slate-700
                   text-white
                   rounded-lg
                   hover:bg-slate-800
                   transition">

            <i class="fas fa-clock-rotate-left mr-2"></i>

            Historique

        </a>

    </div>


    {{-- FILTRES --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               p-5
               mb-6">

        <form
            method="GET"
            action="{{ route('frais.evolution') }}">

            <div
                class="grid grid-cols-1
                       md:grid-cols-2
                       gap-5">


                {{-- FRAIS --}}

                <div>

                    <label
                        for="intitule"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Frais

                    </label>


                    <select
                        name="intitule"
                        id="intitule"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:outline-none">

                        <option value="">

                            Tous les frais

                        </option>


                        @foreach($intitules as $intitule)

                            <option
                                value="{{ $intitule }}"

                                @selected(
                                    request('intitule')
                                    === $intitule
                                )>

                                {{ $intitule }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CLASSE --}}

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
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:outline-none">

                        <option value="">

                            Toutes les classes

                        </option>


                        @foreach($classes as $classe)

                            <option
                                value="{{ $classe->id }}"

                                @selected(
                                    request('classe_id')
                                    == $classe->id
                                )>

                                {{ $classe->nom }}

                                @if($classe->option)

                                    - {{ $classe->option }}

                                @endif

                                — {{ ucfirst($classe->section) }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <div
                class="flex
                       justify-end
                       gap-3
                       mt-5">

                @if(request()->hasAny([
                    'intitule',
                    'classe_id'
                ]))

                    <a
                        href="{{ route('frais.evolution') }}"

                        class="px-5 py-2.5
                               rounded-lg
                               bg-slate-200
                               text-slate-700
                               hover:bg-slate-300">

                        Réinitialiser

                    </a>

                @endif


                <button
                    type="submit"

                    class="px-5 py-2.5
                           rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700">

                    <i class="fas fa-filter mr-2"></i>

                    Afficher l'évolution

                </button>

            </div>

        </form>

    </div>


    {{-- TABLEAU --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">

        <div class="p-5 border-b border-slate-200">

            <h2
                class="text-lg
                       font-semibold
                       text-slate-800">

                Évolution des montants

            </h2>

            <p
                class="text-sm
                       text-slate-500
                       mt-1">

                Comparaison des montants enregistrés
                pour les différentes années scolaires.

            </p>

        </div>


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
                                   uppercase">

                            Année scolaire

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Frais

                        </th>


                        <th
                            class="px-6 py-4
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Classe

                        </th>


                        <th
                            class="px-6 py-4
                                   text-right
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Montant

                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y divide-slate-100">

                    @forelse($frais as $fraisItem)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <span
                                    class="font-semibold
                                           text-slate-700">

                                    {{ $fraisItem->anneeScolaire->libelle }}

                                </span>

                            </td>


                            <td class="px-6 py-4">

                                {{ $fraisItem->intitule }}

                            </td>


                            <td class="px-6 py-4">

                                {{ $fraisItem->classe->nom }}

                                @if($fraisItem->classe->option)

                                    <span
                                        class="text-xs
                                               text-slate-500">

                                        — {{ $fraisItem->classe->option }}

                                    </span>

                                @endif

                            </td>


                            <td
                                class="px-6 py-4
                                       text-right
                                       font-bold
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
                                colspan="4"
                                class="px-6 py-12
                                       text-center
                                       text-slate-500">

                                Aucun historique disponible.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
