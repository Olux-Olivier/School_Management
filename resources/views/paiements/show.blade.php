@extends('layouts.app')

@section('title', 'Historique des paiements')
@section('breadcrumb')
    Paiements / Historique de l’élève
@endsection
@section('content')

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

    @include('paiements.partials.navigation')

    {{-- ================================================================
         EN-TÊTE
    ================================================================= --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-700">
                    Historique des paiements
                </h1>

            </div>

            <p class="text-sm text-slate-500 mt-1">
                Consultation des paiements de l'élève.
            </p>

        </div>


        {{-- NOUVEAU PAIEMENT --}}
        <a href="{{ route('paiements.create', ['eleve' => $eleve->id,'annee_scolaire_id' => request('annee_scolaire_id')]) }}" class="inline-flex items-center  px-5 py-2.5  gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Nouveau paiement
        </a>

    </div>

    {{-- ================================================================
     INFORMATIONS ÉLÈVE + RÉSUMÉ FINANCIER
================================================================= --}}

<div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Matricule
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Élève
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Classe
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Année scolaire
                    </th>

                    <th class="text-right px-5 py-3 font-semibold text-slate-600">
                        Total dû
                    </th>

                    <th class="text-right px-5 py-3 font-semibold text-slate-600">
                        Total payé
                    </th>

                    <th class="text-right px-5 py-3 font-semibold text-slate-600">
                        Restant
                    </th>
                </tr>
            </thead>

            <tbody>

                <tr class="hover:bg-slate-50 transition">

                    {{-- MATRICULE --}}
                    <td class="px-5 py-4 font-medium text-slate-700">
                        {{ $eleve->matricule }}
                    </td>

                    {{-- ÉLÈVE --}}
                    <td class="px-5 py-4 font-semibold text-slate-700">
                        {{ $eleve->nom }}
                        {{ $eleve->postnom }}
                        {{ $eleve->prenom }}
                    </td>

                    {{-- CLASSE --}}
                    <td class="px-5 py-4 text-slate-700">

                        @if($inscription)

                            {{ $inscription->classe->nom }}

                            @if($inscription->classe->option)
                                — {{ $inscription->classe->option }}
                            @endif

                        @else

                            <span class="text-slate-400">
                                Aucune inscription
                            </span>

                        @endif

                    </td>

                    {{-- ANNÉE SCOLAIRE --}}
                    <td class="px-5 py-4 font-medium text-indigo-600">

                        {{ $anneeScolaire->libelle
                            ?? $anneeScolaire->nom
                            ?? $anneeScolaire->date_debut . ' - ' . $anneeScolaire->date_fin
                        }}

                    </td>

                    {{-- TOTAL DÛ --}}
                    <td class="px-5 py-4 text-right font-semibold text-slate-700 whitespace-nowrap">

                        {{ number_format($totalMontantDu, 0, ',', ' ') }}
                        <span class="text-slate-400 font-normal">FC</span>

                    </td>

                    {{-- TOTAL PAYÉ --}}
                    <td class="px-5 py-4 text-right font-semibold text-emerald-600 whitespace-nowrap">

                        {{ number_format($totalPaye, 0, ',', ' ') }}
                        <span class="text-slate-400 font-normal">FC</span>

                    </td>

                    {{-- RESTANT --}}
                    <td class="px-5 py-4 text-right font-semibold whitespace-nowrap">

                        @if($totalRestant > 0)

                            <span class="text-amber-600">
                                {{ number_format($totalRestant, 0, ',', ' ') }} FC
                            </span>

                        @else

                            <span class="text-emerald-600">
                                0 FC
                            </span>

                        @endif

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>
    


    {{-- ================================================================
         CHANGEMENT D'ANNÉE
    ================================================================= --}}

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">

        <div class="bottom-4 mb-4 border-b border-slate-200 pb-3">
            <h2 class="font-semibold text-slate-700">
                Historique des paiements
            </h2>
        </div>


        <form
            method="GET"
            action="{{ route('paiements.show', $eleve) }}"
        >

            <label
                for="annee_scolaire_id"
                class="block text-sm font-medium text-slate-700 mb-2"
            >
                Consulter une autre année scolaire
            </label>

            <div class="flex flex-col md:flex-row gap-3">

                <select
                    name="annee_scolaire_id"
                    id="annee_scolaire_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 md:w-80"
                >

                    @foreach($anneesScolaires as $annee)

                        <option
                            value="{{ $annee->id }}"
                            {{ (string) $anneeScolaireId === (string) $annee->id ? 'selected' : '' }}
                        >

                            {{ $annee->libelle
                                ?? $annee->nom
                                ?? $annee->date_debut . ' - ' . $annee->date_fin
                            }}

                        </option>

                    @endforeach

                </select>


                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2.5 font-medium text-white transition hover:bg-blue-700"
                >
                    Consulter
                </button>

            </div>

        </form>


        @if($paiements->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Référence
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Frais
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Mois
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Date
                            </th>

                            <th class="text-right px-5 py-3 font-semibold text-slate-600">
                                Montant dû
                            </th>

                            <th class="text-right px-5 py-3 font-semibold text-slate-600">
                                Payé
                            </th>

                            <th class="text-right px-5 py-3 font-semibold text-slate-600">
                                Restant
                            </th>

                            <th class="text-left px-5 py-3 font-semibold text-slate-600">
                                Mode
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($paiements as $paiement)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- RÉFÉRENCE --}}

                                <td class="px-5 py-4">

                                    <span class="font-medium text-indigo-600">
                                        {{ $paiement->reference }}
                                    </span>

                                </td>


                                {{-- FRAIS --}}

                                <td class="px-5 py-4 text-slate-700">

                                    {{ $paiement->frais->intitule }}

                                </td>


                                {{-- MOIS --}}

                                <td class="px-5 py-4 text-slate-600">

                                    {{ $paiement->mois }}

                                </td>


                                {{-- DATE --}}

                                <td class="px-5 py-4 text-slate-500">

                                    {{ $paiement->date_paiement?->format('d/m/Y') }}

                                </td>


                                {{-- DÛ --}}

                                <td class="px-5 py-4 text-right">

                                    {{ number_format($paiement->montant_du, 0, ',', ' ') }}
                                    FC

                                </td>


                                {{-- PAYÉ --}}

                                <td class="px-5 py-4 text-right font-medium text-emerald-600">

                                    {{ number_format($paiement->montant_paye, 0, ',', ' ') }}
                                    FC

                                </td>


                                {{-- RESTANT --}}

                                <td class="px-5 py-4 text-right font-medium">

                                    @if($paiement->restant > 0)

                                        <span class="text-amber-600">

                                            {{ number_format($paiement->restant, 0, ',', ' ') }}
                                            FC

                                        </span>

                                    @else

                                        <span class="text-emerald-600">
                                            0 FC
                                        </span>

                                    @endif

                                </td>


                                {{-- MODE --}}

                                <td class="px-5 py-4">

                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">

                                        {{ ucfirst($paiement->mode_paiement) }}

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            <div class="px-5 py-4 border-t border-slate-200">

                {{ $paiements->links() }}

            </div>

        @else

            <div class="px-5 py-12 text-center">

                <div class="text-4xl mb-3 text-slate-300">
                    0
                </div>

                <h3 class="font-semibold text-slate-600">
                    Aucun paiement
                </h3>

                <p class="text-sm text-slate-400 mt-1">
                    Aucun paiement n'a encore été enregistré pour cette année scolaire.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
