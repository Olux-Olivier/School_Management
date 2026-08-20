@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4 mb-8">

        <div>

            <h1 class="text-2xl font-bold text-slate-800">

                Dashboard des frais

            </h1>

            <p class="text-sm text-slate-500 mt-1">

                Année scolaire active :

                <span class="font-semibold text-blue-600">

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

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTIQUES --}}
    {{-- ========================================================= --}}

    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               lg:grid-cols-4
               gap-5
               mb-8">


        {{-- NOMBRE DE FRAIS --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">

                        Frais configurés

                    </p>

                    <p
                        class="text-3xl
                               font-bold
                               text-slate-800
                               mt-2">

                        {{ $nombreFrais }}

                    </p>

                </div>

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-blue-50
                           text-blue-600
                           flex
                           items-center
                           justify-center">

                    <i class="fas fa-file-invoice-dollar text-xl"></i>

                </div>

            </div>

        </div>


        {{-- CLASSES --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">

                        Classes concernées

                    </p>

                    <p
                        class="text-3xl
                               font-bold
                               text-slate-800
                               mt-2">

                        {{ $nombreClasses }}

                    </p>

                </div>

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-green-50
                           text-green-600
                           flex
                           items-center
                           justify-center">

                    <i class="fas fa-school text-xl"></i>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">

                        Total configuré

                    </p>

                    <p
                        class="text-2xl
                               font-bold
                               text-slate-800
                               mt-2">

                        {{ number_format(
                            $montantTotal,
                            0,
                            ',',
                            ' '
                        ) }}

                        FC

                    </p>

                </div>

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-purple-50
                           text-purple-600
                           flex
                           items-center
                           justify-center">

                    <i class="fas fa-coins text-xl"></i>

                </div>

            </div>

        </div>


        {{-- MOYENNE --}}

        <div
            class="bg-white
                   rounded-xl
                   border border-slate-200
                   shadow-sm
                   p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">

                        Montant moyen

                    </p>

                    <p
                        class="text-2xl
                               font-bold
                               text-slate-800
                               mt-2">

                        {{ number_format(
                            $montantMoyen,
                            0,
                            ',',
                            ' '
                        ) }}

                        FC

                    </p>

                </div>

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-amber-50
                           text-amber-600
                           flex
                           items-center
                           justify-center">

                    <i class="fas fa-chart-line text-xl"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- GESTION --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <h2
            class="text-lg
                   font-semibold
                   text-slate-800
                   mb-4">

            Gestion des frais

        </h2>


        <div
            class="grid grid-cols-1
                   md:grid-cols-3
                   gap-5">


            {{-- CONSULTER --}}

            <a
                href="{{ route('frais.index') }}"

                class="group
                       bg-white
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:border-blue-300
                       hover:shadow-md
                       transition">

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-blue-50
                           text-blue-600
                           flex
                           items-center
                           justify-center
                           mb-4">

                    <i class="fas fa-list text-xl"></i>

                </div>

                <h3
                    class="font-semibold
                           text-slate-800">

                    Frais de l'année active

                </h3>

                <p
                    class="text-sm
                           text-slate-500
                           mt-2">

                    Consulter et gérer les frais
                    actuellement applicables.

                </p>

            </a>


            {{-- AJOUTER --}}

            <a
                href="{{ route('frais.create') }}"

                class="group
                       bg-white
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:border-green-300
                       hover:shadow-md
                       transition">

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-green-50
                           text-green-600
                           flex
                           items-center
                           justify-center
                           mb-4">

                    <i class="fas fa-plus text-xl"></i>

                </div>

                <h3
                    class="font-semibold
                           text-slate-800">

                    Ajouter un frais

                </h3>

                <p
                    class="text-sm
                           text-slate-500
                           mt-2">

                    Ajouter un nouveau frais
                    pour une classe.

                </p>

            </a>


            {{-- HISTORIQUE --}}

            <a
                href="{{ route('frais.historique') }}"

                class="group
                       bg-white
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:border-purple-300
                       hover:shadow-md
                       transition">

                <div
                    class="w-12 h-12
                           rounded-lg
                           bg-purple-50
                           text-purple-600
                           flex
                           items-center
                           justify-center
                           mb-4">

                    <i class="fas fa-history text-xl"></i>

                </div>

                <h3
                    class="font-semibold
                           text-slate-800">

                    Historique des frais

                </h3>

                <p
                    class="text-sm
                           text-slate-500
                           mt-2">

                    Consulter les frais des
                    différentes années scolaires.

                </p>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ANALYSE --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <h2
            class="text-lg
                   font-semibold
                   text-slate-800
                   mb-4">

            Analyse et évolution

        </h2>


        <div
            class="grid grid-cols-1
                   md:grid-cols-2
                   gap-5">


            {{-- EVOLUTION --}}

            <a
                href="{{ route('frais.evolution') }}"

                class="bg-white
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:border-amber-300
                       hover:shadow-md
                       transition">

                <div class="flex items-start gap-4">

                    <div
                        class="w-12 h-12
                               shrink-0
                               rounded-lg
                               bg-amber-50
                               text-amber-600
                               flex
                               items-center
                               justify-center">

                        <i class="fas fa-chart-line text-xl"></i>

                    </div>

                    <div>

                        <h3
                            class="font-semibold
                                   text-slate-800">

                            Évolution des frais

                        </h3>

                        <p
                            class="text-sm
                                   text-slate-500
                                   mt-2">

                            Visualiser l'évolution des montants
                            au fil des années scolaires.

                        </p>

                    </div>

                </div>

            </a>


            {{-- COMPARAISON --}}

            <a
                href="{{ route('frais.comparaison') }}"

                class="bg-white
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:border-red-300
                       hover:shadow-md
                       transition">

                <div class="flex items-start gap-4">

                    <div
                        class="w-12 h-12
                               shrink-0
                               rounded-lg
                               bg-red-50
                               text-red-600
                               flex
                               items-center
                               justify-center">

                        <i class="fas fa-code-compare text-xl"></i>

                    </div>

                    <div>

                        <h3
                            class="font-semibold
                                   text-slate-800">

                            Comparer deux années

                        </h3>

                        <p
                            class="text-sm
                                   text-slate-500
                                   mt-2">

                            Comparer les frais selon la classe,
                            la section, l'option et le montant.

                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DERNIERS FRAIS --}}
    {{-- ========================================================= --}}

    <div
        class="bg-white
               rounded-xl
               border border-slate-200
               shadow-sm
               overflow-hidden">

        <div
            class="px-6 py-5
                   border-b border-slate-200
                   flex
                   items-center
                   justify-between">

            <div>

                <h2
                    class="font-semibold
                           text-slate-800">

                    Derniers frais ajoutés

                </h2>

                <p
                    class="text-sm
                           text-slate-500
                           mt-1">

                    Les cinq derniers frais de l'année active.

                </p>

            </div>


            <a
                href="{{ route('frais.index') }}"

                class="text-sm
                       font-medium
                       text-blue-600
                       hover:text-blue-700">

                Voir tous

            </a>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th
                            class="px-6 py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Frais

                        </th>

                        <th
                            class="px-6 py-3
                                   text-left
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Classe

                        </th>

                        <th
                            class="px-6 py-3
                                   text-right
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Montant

                        </th>

                        <th
                            class="px-6 py-3
                                   text-right
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   uppercase">

                            Action

                        </th>

                    </tr>

                </thead>


                <tbody
                    class="divide-y
                           divide-slate-100">

                    @forelse($derniersFrais as $fraisItem)

                        <tr class="hover:bg-slate-50">


                            <td class="px-6 py-4">

                                <span
                                    class="font-medium
                                           text-slate-700">

                                    {{ $fraisItem->intitule }}

                                </span>

                            </td>


                            <td class="px-6 py-4">

                                <div
                                    class="font-medium
                                           text-slate-700">

                                    {{ $fraisItem->classe->nom }}

                                </div>

                                @if($fraisItem->classe->option)

                                    <div
                                        class="text-xs
                                               text-slate-500">

                                        {{ $fraisItem->classe->option }}

                                    </div>

                                @endif

                            </td>


                            <td
                                class="px-6 py-4
                                       text-right
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


                            <td
                                class="px-6 py-4
                                       text-right">

                                <a
                                    href="{{ route(
                                        'frais.edit',
                                        $fraisItem
                                    ) }}"

                                    class="text-amber-600
                                           hover:text-amber-700">

                                    <i class="fas fa-edit"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10
                                       text-center
                                       text-slate-500">

                                Aucun frais enregistré
                                pour l'année active.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
