@extends('layouts.app')

@section('title', 'Parcours scolaire')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Parcours scolaire
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Évolution de l'élève au cours de sa scolarité
            </p>
        </div>

        {{-- Retour à la page précédente --}}
        <a
            href="{{ request('from', route('eleves.index')) }}"
            class="inline-flex items-center justify-center
                   px-4 py-2
                   rounded-lg
                   bg-gray-100
                   text-gray-700
                   hover:bg-gray-200
                   transition">

            <i class="fas fa-arrow-left mr-2"></i>

            Retour
        </a>

    </div>


    {{-- =========================================================
         INFORMATIONS DE L'ÉLÈVE
    ========================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">

        <div class="flex flex-col md:flex-row md:items-center gap-5">

            {{-- PHOTO --}}
            <div class="flex-shrink-0">

                @if($eleve->photo)

                    <img
                        src="{{ asset('storage/' . $eleve->photo) }}"
                        alt="Photo de {{ $eleve->nom_complet }}"
                        class="w-20 h-20 rounded-full object-cover border-4 border-gray-100">

                @else

                    <div
                        class="w-20 h-20 rounded-full
                               bg-blue-100
                               text-blue-600
                               flex items-center justify-center
                               text-2xl font-bold">

                        {{ strtoupper(substr($eleve->nom ?? '', 0, 1)) }}

                    </div>

                @endif

            </div>


            {{-- IDENTITÉ --}}
            <div class="flex-1">

                <h2 class="text-xl font-bold text-gray-800">
                    {{ $eleve->nom_complet }}
                </h2>

                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-2 text-sm text-gray-600">

                    <span>
                        <i class="fas fa-id-card mr-1 text-gray-400"></i>
                        {{ $eleve->matricule ?? '—' }}
                    </span>

                    <span>
                        <i class="fas fa-venus-mars mr-1 text-gray-400"></i>
                        {{ $eleve->sexe_libelle }}
                    </span>

                    @if($eleve->date_naissance)

                        <span>
                            <i class="fas fa-calendar mr-1 text-gray-400"></i>
                            {{ $eleve->date_naissance->format('d/m/Y') }}
                        </span>

                    @endif

                    @if($eleve->telephone)

                        <span>
                            <i class="fas fa-phone mr-1 text-gray-400"></i>
                            {{ $eleve->telephone }}
                        </span>

                    @endif

                </div>

            </div>


            {{-- NOMBRE D'ANNÉES --}}
            <div class="text-center bg-blue-50 rounded-lg px-5 py-3">

                <div class="text-2xl font-bold text-blue-600">
                    {{ $inscriptions->count() }}
                </div>

                <div class="text-xs text-gray-500">
                    année(s) scolaire(s)
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PARCOURS SCOLAIRE
    ========================================================== --}}

    @if($inscriptions->count())

        <div class="relative">

            {{-- Ligne verticale --}}
            <div
                class="absolute left-5 top-0 bottom-0
                       w-0.5 bg-gray-200 hidden md:block">
            </div>


            @foreach($inscriptions as $index => $inscription)

                <div class="relative flex gap-5 mb-8">

                    {{-- POINT --}}
                    <div class="relative z-10 flex-shrink-0 hidden md:flex">

                        <div
                            class="w-10 h-10 rounded-full
                                bg-blue-600
                                text-white
                                flex items-center justify-center
                                font-bold shadow-sm">

                            {{ $index + 1 }}

                        </div>

                    </div>


                    {{-- CARTE --}}
                    <div
                        class="flex-1
                            bg-white
                            border border-gray-200
                            rounded-xl
                            shadow-sm
                            hover:shadow-md
                            transition">

                        <div class="p-5">

                            {{-- ANNÉE --}}
                            <div class="flex flex-col sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                        gap-3">

                                <span
                                    class="inline-flex items-center
                                        w-fit
                                        px-3 py-1
                                        rounded-full
                                        bg-blue-50
                                        text-blue-700
                                        text-sm
                                        font-semibold">

                                    <i class="fas fa-calendar-alt mr-2"></i>

                                    {{ $inscription->anneeScolaire->libelle }}

                                </span>


                                {{-- TYPE --}}
                                @if($index === 0)

                                    <span
                                        class="inline-flex items-center
                                            w-fit
                                            px-3 py-1
                                            rounded-full
                                            bg-green-50
                                            text-green-700
                                            text-xs
                                            font-medium">

                                        <i class="fas fa-user-plus mr-1"></i>

                                        Première inscription

                                    </span>

                                @else

                                    <span
                                        class="inline-flex items-center
                                            w-fit
                                            px-3 py-1
                                            rounded-full
                                            bg-purple-50
                                            text-purple-700
                                            text-xs
                                            font-medium">

                                        <i class="fas fa-user-check mr-1"></i>

                                        Réinscription

                                    </span>

                                @endif

                            </div>


                            {{-- CLASSE --}}
                            <div class="mt-4">

                                <div class="text-xs uppercase
                                            tracking-wide
                                            text-gray-400">

                                    Classe

                                </div>

                                <div class="text-lg font-bold text-gray-800 mt-1">

                                    {{ $inscription->classe->nom_complet }}

                                </div>

                            </div>


                            {{-- DÉTAILS --}}
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
                                    gap-4 mt-5 pt-4
                                    border-t border-gray-100">

                                {{-- SECTION --}}
                                <div>

                                    <div class="text-xs text-gray-400">
                                        Section
                                    </div>

                                    <div class="text-sm font-medium text-gray-700 mt-1">
                                        {{ $inscription->classe->section ?? '—' }}
                                    </div>

                                </div>


                                {{-- OPTION --}}
                                @if($inscription->classe->option)

                                    <div>

                                        <div class="text-xs text-gray-400">
                                            Option
                                        </div>

                                        <div class="text-sm font-medium text-gray-700 mt-1">
                                            {{ $inscription->classe->option }}
                                        </div>

                                    </div>

                                @endif


                                {{-- VARIANTE --}}
                                @if($inscription->classe->variante)

                                    <div>

                                        <div class="text-xs text-gray-400">
                                            Variante
                                        </div>

                                        <div class="mt-1">

                                            <span
                                                class="inline-flex
                                                    items-center
                                                    justify-center
                                                    w-8 h-8
                                                    rounded-lg
                                                    bg-gray-100
                                                    text-gray-700
                                                    text-sm
                                                    font-bold">

                                                {{ $inscription->classe->variante }}

                                            </span>

                                        </div>

                                    </div>

                                @endif


                                {{-- MONTANT --}}
                                <div>

                                    <div class="text-xs text-gray-400">
                                        Montant
                                    </div>

                                    <div class="text-sm font-semibold text-gray-700 mt-1">

                                        {{ number_format($inscription->montant ?? 0, 2, ',', ' ') }}

                                        <span class="text-xs font-normal text-gray-400">
                                            FC
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- DATE D'INSCRIPTION --}}
                            @if($inscription->date_inscription)

                                <div class="mt-4 text-xs text-gray-400">

                                    <i class="fas fa-clock mr-1"></i>

                                    {{ $index === 0 ? 'Inscrit' : 'Réinscrit' }} le

                                    {{ $inscription->date_inscription->format('d/m/Y') }}

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach
        </div>

    @else

        {{-- AUCUNE INSCRIPTION --}}
        <div
            class="bg-white
                   border border-gray-200
                   rounded-xl
                   p-10
                   text-center">

            <div
                class="w-16 h-16
                       mx-auto
                       rounded-full
                       bg-gray-100
                       text-gray-400
                       flex items-center justify-center
                       text-2xl">

                <i class="fas fa-route"></i>

            </div>

            <h3 class="mt-4 text-lg font-semibold text-gray-700">
                Aucun parcours disponible
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Cet élève ne possède encore aucune inscription enregistrée.
            </p>

        </div>

    @endif

</div>

@endsection
