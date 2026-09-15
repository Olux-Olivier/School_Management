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


{{-- Sélection de la date + résumé --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Sélection date --}}
        <div>

            <form method="GET"
                  action="{{ route('paiements.details-jour') }}"
                  class="flex flex-col gap-4">

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
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        required
                    >

                </div>

                <div class="flex flex-wrap gap-2">

                    {{-- Afficher --}}
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-xl bg-blue-600 px-5 py-2.5
                                   font-medium text-white
                                   transition hover:bg-blue-700">

                        <i class="fas fa-search"></i>

                        Afficher

                    </button>


                    {{-- PDF --}}
                    <a href="{{ route('paiements.rapport-quotidien.pdf', ['date' => $date]) }}"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2
                              rounded-xl bg-red-600 px-5 py-2.5
                              font-medium text-white
                              transition hover:bg-red-700">

                        <i class="fas fa-file-pdf"></i>

                        PDF

                    </a>


                    {{-- Excel --}}
                    <a href="{{ route('paiements.rapport-quotidien.excel', ['date' => $date]) }}"
                       class="inline-flex items-center justify-center gap-2
                              rounded-xl bg-emerald-600 px-5 py-2.5
                              font-medium text-white
                              transition hover:bg-emerald-700">

                        <i class="fas fa-file-excel"></i>

                        Excel

                    </a>

                </div>

            </form>

        </div>


        {{-- Total encaissé --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500">
                Total encaissé
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalJour, 0, ',', ' ') }} FC
            </p>

        </div>


        {{-- Nombre de versements --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">

            <p class="text-sm text-slate-500">
                Nombre de versements
            </p>

            <p class="text-2xl font-bold text-slate-800 mt-2">
                {{ $nombrePaiements }}
            </p>

        </div>

    </div>

</div>


{{-- Tableau des paiements --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Versements du {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Liste des versements réellement encaissés à cette date.
        </p>

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
                        Versement
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

                    @php
                        /*
                         * $paiement représente ici un HistoriquePaiement.
                         *
                         * Le Paiement cumulatif est accessible via :
                         * $paiement->paiement
                         */

                        $paiementPrincipal = $paiement->paiement;

                        $eleve = $paiementPrincipal?->eleve;

                        $inscription = $paiement->inscription;

                        $classe = $inscription?->classe;

                        $section = match ((int) ($classe?->niveau ?? -1)) {
                            0 => 'Maternelle',
                            1 => 'Primaire',
                            2 => 'Secondaire',
                            3 => 'Humanités',
                            default => '—',
                        };
                    @endphp


                    <tr class="hover:bg-slate-50 transition">

                        {{-- Référence du versement --}}
                        <td class="px-5 py-4">

                            <span class="font-semibold text-slate-700">
                                {{ $paiement->reference ?? '—' }}
                            </span>

                        </td>


                        {{-- Élève --}}
                        <td class="px-5 py-4">

                            @if($eleve)

                                <div class="font-medium text-slate-700">
                                    {{ $eleve->nom }}
                                    {{ $eleve->postnom }}
                                </div>

                                @if($eleve->prenom)
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $eleve->prenom }}
                                    </div>
                                @endif

                                @if($eleve->matricule)
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ $eleve->matricule }}
                                    </div>
                                @endif

                            @else

                                <span class="text-slate-400">
                                    Élève supprimé
                                </span>

                            @endif

                        </td>


                        {{-- Section / classe --}}
                        <td class="px-5 py-4">

                            @if($classe)

                                <div class="font-medium text-slate-700">
                                    {{ $classe->nom }}

                                    @if($classe->option)
                                        {{ $classe->option }}
                                    @endif
                                </div>

                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $section }}
                                </div>

                            @else

                                <span class="text-slate-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Motif --}}
                        <td class="px-5 py-4">

                            <span class="text-slate-700">
                                {{ $paiementPrincipal?->motif ?? '—' }}
                            </span>

                        </td>


                        {{-- Mois --}}
                        <td class="px-5 py-4">

                            @if($paiementPrincipal?->mois === 'Pas disponible')
                                <span class="text-slate-400">
                                    Pas disponible
                                </span>
                            @else
                                {{ $paiementPrincipal?->mois ?? 'Pas disponible' }}
                            @endif

                        </td>


                        {{-- Montant du versement --}}
                        <td class="px-5 py-4 text-right">

                            <span class="font-bold text-slate-800">
                                {{ number_format($paiement->montant, 0, ',', ' ') }}
                                FC
                            </span>

                        </td>


                        {{-- Mode de paiement --}}
                        <td class="px-5 py-4">

                            @php
                                $mode = $paiement->mode_paiement;
                            @endphp

                            @if($mode === 'Espèces')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    Espèces
                                </span>

                            @elseif($mode === 'Mobile Money')
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    Mobile Money
                                </span>

                            @elseif($mode === 'Virement')
                                <span class="inline-flex items-center rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">
                                    Virement
                                </span>

                            @elseif($mode === 'Chèque')
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    Chèque
                                </span>

                            @else
                                <span class="text-slate-500">
                                    {{ $mode ?? '—' }}
                                </span>
                            @endif

                        </td>


                        {{-- Actions --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center justify-end gap-2">

                                {{-- Modifier ce versement --}}
                                @if($paiementPrincipal)

                                    <a
                                        href="{{ route('paiements.edit', [
                                            'paiement' => $paiementPrincipal->id,
                                            'historique_id' => $paiement->id,
                                            'date' => $date
                                        ]) }}"
                                        title="Modifier ce versement"
                                        aria-label="Modifier ce versement"
                                        class="inline-flex items-center justify-center
                                               w-9 h-9 rounded-lg
                                               bg-blue-50 text-blue-600
                                               hover:bg-blue-100
                                               hover:text-blue-700
                                               transition duration-200
                                               focus:outline-none
                                               focus:ring-2
                                               focus:ring-blue-300">

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

                                @endif


                                {{-- Reçu --}}
                                <a
                                    href="{{ route('paiements.recu', $paiement->id) }}"
                                    target="_blank"
                                    title="Voir le reçu"
                                    aria-label="Voir le reçu"
                                    class="inline-flex items-center justify-center
                                           w-9 h-9 rounded-lg
                                           bg-emerald-50 text-emerald-600
                                           hover:bg-emerald-100
                                           hover:text-emerald-700
                                           transition duration-200
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-emerald-300">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M9 14l2 2 4-4"/>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M7 3h8l4 4v14H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M15 3v5h5"/>

                                    </svg>

                                </a>

                                {{-- Annuler versement --}}
                                <form
                                    action="{{ route('paiements.versement.annuler', $paiement->id) }}"
                                    method="POST"
                                    class="form-annulation-versement inline-block"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center px-3 py-2
                                            bg-red-600 hover:bg-red-700
                                            text-white text-sm font-medium rounded-lg
                                            transition"
                                        title="Annuler ce versement"
                                    >
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="px-5 py-10 text-center">

                            <div class="flex flex-col items-center justify-center">

                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-6 h-6 text-slate-400"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M12 8v4l3 3"/>

                                        <circle cx="12"
                                                cy="12"
                                                r="9"/>

                                    </svg>

                                </div>

                                <p class="font-medium text-slate-600">
                                    Aucun versement enregistré
                                </p>

                                <p class="text-sm text-slate-400 mt-1">
                                    Aucun paiement n'a été enregistré pour cette date.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>


            {{-- Total --}}
            @if($paiements->count() > 0)

                <tfoot class="bg-slate-50 border-t border-slate-200">

                    <tr>

                        <td colspan="5"
                            class="px-5 py-4 text-right font-semibold text-slate-700">

                            Total encaissé

                        </td>

                        <td class="px-5 py-4 text-right font-bold text-slate-800">

                            {{ number_format($totalJour, 0, ',', ' ') }} FC

                        </td>

                        <td colspan="2"></td>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>


    {{-- Pagination --}}
    @if($paiements->hasPages())

        <div class="px-6 py-4 border-t border-slate-200">

            {{ $paiements->links() }}

        </div>

    @endif

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.form-annulation-versement')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                event.preventDefault();

                Swal.fire({
                    title: 'Annuler ce versement ?',
                    text: 'Ce versement sera supprimé et le montant payé sera recalculé. Cette action est irréversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Oui, annuler',
                    cancelButtonText: 'Non, conserver',
                    reverseButtons: true
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
