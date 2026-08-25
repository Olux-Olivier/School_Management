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
                class="inline-flex
                       items-center
                       justify-center
                       w-10 h-10
                       rounded-lg
                       bg-slate-100
                       text-slate-600
                       hover:bg-slate-200
                       transition">

                <i class="fas fa-arrow-left"></i>

            </a>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Modifier le frais
                </h1>

                <p class="text-sm text-slate-500 mt-1">

                    Modification du frais pour l'année scolaire :

                    <span class="font-semibold text-slate-700">
                        {{ $anneeScolaire->libelle }}
                    </span>

                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('frais.update', $frais) }}"
        method="POST"

        class="bg-white
               rounded-xl
               shadow-sm
               border border-slate-200
               overflow-hidden">

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- ERREURS --}}
        {{-- ===================================================== --}}

        @if($errors->any())

            <div
                class="mx-6 mt-6
                       p-4
                       rounded-lg
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
                            Veuillez corriger les erreurs suivantes :
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


        {{-- ===================================================== --}}
        {{-- ANNÉE SCOLAIRE --}}
        {{-- ===================================================== --}}

        <div
            class="mx-6 mt-6
                   p-4
                   rounded-xl
                   bg-slate-50
                   border border-slate-200">

            <div class="flex items-start">

                <i
                    class="fas fa-calendar
                           text-slate-500
                           mt-1
                           mr-3">
                </i>

                <div>

                    <p class="text-sm font-semibold text-slate-700">
                        Année scolaire
                    </p>

                    <p class="text-sm text-slate-600 mt-1">
                        {{ $anneeScolaire->libelle }}
                    </p>

                    <p class="text-xs text-slate-500 mt-1">
                        L'année scolaire n'est pas modifiable.
                        Le frais reste rattaché à cette année.
                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATIONS DU FRAIS --}}
        {{-- ===================================================== --}}

        <div class="p-6">

            <h2
                class="text-lg
                       font-semibold
                       text-slate-700">

                Informations du frais

            </h2>

            <p
                class="text-sm
                       text-slate-500
                       mt-1
                       mb-6">

                Modifiez les informations nécessaires.

            </p>


            <div class="space-y-6">


                {{-- ================================================= --}}
                {{-- INTITULÉ --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="intitule"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Intitulé du frais

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        name="intitule"
                        id="intitule"

                        value="{{ old('intitule', $frais->intitule) }}"

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
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

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

                        <option
                            value="Maternelle"
                            @selected(
                                old('section', $frais->section)
                                === 'Maternelle'
                            )>

                            Maternelle

                        </option>

                        <option
                            value="Primaire"
                            @selected(
                                old('section', $frais->section)
                                === 'Primaire'
                            )>

                            Primaire

                        </option>

                        <option
                            value="Secondaire"
                            @selected(
                                old('section', $frais->section)
                                === 'Secondaire'
                            )>

                            Secondaire

                        </option>

                        <option
                            value="Humanités"
                            @selected(
                                old('section', $frais->section)
                                === 'Humanités'
                            )>

                            Humanités

                        </option>

                    </select>

                    <p class="text-xs text-slate-500 mt-2">
                        Sélectionnez une section pour afficher
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
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Classe

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <select
                        name="classe_id"
                        id="classe_id"

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
                            -- Sélectionner une classe --
                        </option>

                    </select>

                    <p
                        id="classe-help"
                        class="text-xs text-slate-500 mt-2">

                        Les classes de la section sélectionnée
                        apparaîtront ici.

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
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Montant

                        <span class="text-red-500">
                            *
                        </span>

                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            name="montant"
                            id="montant"

                            value="{{ old('montant', $frais->montant) }}"

                            min="0"
                            step="0.01"

                            required

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   px-4 py-3
                                   pr-14
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
                                   font-medium
                                   text-slate-400">

                            FC

                        </span>

                    </div>

                    <div
                        class="mt-2
                               p-3
                               rounded-lg
                               bg-amber-50
                               border border-amber-200
                               text-sm
                               text-amber-700">

                        <i class="fas fa-info-circle mr-1"></i>

                        Vous pouvez modifier le montant.
                        Les anciens paiements resteront associés
                        à leur montant historique.

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
        {{-- RESUME --}}
        {{-- ===================================================== --}}

        <div
            id="resume"
            class="mx-6 mb-6
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

                        {{ old('intitule', $frais->intitule) }}

                    </p>

                    <p
                        id="resume-section"
                        class="text-sm
                               text-slate-500
                               mt-1">

                        Section :
                        {{ old('section', $frais->section) }}

                    </p>

                    <p
                        id="resume-classe"
                        class="text-sm
                               text-slate-500
                               mt-1">

                        Classe :
                        {{ $frais->classe->nom }}

                        @if($frais->classe->option)
                            - {{ $frais->classe->option }}
                        @endif

                    </p>

                    <p
                        id="resume-montant"
                        class="text-lg
                               font-bold
                               text-blue-600
                               mt-2">

                        {{ number_format(
                            old('montant', $frais->montant),
                            2,
                            ',',
                            ' '
                        ) }} FC

                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- BOUTONS --}}
        {{-- ===================================================== --}}

        <div
            class="px-6 py-5
                   bg-slate-50
                   border-t border-slate-200
                   flex
                   flex-col-reverse
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

                Enregistrer les modifications

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
    | Classes envoyées par Laravel
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
    | Valeurs initiales
    |--------------------------------------------------------------------------
    */

    const ancienneSection =
        @json(old('section', $frais->section));

    const ancienneClasse =
        @json(old('classe_id', $frais->classe_id));


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
                'Sélectionnez une section pour afficher ses classes.';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Filtrage exact
        |--------------------------------------------------------------------------
        |
        | Les valeurs sont :
        |
        | Maternelle
        | Primaire
        | Secondaire
        | Humanités
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
        | Classes
        |--------------------------------------------------------------------------
        */

        classesFiltrees.forEach(function (classe) {

            const option =
                document.createElement('option');

            option.value =
                classe.id;


            /*
            | Texte de la classe
            */

            let texte =
                classe.nom;


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
            | Sélectionner automatiquement
            | la classe actuelle
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
        | Activer
        */

        classeSelect.disabled = false;


        /*
        | Message
        */

        classeHelp.textContent =
            classesFiltrees.length +
            ' classe(s) active(s) disponible(s) dans cette section.';

    }


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

        const selectedOption =
            classeSelect.options[
                classeSelect.selectedIndex
            ];


        let classe =
            'Aucune classe sélectionnée';


        if (
            selectedOption &&
            selectedOption.value
        ) {

            classe =
                selectedOption.textContent;

        }


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
                ? 'Section : ' + section
                : 'Section : Non sélectionnée';


        /*
        | Classe
        */

        resumeClasse.textContent =
            'Classe : ' + classe;


        /*
        | Montant
        */

        const montant =
            montantInput.value;


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
    | Changement de section
    |--------------------------------------------------------------------------
    */

    sectionSelect.addEventListener(
        'change',
        function () {

            /*
            | Quand l'utilisateur change de section,
            | on ne conserve PAS l'ancienne classe.
            */

            afficherClasses(
                this.value,
                null
            );

            mettreAJourResume();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Changement de classe
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
    | INITIALISATION
    |--------------------------------------------------------------------------
    |
    | Au chargement :
    |
    | Humanités
    |      ↓
    | classes Humanités
    |      ↓
    | 1ère - Commerciale sélectionnée
    |
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
