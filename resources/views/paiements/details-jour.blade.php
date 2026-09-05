@extends('layouts.app')

@section('title', 'Détails des paiements')

@section('breadcrumb', 'Paiements / Détails du jour')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

@include('paiements.partials.navigation')

{{-- En-tête --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

    <div>

        <h1 class="text-2xl font-bold text-slate-700">
            Détails des paiements
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


{{-- Sélection de la date --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6 grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div>
        <form method="GET"
          action="{{ route('paiements.details-jour') }}"
          class="flex flex-col sm:flex-row sm:items-end gap-4">

        <div>

            <label for="date"
                   class="block text-sm font-medium text-slate-600 mb-2">
                Date à consulter
            </label>

            <input
                type="date"
                id="date"
                name="date"
                value="{{ $date }}"
                class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                required
            >

        </div>

        <button type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 font-medium text-white transition hover:bg-blue-700">
            Afficher
        </button>

    </form>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500">
            Total encaissé
        </p>

        <p class="text-2xl font-bold text-slate-800 mt-2">
            {{ number_format($totalJour, 2, ',', ' ') }} FC
        </p>

    </div>


    <div class="bg-white border border-slate-200 rounded-xl p-5">

        <p class="text-sm text-slate-500">
            Nombre de paiements
        </p>

        <p class="text-2xl font-bold text-slate-800 mt-2">
            {{ $nombrePaiements }}
        </p>

    </div>

</div>


{{-- Résumé --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">



</div>


{{-- Tableau des paiements --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Paiements du {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
        </h2>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50">

                <tr>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Référence
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Élève
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Section
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Motif
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Mois
                    </th>

                    <th class="text-right px-5 py-3 font-semibold text-slate-600">
                        Montant payé
                    </th>

                    <th class="text-left px-5 py-3 font-semibold text-slate-600">
                        Mode
                    </th>

                    <th class="text-right px-5 py-3 font-semibold text-slate-600">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($paiements as $paiement)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-5 py-4 font-medium text-slate-700">
                            {{ $paiement->reference }}
                        </td>


                        <td class="px-5 py-4">

                            @if($paiement->eleve)

                                {{ $paiement->eleve->nom }}
                                {{ $paiement->eleve->postnom }}
                                {{ $paiement->eleve->prenom }}

                            @else

                                <span class="text-slate-400">
                                    Élève supprimé
                                </span>

                            @endif

                        </td>


                        <td class="px-5 py-4">

                            {{ $paiement->inscription?->classe?->nom ?? '—' }}-{{ $paiement->inscription?->classe?->section ?? '—' }}

                        </td>


                        <td class="px-5 py-4">

                            {{ $paiement->motif }}

                        </td>


                        <td class="px-5 py-4">

                            {{ $paiement->mois ?? 'Pas disponible' }}

                        </td>


                        <td class="px-5 py-4 text-right font-semibold">

                            {{ number_format($paiement->montant_paye, 2, ',', ' ') }}
                            FC

                        </td>


                        <td class="px-5 py-4">

                            {{ $paiement->mode_paiement }}

                        </td>


                        {{-- ACTIONS --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center justify-end gap-2">

                                {{-- Modifier --}}
                                <a href="{{ route('paiements.edit', [
                                        'paiement' => $paiement->id,
                                        'date' => $date
                                    ]) }}"
                                title="Modifier le paiement"
                                aria-label="Modifier le paiement"
                                class="inline-flex items-center justify-center
                                        w-9 h-9 rounded-lg
                                        bg-blue-50 text-blue-600
                                        hover:bg-blue-100
                                        hover:text-blue-700
                                        transition duration-200
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-blue-300">

                                    {{-- Icône crayon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/>

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1-1-4 9.5-9.5z"/>

                                    </svg>

                                </a>

                                {{-- Annuler / supprimer --}}
                                <form action="{{ route('paiements.destroy', $paiement->id) }}"
                                      method="POST"
                                      class="form-annulation-paiement">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            title="Annuler le paiement"
                                            aria-label="Annuler le paiement"
                                            class="inline-flex items-center justify-center
                                                   w-9 h-9 rounded-lg
                                                   bg-red-50 text-red-600
                                                   hover:bg-red-100
                                                   hover:text-red-700
                                                   transition duration-200
                                                   focus:outline-none
                                                   focus:ring-2
                                                   focus:ring-red-300">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-5 h-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M6 7h12"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 7V4h6v3"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M10 11v6"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M14 11v6"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 7l1 13h6l1-13"/>

                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="px-5 py-10 text-center text-slate-500">

                            Aucun paiement enregistré pour cette date.

                        </td>

                    </tr>

                @endforelse

            </tbody>


            @if($paiements->count() > 0)

                <tfoot class="bg-slate-50 border-t border-slate-200">

                    <tr>

                        <td colspan="5"
                            class="px-5 py-4 text-right font-semibold text-slate-700">

                            Total

                        </td>

                        <td class="px-5 py-4 text-right font-bold text-slate-800">

                            {{ number_format($totalJour, 2, ',', ' ') }} FC

                        </td>

                        <td colspan="2"></td>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>

</div>
</div>

{{-- Confirmation de l'annulation --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('.form-annulation-paiement')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({

                    title: 'Annuler ce paiement ?',

                    text: 'Cette action supprimera définitivement ce paiement.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Oui, annuler',

                    cancelButtonText: 'Retour',

                    reverseButtons: true,

                    focusCancel: true,

                }).then(function (result) {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

        });

});

</script>

@endsection
