@extends('layouts.app')

@section('title', 'Nouvelle réinscription')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Réinscription d'un élève
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Enregistrez l'élève dans la nouvelle année scolaire.
                </p>

            </div>


            <a
                href="{{ route('reinscriptions.index') }}"

                class="inline-flex
                       items-center
                       justify-center
                       px-5
                       py-2.5
                       rounded-lg
                       bg-slate-200
                       text-slate-700
                       hover:bg-slate-300
                       transition">

                <i class="fas fa-arrow-left mr-2"></i>

                Retour à la liste

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

            <p class="font-semibold mb-2">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="list-disc list-inside text-sm">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('reinscriptions.store') }}"
        method="POST"

        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">

        @csrf


        {{-- ===================================================== --}}
        {{-- ÉLÈVE --}}
        {{-- ===================================================== --}}

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Élève
            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-6">
                Informations de l'élève concerné par la réinscription.
            </p>


            <input
                type="hidden"
                name="ancienne_inscription_id"
                value="{{ $inscription->id }}">


            <input
                type="hidden"
                name="eleve_id"
                value="{{ $inscription->eleve_id }}">


            <div
                class="p-5
                       bg-blue-50
                       border border-blue-200
                       rounded-xl">

                <div class="flex items-start gap-4">

                    <div
                        class="w-12 h-12
                               flex-shrink-0
                               rounded-full
                               bg-blue-100
                               flex items-center
                               justify-center
                               text-blue-600">

                        <i class="fas fa-user-graduate"></i>

                    </div>


                    <div class="flex-1">

                        <p class="text-xs
                                  uppercase
                                  tracking-wide
                                  font-medium
                                  text-blue-600">

                            Élève sélectionné

                        </p>


                        <h3 class="text-lg
                                   font-bold
                                   text-slate-800
                                   mt-1">

                            {{ $inscription->eleve->nom }}
                            {{ $inscription->eleve->postnom }}
                            {{ $inscription->eleve->prenom }}

                        </h3>


                        <p class="text-sm
                                  text-blue-700
                                  font-medium
                                  mt-1">

                            Matricule :
                            {{ $inscription->eleve->matricule }}

                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- ANCIENNE / NOUVELLE ANNÉE --}}
        {{-- ===================================================== --}}

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Année scolaire
            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-6">
                L'ancienne inscription est conservée et une nouvelle
                inscription sera créée dans l'année active.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Ancienne année --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Ancienne année scolaire

                    </label>


                    <input
                        type="text"

                        value="{{ $anneeScolairePrecedente->libelle }}"

                        readonly

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               bg-slate-100
                               text-slate-600
                               px-4
                               py-3
                               cursor-not-allowed">

                </div>



                {{-- Nouvelle année --}}

                <div>

                    <label
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Nouvelle année scolaire

                    </label>


                    <input
                        type="text"

                        value="{{ $anneeScolaireActive->libelle }}"

                        readonly

                        class="w-full
                               border border-green-300
                               rounded-lg
                               bg-green-50
                               text-green-700
                               font-medium
                               px-4
                               py-3
                               cursor-not-allowed">

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- ANCIENNE CLASSE --}}
        {{-- ===================================================== --}}

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Classe précédente
            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-6">
                Classe dans laquelle l'élève était inscrit durant
                l'année précédente.
            </p>


            <div
                class="p-4
                       bg-slate-50
                       border border-slate-200
                       rounded-lg">

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10
                               rounded-lg
                               bg-slate-200
                               flex items-center
                               justify-center
                               text-slate-500">

                        <i class="fas fa-school"></i>

                    </div>


                    <div>

                        <p class="text-xs text-slate-500">
                            Classe précédente
                        </p>

                        <p class="font-semibold text-slate-800">

                            {{ $inscription->classe->nom_complet }}

                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- NOUVELLE CLASSE --}}
        {{-- ===================================================== --}}

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Nouvelle classe
            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-6">
                Sélectionnez d'abord la section, puis la nouvelle classe.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Section --}}

                <div>

                    <label
                        for="section"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Section
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        name="section"
                        id="section"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4
                               py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            -- Sélectionner une section --
                        </option>


                        <option
                            value="maternelle"
                            data-niveau="0">

                            Maternelle

                        </option>


                        <option
                            value="primaire"
                            data-niveau="1">

                            Primaire

                        </option>


                        <option
                            value="secondaire"
                            data-niveau="2">

                            Secondaire

                        </option>


                        <option
                            value="humanites"
                            data-niveau="3">

                            Humanités

                        </option>

                    </select>


                    @error('section')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                {{-- Classe --}}

                <div>

                    <label
                        for="classe_id"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Nouvelle classe
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        name="classe_id"
                        id="classe_id"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4
                               py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            -- Sélectionner d'abord une section --
                        </option>


                        @foreach($classes as $classe)

                            <option
                                value="{{ $classe->id }}"

                                data-niveau="{{ $classe->niveau }}">

                                {{ $classe->nom_complet }}

                            </option>

                        @endforeach

                    </select>


                    @error('classe_id')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- DATE + MONTANT --}}
        {{-- ===================================================== --}}

        <div class="p-6 border-b border-slate-200">

            <h2 class="text-lg font-semibold text-slate-700">
                Informations de la réinscription
            </h2>

            <p class="text-sm text-slate-500 mt-1 mb-6">
                Indiquez la date et le montant versé.
            </p>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Date --}}

                <div>

                    <label
                        for="date_inscription"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Date de réinscription
                        <span class="text-red-500">*</span>

                    </label>


                    <input
                        type="date"
                        name="date_inscription"
                        id="date_inscription"

                        value="{{ old(
                            'date_inscription',
                            date('Y-m-d')
                        ) }}"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4
                               py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">


                    @error('date_inscription')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                {{-- Montant --}}

                <div>

                    <label
                        for="montant"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Montant
                        <span class="text-red-500">*</span>

                    </label>


                    <div class="relative">

                        <input
                            type="number"
                            name="montant"
                            id="montant"

                            value="{{ old('montant') }}"

                            min="0"
                            step="0.01"

                            placeholder="Exemple : 150000"

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   px-4
                                   py-3
                                   pr-14
                                   focus:ring-2
                                   focus:ring-blue-500
                                   focus:border-blue-500
                                   focus:outline-none">


                        <span
                            class="absolute
                                   right-4
                                   top-1/2
                                   -translate-y-1/2
                                   text-sm
                                   font-medium
                                   text-slate-400">

                            FC

                        </span>

                    </div>


                    @error('montant')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- BOUTONS --}}
        {{-- ===================================================== --}}

        <div
            class="px-6
                   py-5
                   bg-slate-50
                   flex
                   flex-col-reverse
                   md:flex-row
                   md:justify-end
                   gap-3">


            <a
                href="{{ route('reinscriptions.index') }}"

                class="px-5
                       py-2.5
                       rounded-lg
                       bg-slate-200
                       text-slate-700
                       text-center
                       hover:bg-slate-300
                       transition">

                Annuler

            </a>


            <button
                type="submit"

                class="px-5
                       py-2.5
                       rounded-lg
                       bg-blue-600
                       text-white
                       hover:bg-blue-700
                       transition">

                <i class="fas fa-user-plus mr-2"></i>

                Réinscrire l'élève

            </button>

        </div>

    </form>

