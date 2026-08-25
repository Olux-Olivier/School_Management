@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <div class="flex items-center gap-3 mb-2">

            <a
                href="{{ route('frais.index') }}"
                class="inline-flex items-center justify-center
                       w-10 h-10 rounded-lg
                       bg-slate-100 text-slate-600
                       hover:bg-slate-200 transition">

                <i class="fas fa-arrow-left"></i>

            </a>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Ajouter un frais
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Définir un frais pour une classe de l'année scolaire active.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ANNEE SCOLAIRE ACTIVE --}}
    {{-- ========================================================= --}}

    <div
        class="mb-6 rounded-xl
               border border-blue-200
               bg-blue-50 p-5">

        <div class="flex items-start gap-4">

            <div
                class="flex-shrink-0
                       w-10 h-10 rounded-lg
                       bg-blue-100
                       text-blue-600
                       flex items-center
                       justify-center">

                <i class="fas fa-calendar-alt"></i>

            </div>

            <div>

                <p class="text-sm text-blue-600 font-medium">
                    Année scolaire active
                </p>

                <p class="text-lg font-bold text-blue-900 mt-1">
                    {{ $anneeScolaire->libelle }}
                </p>

                <p class="text-xs text-blue-600 mt-1">
                    L'année scolaire est automatiquement déterminée par le système.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div
            class="mb-6 p-4 rounded-xl
                   bg-red-50
                   border border-red-200
                   text-red-700">

            <div class="flex items-start gap-3">

                <i
                    class="fas fa-exclamation-circle
                           mt-0.5
                           text-red-500">
                </i>

                <div>

                    <p class="font-semibold mb-2">
                        Impossible d'enregistrer le frais.
                    </p>

                    <ul class="list-disc list-inside text-sm space-y-1">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('frais.store') }}"
        method="POST"
        class="bg-white rounded-xl
               shadow-sm border border-slate-200
               overflow-hidden">

        @csrf


        {{-- ===================================================== --}}
        {{-- INFORMATIONS DU FRAIS --}}
        {{-- ===================================================== --}}

        <div class="p-6">

            <div class="mb-6">

                <h2 class="text-lg font-semibold text-slate-800">
                    Informations du frais
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Sélectionnez d'abord la section,
                    puis la classe concernée.
                </p>

            </div>


            <div class="space-y-6">


                {{-- ================================================= --}}
                {{-- INTITULE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="intitule"
                        class="block text-sm font-medium
                               text-slate-700 mb-2">

                        Intitulé du frais

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        name="intitule"
                        id="intitule"
                        value="{{ old('intitule') }}"
                        placeholder="Exemple : Minerval"
                        autocomplete="off"
                        required

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               text-slate-700
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none
                               transition">

                    <p class="text-xs text-slate-500 mt-2">
                        Exemple : Minerval, Frais d'inscription,
                        Frais de laboratoire...
                    </p>

                    @error('intitule')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- SECTION --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="section"
                        class="block text-sm font-medium
                               text-slate-700 mb-2">

                        Section

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <select
                        name="section"
                        id="section"
                        required

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               text-slate-700
                               bg-white
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none
                               transition">

                        <option value="">
                            -- Sélectionner une section --
                        </option>

                        {{-- IMPORTANT :
                             Les valeurs correspondent exactement
                             aux valeurs stockées dans classes.section --}}

                        <option
                            value="Maternelle"
                            @selected(old('section') === 'Maternelle')>

                            Maternelle

                        </option>

                        <option
                            value="Primaire"
                            @selected(old('section') === 'Primaire')>

                            Primaire

                        </option>

                        <option
                            value="Secondaire"
                            @selected(old('section') === 'Secondaire')>

                            Secondaire

                        </option>

                        <option
                            value="Humanités"
                            @selected(old('section') === 'Humanités')>

                            Humanités

                        </option>

                    </select>

                    <p class="text-xs text-slate-500 mt-2">
                        Choisissez une section pour afficher
                        uniquement ses classes.
                    </p>

                    @error('section')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- CLASSE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="classe_id"
                        class="block text-sm font-medium
                               text-slate-700 mb-2">

                        Classe

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <select
                        name="classe_id"
                        id="classe_id"
                        required
                        disabled

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               text-slate-700
                               bg-slate-100
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none
                               transition
                               disabled:cursor-not-allowed">

                        <option value="">
                            -- Sélectionnez d'abord une section --
                        </option>

                    </select>

                    <p
                        id="classe-help"
                        class="text-xs text-slate-500 mt-2">

                        Les classes disponibles apparaîtront
                        après le choix de la section.

                    </p>

                    @error('classe_id')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- MONTANT --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="montant"
                        class="block text-sm font-medium
                               text-slate-700 mb-2">

                        Montant à payer

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            name="montant"
                            id="montant"
                            value="{{ old('montant') }}"
                            min="0"
                            step="0.01"
                            placeholder="Exemple : 50000"
                            required

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   px-4 py-3
                                   pr-16
                                   text-slate-700
                                   focus:ring-2
                                   focus:ring-blue-500
                                   focus:border-blue-500
                                   focus:outline-none
                                   transition">

                        <span
                            class="absolute
                                   right-4
                                   top-1/2
                                   -translate-y-1/2
                                   text-sm
                                   font-semibold
                                   text-slate-400">

                            FC

                        </span>

                    </div>

                    <p class="text-xs text-slate-500 mt-2">
                        Entrez le montant applicable à cette classe.
                    </p>

                    @error('montant')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RESUME --}}
        {{-- ========================================================= --}}

        <div
            id="resume"
            class="hidden
                   mx-6 mb-6
                   rounded-xl
                   border border-slate-200
                   bg-slate-50
                   p-5">

            <div class="flex items-start gap-3">

                <div
                    class="w-10 h-10
                           rounded-lg
                           bg-white
                           border border-slate-200
                           flex items-center
                           justify-center
                           text-blue-600">

                    <i class="fas fa-file-invoice-dollar"></i>

                </div>

                <div class="flex-1">

                    <p
                        class="text-xs
                               uppercase
                               tracking-wide
                               font-semibold
                               text-slate-400">

                        Résumé

                    </p>

                    <p
                        id="resume-frais"
                        class="font-semibold
                               text-slate-700
                               mt-1">

                        —

                    </p>

                    <p
                        id="resume-section"
                        class="text-sm
                               text-slate-500
                               mt-1">

                        —

                    </p>

                    <p
                        id="resume-classe"
                        class="text-sm
                               text-slate-500
                               mt-1">

                        —

                    </p>

                    <p
                        id="resume-montant"
                        class="text-lg
                               font-bold
                               text-blue-600
                               mt-2">

                        —

                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOUTONS --}}
        {{-- ========================================================= --}}

        <div
            class="px-6 py-5
                   bg-slate-50
                   border-t border-slate-200
                   flex flex-col-reverse
                   sm:flex-row
                   sm:justify-end
                   gap-3">

            <a
                href="{{ route('frais.index') }}"

                class="inline-flex
                       items-center
                       justify-center
                       px-5 py-2.5
                       rounded-lg
                       bg-slate-200
                       text-slate-700
                       hover:bg-slate-300
                       transition">

                <i class="fas fa-times mr-2"></i>

                Annuler

            </a>


            <button
                type="submit"

                class="inline-flex
                       items-center
                       justify-center
                       px-5 py-2.5
                       rounded-lg
                       bg-blue-600
                       text-white
                       hover:bg-blue-700
                       transition">

                <i class="fas fa-save mr-2"></i>

                Enregistrer le frais

            </button>

        </div>

    </form>

