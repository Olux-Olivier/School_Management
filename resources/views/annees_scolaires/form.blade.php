@extends('layouts.app')

@section('title', isset($annee) ? 'Modifier une année scolaire' : 'Ajouter une année scolaire')

@section('breadcrumb')
    Accueil / Années scolaires / {{ isset($annee) ? 'Modifier' : 'Ajouter' }}
@endsection

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border">

        <!-- En-tête -->

        <div class="px-6 py-5 border-b">

            <h2 class="text-xl font-bold text-slate-700">

                {{ isset($annee)
                    ? 'Modifier l’année scolaire'
                    : 'Ajouter une année scolaire' }}

            </h2>

            <p class="text-sm text-slate-500 mt-1">

                {{ isset($annee)
                    ? 'Modifiez les informations de cette année scolaire.'
                    : 'Enregistrez une nouvelle année scolaire.' }}

            </p>

        </div>


        <!-- Formulaire -->

        <form
            action="{{ isset($annee)
                ? route('annees.update', $annee)
                : route('annees.store') }}"
            method="POST">

            @csrf

            @if(isset($annee))

                @method('PUT')

            @endif


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    <!-- ================================================= -->
                    <!-- LIBELLE AUTOMATIQUE -->
                    <!-- ================================================= -->

                    <div class="md:col-span-2">

                        <label class="block mb-2 font-medium text-slate-700">

                            Année scolaire

                        </label>

                        <input
                            type="text"
                            name="libelle"
                            id="libelle"

                            value="{{ old(
                                'libelle',
                                $annee->libelle ?? ''
                            ) }}"

                            readonly

                            placeholder="Sera généré automatiquement"

                            class="w-full bg-gray-100 border rounded-lg
                            px-4 py-3 text-gray-700
                            cursor-not-allowed
                            focus:outline-none">

                        <p class="text-sm text-gray-500 mt-1">

                            Le libellé est généré automatiquement
                            à partir des dates.

                        </p>

                        @error('libelle')

                            <p class="text-red-500 text-sm mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- DATE DEBUT -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Date de début

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="date"
                            name="date_debut"
                            id="date_debut"

                            value="{{ old(
                                'date_debut',
                                isset($annee)
                                    ? $annee->date_debut->format('Y-m-d')
                                    : ''
                            ) }}"

                            class="w-full border rounded-lg
                            px-4 py-3
                            focus:ring-2 focus:ring-blue-500
                            focus:outline-none
                            @error('date_debut')
                                border-red-500
                            @enderror">

                        @error('date_debut')

                            <p class="text-red-500 text-sm mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                    <!-- ================================================= -->
                    <!-- DATE FIN -->
                    <!-- ================================================= -->

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Date de fin

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="date"
                            name="date_fin"
                            id="date_fin"

                            value="{{ old(
                                'date_fin',
                                isset($annee)
                                    ? $annee->date_fin->format('Y-m-d')
                                    : ''
                            ) }}"

                            class="w-full border rounded-lg
                            px-4 py-3
                            focus:ring-2 focus:ring-blue-500
                            focus:outline-none
                            @error('date_fin')
                                border-red-500
                            @enderror">

                        @error('date_fin')

                            <p class="text-red-500 text-sm mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>


                </div>

            </div>


            <!-- ================================================= -->
            <!-- BOUTONS -->
            <!-- ================================================= -->

            <div class="border-t px-6 py-4 flex justify-end gap-3">

                <a
                    href="{{ route('annees.index') }}"

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

                    {{ isset($annee) ? 'Modifier' : 'Enregistrer' }}

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

    const dateDebut = document.getElementById('date_debut');

    const dateFin = document.getElementById('date_fin');

    const libelle = document.getElementById('libelle');


    function genererLibelle() {

        /*
        |--------------------------------------------------------------------------
        | Vérifier que les deux dates existent
        |--------------------------------------------------------------------------
        */

        if (!dateDebut.value || !dateFin.value) {

            libelle.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Récupération des dates
        |--------------------------------------------------------------------------
        */

        const debut = new Date(dateDebut.value);

        const fin = new Date(dateFin.value);


        const anneeDebut = debut.getFullYear();

        const anneeFin = fin.getFullYear();


        /*
        |--------------------------------------------------------------------------
        | Date de fin avant ou égale à la date de début
        |--------------------------------------------------------------------------
        */

        if (fin <= debut) {

            libelle.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que les années sont consécutives
        |
        | Exemple accepté :
        |
        | 2026 → 2027
        |
        | Exemple refusé :
        |
        | 2026 → 2028
        | 2027 → 2026
        |--------------------------------------------------------------------------
        */

        if (anneeFin - anneeDebut !== 1) {

            libelle.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Génération automatique
        |--------------------------------------------------------------------------
        */

        libelle.value = `${anneeDebut}-${anneeFin}`;

    }


    /*
    |--------------------------------------------------------------------------
    | Quand la date de début change
    |--------------------------------------------------------------------------
    */

    dateDebut.addEventListener('change', function () {

        genererLibelle();

    });


    /*
    |--------------------------------------------------------------------------
    | Quand la date de fin change
    |--------------------------------------------------------------------------
    */

    dateFin.addEventListener('change', function () {

        genererLibelle();

    });


    /*
    |--------------------------------------------------------------------------
    | Génération automatique au chargement
    |
    | Utile pour la modification
    |--------------------------------------------------------------------------
    */

    genererLibelle();

});

</script>

@endsection
