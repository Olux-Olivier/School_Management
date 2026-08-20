@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex flex-col md:flex-row
                    md:items-center
                    md:justify-between
                    gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">

                    Comparaison des frais

                </h1>

                <p class="text-sm text-slate-500 mt-1">

                    Comparez l'évolution des montants
                    entre deux années scolaires.

                </p>

            </div>


            <a
                href="{{ route('frais.index') }}"

                class="inline-flex
                       items-center
                       justify-center
                       px-5 py-2.5
                       rounded-lg
                       bg-slate-200
                       text-slate-700
                       hover:bg-slate-300
                       transition">

                <i class="fas fa-arrow-left mr-2"></i>

                Retour aux frais

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div
            class="mb-6
                   p-4
                   rounded-lg
                   bg-red-50
                   border border-red-200
                   text-red-700">

            <div class="flex items-start">

                <i class="fas fa-exclamation-circle mr-3 mt-1"></i>

                <div>

                    <p class="font-semibold mb-1">

                        Impossible d'effectuer la comparaison.

                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULAIRE DE COMPARAISON --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               p-6
               mb-6">

        <div class="mb-6">

            <h2
                class="text-lg
                       font-semibold
                       text-slate-700">

                Critères de comparaison

            </h2>

            <p
                class="text-sm
                       text-slate-500
                       mt-1">

                Sélectionnez les deux années et les critères
                que vous souhaitez comparer.

            </p>

        </div>


        <form
            action="{{ route('frais.comparaison') }}"
            method="GET">


            <div
                class="grid grid-cols-1
                       md:grid-cols-2
                       lg:grid-cols-4
                       gap-5">


                {{-- ANNÉE 1 --}}

                <div>

                    <label
                        for="annee_1"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Première année

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="annee_1"
                        id="annee_1"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            -- Première année --
                        </option>

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"

                                @selected(
                                    request('annee_1')
                                    == $annee->id
                                )>

                                {{ $annee->libelle }}

                                @if($annee->actif)
                                    — Active
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ANNÉE 2 --}}

                <div>

                    <label
                        for="annee_2"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Deuxième année

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="annee_2"
                        id="annee_2"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            -- Deuxième année --
                        </option>

                        @foreach($anneesScolaires as $annee)

                            <option
                                value="{{ $annee->id }}"

                                @selected(
                                    request('annee_2')
                                    == $annee->id
                                )>

                                {{ $annee->libelle }}

                                @if($annee->actif)
                                    — Active
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
                               focus:border-blue-500
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
                                    — {{ $classe->option }}
                                @endif

                                — {{ ucfirst($classe->section) }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- INTITULÉ --}}

            <div class="mt-5">

                <label
                    for="intitule"
                    class="block
                           text-sm
                           font-medium
                           text-slate-700
                           mb-2">

                    Frais à comparer

                </label>

                <input
                    type="text"
                    name="intitule"
                    id="intitule"

                    value="{{ request('intitule') }}"

                    placeholder="Exemple : Minerval"

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           focus:outline-none">

            </div>


            {{-- BOUTONS --}}

            <div
                class="flex
                       flex-col-reverse
                       sm:flex-row
                       sm:justify-end
                       gap-3
                       mt-5">

                <a
                    href="{{ route('frais.comparaison') }}"

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


                <button
                    type="submit"

                    class="px-5 py-2.5
                           rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700
                           transition">

                    <i class="fas fa-chart-line mr-2"></i>

                    Comparer

                </button>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- RÉSULTATS --}}
    {{-- ========================================================= --}}

    @if($annee1 && $annee2)

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">


            {{-- TITRE --}}

            <div
                class="px-6 py-5
                       border-b
                       border-slate-200
                       bg-slate-50">

                <div
                    class="flex
                           flex-col
                           md:flex-row
                           md:items-center
                           md:justify-between
                           gap-3">

                    <div>

                        <h2
                            class="text-lg
                                   font-bold
                                   text-slate-800">

                            Résultats de la comparaison

                        </h2>

                        <p
                            class="text-sm
                                   text-slate-500
                                   mt-1">

                            {{ $annee1->libelle }}

                            <span class="mx-2">
                                →
                            </span>

                            {{ $annee2->libelle }}

                        </p>

                    </div>

                    <div
                        class="text-sm
                               text-slate-500">

                        {{ $resultats->count() }}
                        résultat(s)

                    </div>

                </div>

            </div>


            {{-- TABLEAU --}}

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead
                        class="bg-slate-100
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

                                {{ $annee1->libelle }}

                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
                                       text-xs
                                       font-semibold
                                       text-slate-500
                                       uppercase">

                                {{ $annee2->libelle }}

                            </th>

                            <th
                                class="px-6 py-4
                                       text-right
                                       text-xs
                                       font-semibold
                                       text-slate-500
                                       uppercase">

                                Différence

                            </th>

                            <th
                                class="px-6 py-4
                                       text-center
                                       text-xs
                                       font-semibold
                                       text-slate-500
                                       uppercase">

                                Évolution

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y
                               divide-slate-100">

                        @forelse($resultats as $resultat)

                            <tr
                                class="hover:bg-slate-50
                                       transition">


                                {{-- FRAIS --}}

                                <td
                                    class="px-6 py-4">

                                    <div
                                        class="font-semibold
                                               text-slate-700">

                                        {{ $resultat['intitule'] }}

                                    </div>

                                </td>


                                {{-- CLASSE --}}

                                <td
                                    class="px-6 py-4">

                                    <div
                                        class="font-medium
                                               text-slate-700">

                                        {{ $resultat['classe']->nom }}

                                    </div>

                                    @if($resultat['classe']->option)

                                        <div
                                            class="text-xs
                                                   text-slate-500
                                                   mt-1">

                                            {{ $resultat['classe']->option }}

                                        </div>

                                    @endif

                                    <div
                                        class="text-xs
                                               text-slate-400
                                               mt-1">

                                        {{ ucfirst(
                                            $resultat['classe']->section
                                        ) }}

                                    </div>

                                </td>


                                {{-- ANNÉE 1 --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-semibold
                                           text-slate-700">

                                    @if(
                                        $resultat['montant_1']
                                        !== null
                                    )

                                        {{ number_format(
                                            $resultat['montant_1'],
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FC

                                    @else

                                        <span
                                            class="text-slate-400">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- ANNÉE 2 --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-semibold
                                           text-slate-700">

                                    @if(
                                        $resultat['montant_2']
                                        !== null
                                    )

                                        {{ number_format(
                                            $resultat['montant_2'],
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FC

                                    @else

                                        <span
                                            class="text-slate-400">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- DIFFÉRENCE --}}

                                <td
                                    class="px-6 py-4
                                           text-right
                                           font-semibold">

                                    @if(
                                        $resultat['difference']
                                        !== null
                                    )

                                        @if(
                                            $resultat['difference'] > 0
                                        )

                                            <span class="text-red-600">

                                                +{{ number_format(
                                                    $resultat['difference'],
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}
                                                FC

                                            </span>

                                        @elseif(
                                            $resultat['difference'] < 0
                                        )

                                            <span class="text-green-600">

                                                {{ number_format(
                                                    $resultat['difference'],
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}
                                                FC

                                            </span>

                                        @else

                                            <span
                                                class="text-slate-500">

                                                0 FC

                                            </span>

                                        @endif

                                    @else

                                        <span
                                            class="text-slate-400">

                                            —

                                        </span>

                                    @endif

                                </td>


                                {{-- ÉVOLUTION --}}

                                <td
                                    class="px-6 py-4
                                           text-center">

                                    @if(
                                        $resultat['statut']
                                        === 'augmentation'
                                    )

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-red-50
                                                   text-red-700">

                                            <i
                                                class="fas fa-arrow-up mr-1">
                                            </i>

                                            +{{ number_format(
                                                $resultat['pourcentage'],
                                                2,
                                                ',',
                                                ' '
                                            ) }} %

                                        </span>


                                    @elseif(
                                        $resultat['statut']
                                        === 'diminution'
                                    )

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-green-50
                                                   text-green-700">

                                            <i
                                                class="fas fa-arrow-down mr-1">
                                            </i>

                                            {{ number_format(
                                                $resultat['pourcentage'],
                                                2,
                                                ',',
                                                ' '
                                            ) }} %

                                        </span>


                                    @elseif(
                                        $resultat['statut']
                                        === 'stable'
                                    )

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-slate-100
                                                   text-slate-600">

                                            <i
                                                class="fas fa-minus mr-1">
                                            </i>

                                            Stable

                                        </span>


                                    @elseif(
                                        $resultat['statut']
                                        === 'nouveau'
                                    )

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-blue-50
                                                   text-blue-700">

                                            <i
                                                class="fas fa-plus mr-1">
                                            </i>

                                            Nouveau

                                        </span>


                                    @elseif(
                                        $resultat['statut']
                                        === 'supprime'
                                    )

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   px-3 py-1
                                                   rounded-full
                                                   text-xs
                                                   font-semibold
                                                   bg-amber-50
                                                   text-amber-700">

                                            <i
                                                class="fas fa-minus-circle mr-1">
                                            </i>

                                            Absent

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-12
                                           text-center">

                                    <div
                                        class="flex
                                               flex-col
                                               items-center
                                               text-slate-400">

                                        <i
                                            class="fas fa-chart-line
                                                   text-4xl
                                                   mb-3">
                                        </i>

                                        <p
                                            class="font-medium
                                                   text-slate-500">

                                            Aucun frais à comparer.

                                        </p>

                                        <p
                                            class="text-sm
                                                   mt-1">

                                            Aucun frais ne correspond
                                            aux critères sélectionnés.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @else

        {{-- ===================================================== --}}
        {{-- ÉTAT INITIAL --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   p-12
                   text-center">

            <div
                class="flex
                       flex-col
                       items-center
                       text-slate-400">

                <i
                    class="fas fa-scale-balanced
                           text-5xl
                           mb-4">
                </i>

                <h2
                    class="text-lg
                           font-semibold
                           text-slate-600">

                    Comparer deux années scolaires

                </h2>

                <p
                    class="text-sm
                           text-slate-400
                           mt-2
                           max-w-lg">

                    Sélectionnez deux années scolaires,
                    puis utilisez les filtres disponibles
                    pour analyser l'évolution des frais.

                </p>

            </div>

        </div>

    @endif

</div>

@endsection