</div>



{{-- ============================================================= --}}
{{-- JAVASCRIPT : SECTION → CLASSE --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const section =
        document.getElementById('section');

    const classe =
        document.getElementById('classe_id');


    function filtrerClasses()
    {

        const selectedOption =
            section.options[
                section.selectedIndex
            ];


        const selectedNiveau =
            selectedOption
                ? selectedOption.dataset.niveau
                : '';


        Array.from(
            classe.options
        ).forEach(function (option) {

            /*
            |--------------------------------------------------------------------------
            | Première option
            |--------------------------------------------------------------------------
            */

            if (!option.value) {

                option.hidden = false;

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Niveau de la classe
            |--------------------------------------------------------------------------
            */

            const classeNiveau =
                option.dataset.niveau;


            /*
            |--------------------------------------------------------------------------
            | Filtrage
            |--------------------------------------------------------------------------
            */

            if (
                selectedNiveau !== ''
                &&
                classeNiveau === selectedNiveau
            ) {

                option.hidden = false;

            } else {

                option.hidden = true;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Réinitialiser la classe
        |--------------------------------------------------------------------------
        */

        classe.value = '';

    }


    section.addEventListener(
        'change',
        filtrerClasses
    );


    /*
    |--------------------------------------------------------------------------
    | État initial
    |--------------------------------------------------------------------------
    */

    filtrerClasses();

});

</script>

@endsection
