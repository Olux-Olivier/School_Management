@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('inscriptions.index') }}"
                    class="w-10 h-10 flex items-center justify-center
                           rounded-lg bg-slate-100
                           text-slate-600
                           hover:bg-slate-200
                           transition">

                    <i class="fas fa-arrow-left"></i>

                </a>


                <div>

                    <h1 class="text-2xl font-bold text-slate-800">
                        Consultation de l'inscription
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Détails complets de l'inscription de l'élève.
                    </p>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('inscriptions.edit', $inscription) }}"
                class="inline-flex items-center
                       px-4 py-2.5
                       rounded-lg
                       bg-blue-600
                       text-white
                       hover:bg-blue-700
                       transition">

                <i class="fas fa-edit mr-2"></i>

                Modifier

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGE DE SUCCÈS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="mb-6 p-4 rounded-lg
                   bg-green-50
                   border border-green-200
                   text-green-700">

            <div class="flex items-center">

                <i class="fas fa-check-circle mr-3"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- INFORMATIONS PRINCIPALES --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


        {{-- ===================================================== --}}
        {{-- CARTE ÉLÈVE --}}
        {{-- ===================================================== --}}

        <div
            class="lg:col-span-2
                   bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11
                               flex items-center justify-center
                               rounded-lg
                               bg-blue-100
                               text-blue-600">

                        <i class="fas fa-user-graduate"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Informations de l'élève
                        </h2>

                        <p class="text-sm text-slate-500">
                            Identité de l'élève inscrit.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">


                    {{-- Matricule --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Matricule
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->matricule ?? '—' }}
                        </p>

                    </div>


                    {{-- Nom --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Nom
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->nom ?? '—' }}
                        </p>

                    </div>


                    {{-- Postnom --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Postnom
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->postnom ?? '—' }}
                        </p>

                    </div>


                    {{-- Prénom --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Prénom
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->prenom ?? '—' }}
                        </p>

                    </div>


                    {{-- Sexe --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Sexe
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->sexe_libelle ?? '—' }}
                        </p>

                    </div>


                    {{-- Téléphone --}}

                    <div>

                        <p class="text-xs font-medium text-slate-500 uppercase">
                            Téléphone
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            {{ $inscription->eleve->telephone ?? '—' }}
                        </p>

                    </div>


                    {{-- Email --}}

                    @if(!empty($inscription->eleve->email))

                        <div>

                            <p class="text-xs font-medium text-slate-500 uppercase">
                                Email
                            </p>

                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $inscription->eleve->email }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STATUT --}}
        {{-- ===================================================== --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-800">
                    Statut
                </h2>

            </div>


            <div class="p-6">

                @if($inscription->actif)

                    <div
                        class="flex items-center gap-3
                               p-4
                               rounded-lg
                               bg-green-50
                               border border-green-200">

                        <div
                            class="w-10 h-10
                                   rounded-full
                                   bg-green-100
                                   text-green-600
                                   flex items-center justify-center">

                            <i class="fas fa-check"></i>

                        </div>

                        <div>

                            <p class="font-semibold text-green-700">
                                Inscription active
                            </p>

                            <p class="text-sm text-green-600">
                                Cette inscription est actuellement active.
                            </p>

                        </div>

                    </div>

                @else

                    <div
                        class="flex items-center gap-3
                               p-4
                               rounded-lg
                               bg-red-50
                               border border-red-200">

                        <div
                            class="w-10 h-10
                                   rounded-full
                                   bg-red-100
                                   text-red-600
                                   flex items-center justify-center">

                            <i class="fas fa-times"></i>

                        </div>

                        <div>

                            <p class="font-semibold text-red-700">
                                Inscription inactive
                            </p>

                            <p class="text-sm text-red-600">
                                Cette inscription est actuellement inactive.
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- INFORMATIONS SCOLAIRES --}}
    {{-- ========================================================= --}}

    <div
        class="mt-6
               bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <div class="flex items-center gap-3">

                <div
                    class="w-11 h-11
                           flex items-center justify-center
                           rounded-lg
                           bg-indigo-100
                           text-indigo-600">

                    <i class="fas fa-school"></i>

                </div>

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Informations scolaires
                    </h2>

                    <p class="text-sm text-slate-500">
                        Année scolaire et classe de l'élève.
                    </p>

                </div>

            </div>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">


                {{-- Année scolaire --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Année scolaire
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->anneeScolaire->libelle ?? '—' }}

                    </p>

                </div>


                {{-- Section --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Section
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->classe->section ?? '—' }}

                    </p>

                </div>


                {{-- Classe --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Classe
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->classe->nom ?? '—' }}

                    </p>

                </div>


                {{-- Option --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Option
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->classe->option ?: '—' }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PAIEMENT + DATE --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">


        {{-- Paiement --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11
                               flex items-center justify-center
                               rounded-lg
                               bg-emerald-100
                               text-emerald-600">

                        <i class="fas fa-money-bill-wave"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Paiement
                        </h2>

                        <p class="text-sm text-slate-500">
                            Informations financières de l'inscription.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <p class="text-xs font-medium text-slate-500 uppercase">
                    Montant versé
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-800">

                    {{ number_format(
                        $inscription->montant ?? 0,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-base font-medium text-slate-500">
                        FC
                    </span>

                </p>

            </div>

        </div>


        {{-- Date --}}

        <div
            class="bg-white
                   rounded-xl
                   shadow-sm
                   border border-slate-200
                   overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-200">

                <div class="flex items-center gap-3">

                    <div
                        class="w-11 h-11
                               flex items-center justify-center
                               rounded-lg
                               bg-amber-100
                               text-amber-600">

                        <i class="fas fa-calendar-alt"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-slate-800">
                            Date d'inscription
                        </h2>

                        <p class="text-sm text-slate-500">
                            Date officielle de l'inscription.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <p class="text-2xl font-bold text-slate-800">

                    {{ $inscription->date_inscription
                        ? $inscription->date_inscription->format('d/m/Y')
                        : '—' }}

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TRAÇABILITÉ --}}
    {{-- ========================================================= --}}

    <div
        class="mt-6
               bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-200">

            <div class="flex items-center gap-3">

                <div
                    class="w-11 h-11
                           flex items-center justify-center
                           rounded-lg
                           bg-slate-100
                           text-slate-600">

                    <i class="fas fa-history"></i>

                </div>

                <div>

                    <h2 class="text-lg font-semibold text-slate-800">
                        Traçabilité
                    </h2>

                    <p class="text-sm text-slate-500">
                        Historique de l'enregistrement.
                    </p>

                </div>

            </div>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Créé par --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Enregistré par
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        @if($inscription->createdBy)

                            {{ $inscription->createdBy->nom_complet }}

                        @else

                            —

                        @endif

                    </p>

                </div>


                {{-- Date création --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Date de création
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->created_at
                            ? $inscription->created_at->format('d/m/Y à H:i')
                            : '—' }}

                    </p>

                </div>


                {{-- Modifié par --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Dernière modification par
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        @if($inscription->updatedBy)

                            {{ $inscription->updatedBy->nom_complet }}

                        @else

                            —

                        @endif

                    </p>

                </div>


                {{-- Date modification --}}

                <div>

                    <p class="text-xs font-medium text-slate-500 uppercase">
                        Dernière modification
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">

                        {{ $inscription->updated_at
                            ? $inscription->updated_at->format('d/m/Y à H:i')
                            : '—' }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ACTIONS --}}
    {{-- ========================================================= --}}

    <div
        class="mt-6
               flex flex-col sm:flex-row
               justify-end
               gap-3">

        <a
            href="{{ route('inscriptions.index') }}"
            class="px-5 py-2.5
                   rounded-lg
                   bg-slate-200
                   text-slate-700
                   text-center
                   hover:bg-slate-300
                   transition">

            <i class="fas fa-arrow-left mr-2"></i>

            Retour à la liste

        </a>

        <a
            href="{{ route('inscriptions.pdf', $inscription) }}"
            target="_blank"
            class="px-5 py-2.5
                rounded-lg
                bg-red-600
                text-white
                text-center
                hover:bg-red-700
                transition">

            <i class="fas fa-file-pdf mr-2"></i>

            Télécharger la fiche PDF

        </a>


        <a
            href="{{ route('inscriptions.edit', $inscription) }}"
            class="px-5 py-2.5
                   rounded-lg
                   bg-blue-600
                   text-white
                   text-center
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-edit mr-2"></i>

            Modifier

        </a>

    </div>

</div>

@endsection