</div>


{{-- ================================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ================================================================ --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Données des classes
    |--------------------------------------------------------------------------
    */

    const classes = [

        @foreach($classes as $classe)

        {
            id: @json($classe->id),

            nom: @json($classe->nom),

            section: @json($classe->section),

            option: @json($classe->option),
        },

        @endforeach

    ];


    /*
    |--------------------------------------------------------------------------
    | Éléments
    |--------------------------------------------------------------------------
    */

    const sectionSelect =
        document.getElementById('section');

    const classeSelect =
        document.getElementById('classe_id');

    const classeHelp =
        document.getElementById('classe-help');

    const intituleInput =
        document.getElementById('intitule');

    const montantInput =
        document.getElementById('montant');

    const resume =
        document.getElementById('resume');

    const resumeFrais =
        document.getElementById('resume-frais');

    const resumeSection =
        document.getElementById('resume-section');

    const resumeClasse =
        document.getElementById('resume-classe');

    const resumeMontant =
        document.getElementById('resume-montant');


    /*
    |--------------------------------------------------------------------------
    | Anciennes valeurs
    |--------------------------------------------------------------------------
    */

    const ancienneSection =
        @json(old('section'));

    const ancienneClasse =
        @json(old('classe_id'));


    /*
    |--------------------------------------------------------------------------
    | Libellé section
    |--------------------------------------------------------------------------
    */

    function libelleSection(section) {

        const libelles = {

            'Maternelle': 'Maternelle',

            'Primaire': 'Primaire',

            'Secondaire': 'Secondaire',

            'Humanités': 'Humanités',

        };

        return libelles[section] ?? section;

    }


    /*
    |--------------------------------------------------------------------------
    | Afficher les classes
    |--------------------------------------------------------------------------
    */

    function afficherClasses(
        section,
        classeSelectionnee = null
    ) {


        /*
        | Réinitialiser
        */

        classeSelect.innerHTML = '';

        classeSelect.disabled = true;

        classeSelect.classList.add(
            'bg-slate-100'
        );

        classeSelect.classList.remove(
            'bg-white'
        );


        /*
        | Pas de section
        */

        if (!section) {

            const option =
                document.createElement('option');

            option.value = '';

            option.textContent =
                '-- Sélectionnez d\'abord une section --';

            classeSelect.appendChild(option);

            classeHelp.textContent =
                'Les classes disponibles apparaîtront après le choix de la section.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRAGE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | On compare exactement la valeur de la
        | section avec celle stockée en base.
        |
        */

        const classesFiltrees =
            classes.filter(function (classe) {

                return classe.section === section;

            });


        /*
        | Aucune classe
        */

        if (classesFiltrees.length === 0) {

            const option =
                document.createElement('option');

            option.value = '';

            option.textContent =
                '-- Aucune classe disponible --';

            classeSelect.appendChild(option);

            classeHelp.textContent =
                'Aucune classe active n\'est disponible dans cette section.';

            return;
        }


        /*
        | Option par défaut
        */

        const optionDefaut =
            document.createElement('option');

        optionDefaut.value = '';

        optionDefaut.textContent =
            '-- Sélectionner une classe --';

        classeSelect.appendChild(
            optionDefaut
        );


        /*
        |--------------------------------------------------------------------------
        | Ajouter les classes
        |--------------------------------------------------------------------------
        */

        classesFiltrees.forEach(function (classe) {

            const option =
                document.createElement('option');

            option.value =
                classe.id;


            /*
            | Nom
            */

            let texte =
                classe.nom;


            /*
            | Option
            */

            if (
                classe.option !== null &&
                classe.option !== ''
            ) {

                texte +=
                    ' - ' +
                    classe.option;

            }


            option.textContent =
                texte;


            /*
            | Restaurer la sélection
            */

            if (
                classeSelectionnee !== null &&
                String(classe.id) ===
                String(classeSelectionnee)
            ) {

                option.selected = true;

            }


            classeSelect.appendChild(
                option
            );

        });


        /*
        | Activer le select
        */

        classeSelect.disabled = false;

        classeSelect.classList.remove(
            'bg-slate-100'
        );

        classeSelect.classList.add(
            'bg-white'
        );


        /*
        | Aide
        */

        classeHelp.textContent =
            classesFiltrees.length +
            ' classe(s) active(s) disponible(s) dans cette section.';

    }


    /*
    |--------------------------------------------------------------------------
    | Changement section
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {

            afficherClasses(
                this.value
            );

            mettreAJourResume();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Changement classe
    |--------------------------------------------------------------------------
    */

    classeSelect.addEventListener(
        'change',
        function () {

            mettreAJourResume();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Changement intitulé
    |--------------------------------------------------------------------------
    */

    intituleInput.addEventListener(
        'input',
        function () {

            mettreAJourResume();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Changement montant
    |--------------------------------------------------------------------------
    */

    montantInput.addEventListener(
        'input',
        function () {

            mettreAJourResume();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Résumé
    |--------------------------------------------------------------------------
    */

    function mettreAJourResume() {

        const frais =
            intituleInput.value.trim();

        const section =
            sectionSelect.value;

        const classeOption =
            classeSelect.options[
                classeSelect.selectedIndex
            ];


        const classe =
            classeOption &&
            classeOption.value
                ? classeOption.textContent
                : 'Aucune classe sélectionnée';


        const montant =
            montantInput.value;


        /*
        | Rien à afficher
        */

        if (
            frais === '' &&
            section === '' &&
            !classeOption?.value &&
            montant === ''
        ) {

            resume.classList.add(
                'hidden'
            );

            return;

        }


        /*
        | Afficher
        */

        resume.classList.remove(
            'hidden'
        );


        /*
        | Frais
        */

        resumeFrais.textContent =
            frais !== ''
                ? frais
                : 'Intitulé non renseigné';


        /*
        | Section
        */

        resumeSection.textContent =
            section !== ''
                ? 'Section : ' +
                  libelleSection(section)
                : 'Section : Non sélectionnée';


        /*
        | Classe
        */

        resumeClasse.textContent =
            'Classe : ' +
            classe;


        /*
        | Montant
        */

        if (montant !== '') {

            const montantNombre =
                parseFloat(montant);


            if (!isNaN(montantNombre)) {

                resumeMontant.textContent =
                    new Intl.NumberFormat(
                        'fr-FR',
                        {
                            maximumFractionDigits: 2
                        }
                    ).format(
                        montantNombre
                    ) + ' FC';

            } else {

                resumeMontant.textContent =
                    '—';

            }

        } else {

            resumeMontant.textContent =
                'Montant non renseigné';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Restaurer après erreur
    |--------------------------------------------------------------------------
    */

    if (ancienneSection) {

        sectionSelect.value =
            ancienneSection;

        afficherClasses(
            ancienneSection,
            ancienneClasse
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Résumé initial
    |--------------------------------------------------------------------------
    */

    mettreAJourResume();

});

</script>

@endsection
