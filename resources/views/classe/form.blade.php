@extends('layouts.app')

@section('title', isset($classe) ? 'Modifier une classe' : 'Ajouter une classe')

@section('breadcrumb')
    {{ isset($classe) ? 'Modifier' : 'Ajouter' }} une classe
@endsection

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <!-- ===================================================== -->
    <!-- EN-TÊTE -->
    <!-- ===================================================== -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

        </div>

        <a
                href="{{ route('classes.index') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 sm:w-auto"
            >
                <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                <span>Retour aux classes</span>
            </a>

    </div>

    <div class="bg-white rounded-xl shadow-sm border mb-6">

        <div class="px-6 py-5">

            <h2 class="text-2xl font-bold text-slate-700">

                {{ isset($classe)
                    ? 'Modifier la classe'
                    : 'Ajouter une classe' }}

            </h2>

            <p class="text-sm text-slate-500 mt-1">

                {{ isset($classe)
                    ? 'Modifiez les informations de cette classe.'
                    : 'Enregistrez une nouvelle classe dans l’établissement.' }}

            </p>

        </div>



        <form
            action="{{ isset($classe)
                ? route('classes.update', $classe)
                : route('classes.store') }}"
            method="POST">

            @csrf

            @if(isset($classe))
                @method('PUT')
            @endif


            <div class="p-6 ">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    <!-- ================================================= -->
                    <!-- SECTION -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Section
                            <span class="text-red-500">*</span>

                        </label>

                        <select
                            name="section"
                            id="section"
                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                            <option value="">
                                Sélectionner une section
                            </option>

                            <option
                                value="Maternelle"
                                {{ old('section', $classe->section ?? '') == 'Maternelle'
                                    ? 'selected'
                                    : '' }}>

                                Maternelle

                            </option>

                            <option
                                value="Primaire"
                                {{ old('section', $classe->section ?? '') == 'Primaire'
                                    ? 'selected'
                                    : '' }}>

                                Primaire

                            </option>

                            <option
                                value="Secondaire"
                                {{ old('section', $classe->section ?? '') == 'Secondaire'
                                    ? 'selected'
                                    : '' }}>

                                Secondaire

                            </option>

                            <option
                                value="Humanités"
                                {{ old('section', $classe->section ?? '') == 'Humanités'
                                    ? 'selected'
                                    : '' }}>

                                Humanités

                            </option>

                        </select>

                        @error('section')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- NIVEAU -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Niveau

                            <span class="text-red-500">*</span>

                        </label>

                        <select
                            name="niveau"
                            id="niveau"
                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                            <option value="">
                                Sélectionner
                            </option>

                            <option value="0">
                                Maternelle
                            </option>

                            <option value="1">
                                Primaire
                            </option>

                            <option value="2">
                                Secondaire
                            </option>

                            <option value="3">
                                Humanités
                            </option>

                        </select>

                        @error('niveau')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- NOM / CLASSE -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Classe

                            <span class="text-red-500">*</span>

                        </label>

                        <select
                            name="nom"
                            id="nom"
                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                            <option value="">
                                Sélectionner une classe
                            </option>

                        </select>

                        @error('nom')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- OPTION -->
                    <!-- ================================================= -->

                    <div
                        id="optionContainer"
                        class="hidden">

                        <label class="block mb-2 font-medium text-slate-700">

                            Option

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="text"
                            name="option"
                            id="option"

                            value="{{ old('option', $classe->option ?? '') }}"

                            placeholder="Exemple : Commercial"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('option')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- APERÇU -->
                    <!-- ================================================= -->

                    <div class="md:col-span-2">

                        <div
                            class="bg-slate-50 border border-slate-200 rounded-xl p-5">

                            <p class="text-sm text-gray-500 mb-2">

                                Aperçu du nom complet

                            </p>

                            <p
                                id="nomCompletPreview"
                                class="text-xl font-bold text-slate-700">

                                --

                            </p>

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- STATUT -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Statut

                        </label>

                        <select
                            name="actif"
                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                            <option
                                value="1"
                                {{ old('actif', $classe->actif ?? 1) == 1
                                    ? 'selected'
                                    : '' }}>

                                Actif

                            </option>

                            <option
                                value="0"
                                {{ old('actif', $classe->actif ?? 1) == 0
                                    ? 'selected'
                                    : '' }}>

                                Inactif

                            </option>

                        </select>

                        @error('actif')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- BOUTONS -->
            <!-- ===================================================== -->

            <div class="border-t border-slate-200 px-6 py-4
                        flex justify-end gap-3">

                <a
                    href="{{ route('classes.index') }}"
                    class="px-5 py-2.5 rounded-lg
                           bg-gray-200 text-gray-700
                           hover:bg-gray-300 transition">

                    Annuler

                </a>


                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg
                           bg-blue-600 text-white
                           hover:bg-blue-700 transition">

                    <i class="fas fa-save mr-2"></i>

                    {{ isset($classe)
                        ? 'Enregistrer les modifications'
                        : 'Enregistrer' }}

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const section = document.getElementById('section');

    const niveau = document.getElementById('niveau');

    const nom = document.getElementById('nom');

    const optionContainer =
        document.getElementById('optionContainer');

    const option =
        document.getElementById('option');

    const preview =
        document.getElementById('nomCompletPreview');


    /*
    |--------------------------------------------------------------------------
    | Correspondance section / niveau
    |--------------------------------------------------------------------------
    */

    const niveaux = {

        'Maternelle': '0',

        'Primaire': '1',

        'Secondaire': '2',

        'Humanités': '3'

    };


    /*
    |--------------------------------------------------------------------------
    | Classes disponibles
    |--------------------------------------------------------------------------
    */

    const classes = {

        0: [
            '1ère',
            '2ème',
            '3ème'
        ],

        1: [
            '1ère',
            '2ème',
            '3ème',
            '4ème',
            '5ème',
            '6ème'
        ],

        2: [
            '1ère',
            '2ème'
        ],

        3: [
            '1ère',
            '2ème',
            '3ème',
            '4ème'
        ]

    };


    /*
    |--------------------------------------------------------------------------
    | Ancienne valeur pour modification
    |--------------------------------------------------------------------------
    */

    const ancienneClasse =
        @json(old('nom', $classe->nom ?? ''));

    const ancienNiveau =
        @json(old('niveau', $classe->niveau ?? ''));


    /*
    |--------------------------------------------------------------------------
    | Quand la section change
    |--------------------------------------------------------------------------
    */

    section.addEventListener('change', function () {

        const valeur = this.value;

        if (niveaux[valeur] !== undefined) {

            niveau.value = niveaux[valeur];

            mettreAJourClasses();

        } else {

            niveau.value = '';

            nom.innerHTML =
                '<option value="">Sélectionner une classe</option>';

            cacherOption();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Quand le niveau change
    |--------------------------------------------------------------------------
    */

    niveau.addEventListener('change', function () {

        mettreAJourClasses();

    });


    /*
    |--------------------------------------------------------------------------
    | Mettre à jour les classes
    |--------------------------------------------------------------------------
    */

    function mettreAJourClasses()
    {

        const valeur = niveau.value;

        nom.innerHTML =
            '<option value="">Sélectionner une classe</option>';


        if (!classes[valeur]) {

            cacherOption();

            return;

        }


        classes[valeur].forEach(function (classeNom) {

            const optionElement =
                document.createElement('option');

            optionElement.value = classeNom;

            optionElement.textContent = classeNom;


            if (classeNom === ancienneClasse) {

                optionElement.selected = true;

            }


            nom.appendChild(optionElement);

        });


        /*
        |--------------------------------------------------------------------------
        | Humanités
        |--------------------------------------------------------------------------
        */

        if (valeur === '3') {

            afficherOption();

        } else {

            cacherOption();

        }


        afficherPreview();

    }


    /*
    |--------------------------------------------------------------------------
    | Afficher l'option
    |--------------------------------------------------------------------------
    */

    function afficherOption()
    {

        optionContainer.classList.remove('hidden');

        option.required = true;

    }


    /*
    |--------------------------------------------------------------------------
    | Cacher l'option
    |--------------------------------------------------------------------------
    */

    function cacherOption()
    {

        optionContainer.classList.add('hidden');

        option.required = false;

        option.value = '';

    }


    /*
    |--------------------------------------------------------------------------
    | Mise à jour de l'aperçu
    |--------------------------------------------------------------------------
    */

    function afficherPreview()
    {

        const classeNom = nom.value;

        if (!classeNom) {

            preview.textContent = '--';

            return;

        }


        if (niveau.value === '3') {

            if (option.value.trim() !== '') {

                preview.textContent =
                    classeNom + ' ' + option.value.trim();

            } else {

                preview.textContent =
                    classeNom + ' ...';

            }

        } else {

            preview.textContent =
                classeNom + ' ' + section.value;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Changement de classe
    |--------------------------------------------------------------------------
    */

    nom.addEventListener('change', function () {

        afficherPreview();

    });


    /*
    |--------------------------------------------------------------------------
    | Changement d'option
    |--------------------------------------------------------------------------
    */

    option.addEventListener('input', function () {

        afficherPreview();

    });


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    if (ancienNiveau !== '') {

        niveau.value = ancienNiveau;

        mettreAJourClasses();

    } else if (section.value !== '') {

        niveau.value =
            niveaux[section.value] ?? '';

        mettreAJourClasses();

    }

});

</script>

@endsection
