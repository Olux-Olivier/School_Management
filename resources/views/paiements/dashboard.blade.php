
@extends('layouts.app')

@section('title', 'Dashboard des paiements')

@section('breadcrumb', 'Paiements / Tableau de bord')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

    @include('paiements.partials.navigation')


    {{-- ==========================================================
         EN-TÊTE
    =========================================================== --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-2xl font-bold text-slate-700">
                Dashboard des paiements
            </h1>

            @if($anneeScolaireActive)

                <p class="text-sm text-slate-500 mt-1">

                    Année scolaire :

                    <span class="font-semibold text-slate-700">
                        {{ $anneeScolaireActive->libelle ?? $anneeScolaireActive->nom }}
                    </span>

                </p>

            @endif

        </div>

    </div>


    {{-- ==========================================================
         AUCUNE ANNÉE SCOLAIRE ACTIVE
    =========================================================== --}}

    @if(!$anneeScolaireActive)

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-5">

            Aucune année scolaire active n'est actuellement définie.

        </div>

    @else


        {{-- ======================================================
             CARTES DES TOTAUX
        ======================================================= --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">


            {{-- Aujourd'hui --}}

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Aujourd'hui
                        </p>

                        <p class="text-2xl font-bold text-slate-800 mt-2">

                            {{ number_format($totalJour, 0, ',', ' ') }}

                            <span class="text-sm font-semibold">
                                FC
                            </span>

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-3">
                    Versements réellement encaissés aujourd'hui
                </p>

            </div>


            {{-- Cette semaine --}}

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Cette semaine
                        </p>

                        <p class="text-2xl font-bold text-slate-800 mt-2">

                            {{ number_format($totalSemaine, 0, ',', ' ') }}

                            <span class="text-sm font-semibold">
                                FC
                            </span>

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-3">
                    Total des versements de la semaine
                </p>

            </div>


            {{-- Ce mois --}}

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Ce mois
                        </p>

                        <p class="text-2xl font-bold text-slate-800 mt-2">

                            {{ number_format($totalMois, 0, ',', ' ') }}

                            <span class="text-sm font-semibold">
                                FC
                            </span>

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-emerald-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3v18h18M7 16l4-5 3 3 5-7"
                            />
                        </svg>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-3">
                    Total des versements du mois
                </p>

            </div>


            {{-- Année scolaire --}}

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-slate-500">
                            Année scolaire
                        </p>

                        <p class="text-2xl font-bold text-slate-800 mt-2">

                            {{ number_format($totalAnnee, 0, ',', ' ') }}

                            <span class="text-sm font-semibold">
                                FC
                            </span>

                        </p>

                    </div>

                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

                <p class="text-xs text-slate-400 mt-3">
                    Total réellement encaissé depuis le début
                </p>

            </div>

        </div>



        {{-- ======================================================
             TOTAL PAR SECTION
        ======================================================= --}}

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-700">
                    Paiements par section
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Total réellement encaissé pendant l'année scolaire active.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="text-left px-6 py-3 font-semibold text-slate-600">
                                Section
                            </th>

                            <th class="text-right px-6 py-3 font-semibold text-slate-600">
                                Total encaissé
                            </th>

                            <th class="text-right px-6 py-3 font-semibold text-slate-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($totauxSections as $section => $total)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-6 py-4">

                                    <span class="font-medium text-slate-700">
                                        {{ $section }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <span class="font-semibold text-slate-700">

                                        {{ number_format($total, 0, ',', ' ') }}

                                        FC

                                    </span>

                                </td>


                                <td class="px-6 py-4 text-right">

                                    @php
                                        $sectionFiltre = match (strtolower(trim($section))) {
                                            'maternelle' => 'maternelle',
                                            'primaire' => 'primaire',
                                            'secondaire' => 'secondaire',
                                            'humanités', 'humanites' => 'humanites',
                                            default => null,
                                        };
                                    @endphp

                                    @if($sectionFiltre)

                                        <a
                                            href="{{ route('paiements.index', [
                                                'annee_scolaire_id' => $anneeScolaireActive->id,
                                                'section' => $sectionFiltre,
                                            ]) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200"
                                        >

                                            <i class="fas fa-eye"></i>

                                            Voir les détails

                                        </a>

                                    @else

                                        <span class="text-xs text-slate-400">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-6 py-8 text-center text-slate-500"
                                >

                                    Aucun versement enregistré pour cette année scolaire.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- ======================================================
             ACTIONS RAPIDES
        ======================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">


            {{-- Paiements du jour --}}

            <div class="bg-white border border-slate-200 rounded-xl p-5">

                <div class="flex items-center gap-3 mb-2">

                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-slate-700">
                        Paiements du jour
                    </h3>

                </div>


                <p class="text-sm text-slate-500 mt-2 mb-4">

                    Consulter les versements enregistrés aujourd'hui.

                </p>


                <a
                    href="{{ route('paiements.details-jour', ['date' => now()->format('Y-m-d')]) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                >

                    Voir les détails

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                </a>

            </div>



            {{-- Rapport quotidien --}}

            <div class="bg-white border border-slate-200 rounded-xl p-5">

                <div class="flex items-center gap-3 mb-2">

                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-slate-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-slate-700">
                        Rapport quotidien
                    </h3>

                </div>


                <p class="text-sm text-slate-500 mt-2 mb-4">

                    Télécharger le rapport des versements du jour.

                </p>


                <div class="flex flex-wrap gap-3 mt-4">

                    <a href="{{ route('paiements.rapport-quotidien.pdf') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">

                        <i class="fa-solid fa-file-pdf"></i>
                        Télécharger PDF
                    </a>

                    <a href="{{ route('paiements.rapport-quotidien.excel') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">

                        <i class="fa-solid fa-file-excel"></i>
                        Télécharger Excel
                    </a>

                </div>

            </div>



            {{-- Rapports période --}}

            <div class="bg-white border border-slate-200 rounded-xl p-5">

                <div class="flex items-center gap-3 mb-2">

                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">

                        <svg
                            class="w-5 h-5 text-slate-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2 2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"
                            />
                        </svg>

                    </div>

                    <h3 class="font-semibold text-slate-700">
                        Rapports
                    </h3>

                </div>


                <p class="text-sm text-slate-500 mt-2 mb-4">

                    Rapports de la semaine et du mois.

                </p>


                <button
                    type="button"
                    disabled
                    class="px-4 py-2 bg-slate-200 text-slate-400 rounded-lg cursor-not-allowed"
                >

                    Bientôt disponible

                </button>

            </div>

        </div>



        {{-- ======================================================
             LIEN HISTORIQUE
        ======================================================= --}}

        <div class="flex justify-center">

            <a
                href="{{ route('paiements.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 underline"
            >

                Consulter l'historique complet des paiements

                <span>→</span>

            </a>

        </div>


    @endif

</div>

@endsection
