@extends('layouts.app')

@section('title', 'Dashboard des paiements')

@section('content')

<div class="max-w-7xl mx-auto py-8 px-4">

```
{{-- En-tête --}}
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

    <div class="flex flex-wrap gap-2">

        {{-- Historique complet --}}
        <a href="{{ route('paiements.index') }}"
           class="inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition">
            Historique des paiements
        </a>

    </div>

</div>


{{-- Aucun année scolaire active --}}
@if(!$anneeScolaireActive)

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-5">
        Aucune année scolaire active n'est actuellement définie.
    </div>

@else


    {{-- ==========================================================
         CARTES DES TOTAUX
    =========================================================== --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Aujourd'hui --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

            <p class="text-sm text-slate-500">
                Aujourd'hui
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalJour, 2, ',', ' ') }} FC
            </p>

            <p class="text-xs text-slate-400 mt-2">
                Paiements enregistrés aujourd'hui
            </p>

        </div>


        {{-- Cette semaine --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

            <p class="text-sm text-slate-500">
                Cette semaine
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalSemaine, 2, ',', ' ') }} FC
            </p>

            <p class="text-xs text-slate-400 mt-2">
                Total des paiements de la semaine
            </p>

        </div>


        {{-- Ce mois --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

            <p class="text-sm text-slate-500">
                Ce mois
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalMois, 2, ',', ' ') }} FC
            </p>

            <p class="text-xs text-slate-400 mt-2">
                Total des paiements du mois
            </p>

        </div>


        {{-- Année scolaire --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">

            <p class="text-sm text-slate-500">
                Année scolaire
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalAnnee, 2, ',', ' ') }} FC
            </p>

            <p class="text-xs text-slate-400 mt-2">
                Total depuis le début de l'année scolaire
            </p>

        </div>

    </div>


    {{-- ==========================================================
         TOTAL PAR SECTION
    =========================================================== --}}

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
                            Total payé
                        </th>

                        <th class="text-right px-6 py-3 font-semibold text-slate-600">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($totauxSections as $section => $total)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $section }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-slate-700">
                                {{ number_format($total, 2, ',', ' ') }} FC
                            </td>

                            <td class="px-6 py-4 text-right">

                                {{-- Nous brancherons plus tard le filtre par section --}}
                                <span class="text-xs text-slate-400">
                                    Détails bientôt disponibles
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="px-6 py-8 text-center text-slate-500">

                                Aucun paiement enregistré pour cette année scolaire.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ==========================================================
         ACTIONS RAPIDES
    =========================================================== --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">


        {{-- Paiements du jour --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <h3 class="font-semibold text-slate-700">
                Paiements du jour
            </h3>

            <p class="text-sm text-slate-500 mt-2 mb-4">
                Consulter les paiements enregistrés aujourd'hui.
            </p>

            {{-- Route à créer lors de l'étape suivante --}}
            <a href="{{ route('paiements.details-jour', ['date' => now()->format('Y-m-d')]) }}"
               class="inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-800 transition">
                Voir les détails
            </a>

        </div>


        {{-- Rapport quotidien --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <h3 class="font-semibold text-slate-700">
                Rapport quotidien
            </h3>

            <p class="text-sm text-slate-500 mt-2 mb-4">
                Télécharger le rapport des paiements du jour.
            </p>

            <button type="button"
                    disabled
                    class="px-4 py-2 bg-slate-200 text-slate-400 rounded-lg cursor-not-allowed">
                PDF / Excel bientôt disponible
            </button>

        </div>


        {{-- Rapports période --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5">

            <h3 class="font-semibold text-slate-700">
                Rapports
            </h3>

            <p class="text-sm text-slate-500 mt-2 mb-4">
                Rapports de la semaine et du mois.
            </p>

            <button type="button"
                    disabled
                    class="px-4 py-2 bg-slate-200 text-slate-400 rounded-lg cursor-not-allowed">
                Bientôt disponible
            </button>

        </div>

    </div>


    {{-- ==========================================================
         LIEN HISTORIQUE
    =========================================================== --}}

    <div class="flex justify-center">

        <a href="{{ route('paiements.index') }}"
           class="text-sm font-medium text-slate-600 hover:text-slate-900 underline">
            Consulter l'historique complet des paiements →
        </a>

    </div>

@endif

</div>

@endsection
