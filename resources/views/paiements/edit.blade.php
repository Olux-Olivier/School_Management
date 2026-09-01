@extends('layouts.app')

@section('title', 'Modifier le paiement')

@section('content')

<div class="max-w-4xl mx-auto py-8 px-4">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M11 5h6m-6 4h6m-9 4h9m-9 4h6M5 5h.01M5 9h.01M5 13h.01M5 17h.01"/>

                    </svg>

                </div>

                <div>

                    <h1 class="text-2xl font-bold text-slate-700">
                        Modifier le paiement
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Référence :
                        <span class="font-semibold text-slate-700">
                            {{ $paiement->reference }}
                        </span>
                    </p>

                </div>

            </div>

        </div>


        <a href="{{ route('paiements.show', [
                'eleve' => $paiement->eleve_id,
                'annee_scolaire_id' => $paiement->annee_scolaire_id
            ]) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5
                  bg-slate-100 text-slate-700 rounded-xl
                  hover:bg-slate-200 transition">

            ← Retour

        </a>

    </div>


    {{-- Message d'erreur général --}}
    @if(session('error'))

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">

            {{ session('error') }}

        </div>

    @endif


    {{-- Erreurs de validation --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <p class="font-semibold text-red-700 mb-2">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Informations du paiement --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-6">

        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Informations du paiement
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Ces informations ne peuvent pas être modifiées.
            </p>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Élève --}}
                <div>

                    <label class="block text-sm font-medium text-slate-500 mb-2">
                        Élève
                    </label>

                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700">

                        {{ $paiement->eleve->nom }}
                        {{ $paiement->eleve->postnom }}
                        {{ $paiement->eleve->prenom }}

                    </div>

                </div>


                {{-- Référence --}}
                <div>

                    <label class="block text-sm font-medium text-slate-500 mb-2">
                        Référence
                    </label>

                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700">

                        {{ $paiement->reference }}

                    </div>

                </div>


                {{-- Motif --}}
                <div>

                    <label class="block text-sm font-medium text-slate-500 mb-2">
                        Motif
                    </label>

                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700">

                        {{ $paiement->motif }}

                    </div>

                </div>


                {{-- Montant dû --}}
                <div>

                    <label class="block text-sm font-medium text-slate-500 mb-2">
                        Montant dû
                    </label>

                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700">

                        {{ number_format($paiement->montant_du, 2, ',', ' ') }}
                        FC

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Formulaire --}}
    <form action="{{ route('paiements.update', $paiement->id) }}"
          method="POST"
          class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        @csrf
        @method('PUT')


        <div class="px-6 py-5 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Modifier les informations
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Modifiez uniquement les éléments nécessaires.
            </p>

        </div>


        <div class="p-6 space-y-6">


            {{-- Mois --}}
            @if($estMinerval)

                <div>

                    <label for="mois"
                           class="block text-sm font-medium text-slate-600 mb-2">

                        Mois du minerval

                    </label>

                    <select id="mois"
                            name="mois"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">

                        <option value="">
                            Sélectionner le mois
                        </option>

                        @foreach([
                            'Janvier',
                            'Février',
                            'Mars',
                            'Avril',
                            'Mai',
                            'Juin',
                            'Juillet',
                            'Août',
                            'Septembre',
                            'Octobre',
                            'Novembre',
                            'Décembre'
                        ] as $mois)

                            <option value="{{ $mois }}"
                                @selected(old('mois', $paiement->mois) === $mois)>

                                {{ $mois }}

                            </option>

                        @endforeach

                    </select>

                    @error('mois')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            @else

                <input type="hidden"
                       name="mois"
                       value="Pas disponible">

            @endif


            {{-- Montant payé --}}
            <div>

                <label for="montant_paye"
                       class="block text-sm font-medium text-slate-600 mb-2">

                    Montant payé

                </label>

                <div class="relative">

                    <input type="number"
                           id="montant_paye"
                           name="montant_paye"
                           value="{{ old('montant_paye', $paiement->montant_paye) }}"
                           min="1"
                           max="{{ $paiement->montant_du }}"
                           step="0.01"
                           required
                           class="w-full border border-slate-300 rounded-xl px-4 py-3 pr-14
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">

                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">
                        FC
                    </span>

                </div>

                <p class="text-xs text-slate-500 mt-2">
                    Montant maximum :
                    <strong>
                        {{ number_format($paiement->montant_du, 2, ',', ' ') }} FC
                    </strong>
                </p>

                @error('montant_paye')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Date --}}
            <div>

                <label for="date_paiement"
                       class="block text-sm font-medium text-slate-600 mb-2">

                    Date du paiement

                </label>

                <input type="date"
                       id="date_paiement"
                       name="date_paiement"
                       value="{{ old(
                           'date_paiement',
                           optional($paiement->date_paiement)->format('Y-m-d')
                       ) }}"
                       required
                       class="w-full border border-slate-300 rounded-xl px-4 py-3
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">

                @error('date_paiement')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Mode de paiement --}}
            <div>

                <label for="mode_paiement"
                       class="block text-sm font-medium text-slate-600 mb-2">

                    Mode de paiement

                </label>

                <select id="mode_paiement"
                        name="mode_paiement"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">

                    @foreach($modesPaiement as $mode)

                        <option value="{{ $mode }}"
                            @selected(old('mode_paiement', $paiement->mode_paiement) === $mode)>

                            {{ $mode }}

                        </option>

                    @endforeach

                </select>

                @error('mode_paiement')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Aperçu restant --}}
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-5">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <p class="text-sm text-slate-500">
                            Nouveau restant
                        </p>

                        <p id="restant-preview"
                           class="text-2xl font-bold text-slate-700 mt-1">

                            {{ number_format(
                                $paiement->restant,
                                2,
                                ',',
                                ' '
                            ) }}
                            FC

                        </p>

                    </div>

                </div>

            </div>


        </div>


        {{-- Actions --}}
        <div class="px-6 py-5 bg-slate-50 border-t border-slate-200">

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                <a href="{{ route('paiements.details-jour', [
                        'date' => $dateRetour
                    ]) }}"
                class="inline-flex items-center justify-center px-5 py-2.5
                        rounded-xl bg-white border border-slate-300
                        text-slate-700 font-medium
                        hover:bg-slate-100 transition">

                    Annuler

                </a>


                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5
                               rounded-xl bg-blue-600 text-white font-semibold
                               hover:bg-blue-700 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                    Enregistrer les modifications

                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const montantPaye =
        document.getElementById('montant_paye');

    const restantPreview =
        document.getElementById('restant-preview');

    const montantDu =
        {{ (float) $paiement->montant_du }};


    function mettreAJourRestant() {

        const montant =
            parseFloat(montantPaye.value) || 0;

        const restant =
            Math.max(
                montantDu - montant,
                0
            );

        restantPreview.textContent =
            new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(restant) + ' FC';
    }


    montantPaye.addEventListener(
        'input',
        mettreAJourRestant
    );


    mettreAJourRestant();

});

</script>

@endsection
