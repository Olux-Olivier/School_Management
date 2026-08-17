<form
    action="{{ isset($inscription)
        ? route('inscriptions.update', $inscription)
        : route('inscriptions.store') }}"
    method="POST"
    class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

    @csrf

    @isset($inscription)
        @method('PUT')
    @endisset


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="mx-6 mt-6 p-4 rounded-lg
                    bg-red-50 border border-red-200 text-red-700">

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
    {{-- ÉLÈVE --}}
    {{-- ========================================================= --}}

    <div class="p-6 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Élève
        </h2>

        <p class="text-sm text-slate-500 mt-1 mb-6">
            Recherchez et sélectionnez l'élève à inscrire.
        </p>


        <div>

            <label
                for="searchEleve"
                class="block text-sm font-medium text-slate-700 mb-2">

                Élève <span class="text-red-500">*</span>

            </label>


            <div class="relative">

                <div
                    class="absolute inset-y-0 left-0
                           flex items-center pl-4
                           pointer-events-none">

                    <i class="fas fa-search text-slate-400"></i>

                </div>


                <input
                    type="text"
                    id="searchEleve"

                    value="{{ old(
                        'eleve_label',
                        isset($inscription)
                            ? $inscription->eleve->matricule
                              . ' - '
                              . $inscription->eleve->nom_complet
                            : ''
                    ) }}"

                    placeholder="Rechercher par matricule, nom, postnom ou prénom..."

                    autocomplete="off"

                    class="w-full border border-slate-300
                           rounded-lg
                           pl-11 pr-4 py-3
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           focus:outline-none">


                <div
                    id="eleveResults"

                    class="hidden absolute z-50
                           left-0 right-0 mt-1
                           bg-white
                           border border-slate-200
                           rounded-lg
                           shadow-lg
                           max-h-64
                           overflow-y-auto">
                </div>

            </div>


            {{-- ID réel de l'élève --}}

            <input
                type="hidden"
                name="eleve_id"
                id="eleve_id"

                value="{{ old(
                    'eleve_id',
                    $inscription->eleve_id ?? ''
                ) }}">


            {{-- Élève sélectionné --}}

            <div
                id="selectedEleve"

                class="
                    {{ old(
                        'eleve_id',
                        $inscription->eleve_id ?? ''
                    )
                        ? ''
                        : 'hidden' }}

                    mt-3
                    p-4
                    bg-blue-50
                    border border-blue-200
                    rounded-lg
                ">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs text-blue-600 font-medium">
                            Élève sélectionné
                        </p>

                        <p
                            id="selectedEleveName"
                            class="font-semibold text-slate-700">

                            @if(isset($inscription))

                                {{ $inscription->eleve->matricule }}
                                -
                                {{ $inscription->eleve->nom_complet }}

                            @endif

                        </p>

                    </div>


                    <button
                        type="button"
                        id="removeEleve"

                        class="w-8 h-8
                               flex items-center
                               justify-center
                               rounded-full
                               text-red-500
                               hover:bg-red-100
                               hover:text-red-700
                               transition">

                        <i class="fas fa-times"></i>

                    </button>

                </div>

            </div>


            @error('eleve_id')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

            @enderror

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ANNÉE SCOLAIRE + DATE --}}
    {{-- ========================================================= --}}

    <div class="p-6 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Informations de l'inscription
        </h2>

        <p class="text-sm text-slate-500 mt-1 mb-6">
            L'inscription sera automatiquement enregistrée
            dans l'année scolaire active.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- Année scolaire active --}}

            <div>

                <label
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Année scolaire

                </label>


                <input
                    type="text"

                    value="{{ $anneeScolaire->libelle ?? 'Aucune année active' }}"

                    readonly

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           bg-slate-100
                           text-slate-600
                           px-4 py-3
                           cursor-not-allowed">


                @if(!$anneeScolaire)

                    <p class="text-red-500 text-sm mt-1">

                        Aucune année scolaire active
                        n'est actuellement disponible.

                    </p>

                @endif

            </div>


            {{-- Date d'inscription --}}

            <div>

                <label
                    for="date_inscription"
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Date d'inscription
                    <span class="text-red-500">*</span>

                </label>


                <input
                    type="date"
                    name="date_inscription"
                    id="date_inscription"

                    value="{{ old(
                        'date_inscription',
                        isset($inscription)
                            ? $inscription->date_inscription->format('Y-m-d')
                            : date('Y-m-d')
                    ) }}"

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3
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

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CLASSE --}}
    {{-- ========================================================= --}}

    <div class="p-6 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Classe
        </h2>

        <p class="text-sm text-slate-500 mt-1 mb-6">
            Sélectionnez d'abord la section, puis la classe.
        </p>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- Section --}}

            <div>

                <label
                    for="section"
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Section <span class="text-red-500">*</span>

                </label>


                <select
                    id="section"
                    name="section"

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           focus:outline-none">

                    <option value="">
                        -- Sélectionner une section --
                    </option>


                    <option
                        value="maternelle"

                        {{ old(
                            'section',
                            $inscription->classe->section ?? ''
                        ) == 'maternelle'
                            ? 'selected'
                            : '' }}>

                        Maternelle

                    </option>


                    <option
                        value="primaire"

                        {{ old(
                            'section',
                            $inscription->classe->section ?? ''
                        ) == 'primaire'
                            ? 'selected'
                            : '' }}>

                        Primaire

                    </option>


                    <option
                        value="secondaire"

                        {{ old(
                            'section',
                            $inscription->classe->section ?? ''
                        ) == 'secondaire'
                            ? 'selected'
                            : '' }}>

                        Secondaire

                    </option>


                    <option
                        value="humanites"

                        {{ old(
                            'section',
                            $inscription->classe->section ?? ''
                        ) == 'humanites'
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


            {{-- Classe --}}

            <div>

                <label
                    for="classe_id"
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Classe <span class="text-red-500">*</span>

                </label>


                <select
                    name="classe_id"
                    id="classe_id"

                    disabled

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3
                           bg-slate-100
                           text-slate-600
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           focus:outline-none">

                    <option value="">
                        -- Sélectionner d'abord une section --
                    </option>

                </select>


                @error('classe_id')

                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MONTANT --}}
    {{-- ========================================================= --}}

    <div class="p-6 border-b border-slate-200">

        <h2 class="text-lg font-semibold text-slate-700">
            Paiement
        </h2>

        <p class="text-sm text-slate-500 mt-1 mb-6">
            Indiquez le montant versé pour cette inscription.
        </p>


        <div class="max-w-md">

            <label
                for="montant"
                class="block text-sm font-medium text-slate-700 mb-2">

                Montant
                <span class="text-red-500">*</span>

            </label>


            <div class="relative">

                <input
                    type="number"
                    name="montant"
                    id="montant"

                    value="{{ old(
                        'montant',
                        $inscription->montant ?? ''
                    ) }}"

                    min="0"
                    step="0.01"

                    placeholder="Exemple : 150000"

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3 pr-14
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


    {{-- ========================================================= --}}
    {{-- BOUTONS --}}
    {{-- ========================================================= --}}

    <div
        class="px-6 py-5
               bg-slate-50
               flex flex-col-reverse
               md:flex-row
               md:justify-end
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

            Annuler

        </a>


        <button
            type="submit"

            class="px-5 py-2.5
                   rounded-lg
                   bg-blue-600
                   text-white
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-save mr-2"></i>

            {{ isset($inscription)
                ? 'Modifier l’inscription'
                : 'Enregistrer l’inscription' }}

        </button>

    </div>

</form>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE ÉLÈVE
    |--------------------------------------------------------------------------
    */

    const searchEleve =
        document.getElementById('searchEleve');

    const eleveResults =
        document.getElementById('eleveResults');

    const eleveId =
        document.getElementById('eleve_id');

    const selectedEleve =
        document.getElementById('selectedEleve');

    const selectedEleveName =
        document.getElementById('selectedEleveName');

    const removeEleve =
        document.getElementById('removeEleve');


    let searchTimer;


    searchEleve.addEventListener('input', function () {

        clearTimeout(searchTimer);


        const search =
            this.value.trim();


        if (search.length < 2) {

            eleveResults.innerHTML = '';

            eleveResults.classList.add('hidden');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Recherche après 2 secondes
        |--------------------------------------------------------------------------
        */

        searchTimer = setTimeout(function () {

            fetch(
                "{{ route('inscriptions.search-eleves') }}"
                + "?search="
                + encodeURIComponent(search),

                {
                    headers: {
                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'
                    }
                }
            )

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Erreur lors de la recherche.'
                    );

                }

                return response.json();

            })

            .then(data => {

                eleveResults.innerHTML = '';


                if (data.length === 0) {

                    eleveResults.innerHTML = `

                        <div class="p-4 text-sm text-slate-500">

                            Aucun élève trouvé.

                        </div>

                    `;

                    eleveResults.classList.remove(
                        'hidden'
                    );

                    return;
                }


                data.forEach(function (eleve) {

                    const item =
                        document.createElement('button');


                    item.type = 'button';


                    item.className = `
                        w-full
                        text-left
                        px-4
                        py-3
                        hover:bg-blue-50
                        border-b
                        border-slate-100
                        last:border-b-0
                        transition
                    `;


                    item.innerHTML = `

                        <div class="font-semibold text-slate-700">

                            ${eleve.nom}
                            ${eleve.postnom ?? ''}
                            ${eleve.prenom ?? ''}

                        </div>

                        <div class="text-sm text-blue-600">

                            ${eleve.matricule}

                        </div>

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            eleveId.value =
                                eleve.id;


                            const nomComplet =
                                eleve.nom
                                + ' '
                                + (eleve.postnom ?? '')
                                + ' '
                                + (eleve.prenom ?? '');


                            const label =
                                eleve.matricule
                                + ' - '
                                + nomComplet;


                            searchEleve.value =
                                label;


                            selectedEleveName.textContent =
                                label;


                            selectedEleve.classList.remove(
                                'hidden'
                            );


                            eleveResults.innerHTML = '';

                            eleveResults.classList.add(
                                'hidden'
                            );

                        }
                    );


                    eleveResults.appendChild(item);

                });


                eleveResults.classList.remove(
                    'hidden'
                );

            })

            .catch(error => {

                console.error(
                    'Erreur recherche élève :',
                    error
                );

            });

        }, 2000);

    });


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER L'ÉLÈVE
    |--------------------------------------------------------------------------
    */

    removeEleve.addEventListener(
        'click',
        function () {

            eleveId.value = '';

            searchEleve.value = '';

            selectedEleveName.textContent = '';

            selectedEleve.classList.add(
                'hidden'
            );

            eleveResults.innerHTML = '';

            eleveResults.classList.add(
                'hidden'
            );

            searchEleve.focus();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SECTION → CLASSE
    |--------------------------------------------------------------------------
    */

    const section =
        document.getElementById('section');

    const classe =
        document.getElementById('classe_id');


    function chargerClasses(
        sectionValue,
        classeSelectionnee = null
    ) {

        classe.innerHTML = `
            <option value="">
                Chargement des classes...
            </option>
        `;

        classe.disabled = true;


        if (!sectionValue) {

            classe.innerHTML = `
                <option value="">
                    -- Sélectionner d'abord une section --
                </option>
            `;

            classe.disabled = true;

            return;
        }


        fetch(
            "{{ route('inscriptions.classes') }}"
            + "?section="
            + encodeURIComponent(sectionValue),

            {
                headers: {
                    'X-Requested-With':
                        'XMLHttpRequest',

                    'Accept':
                        'application/json'
                }
            }
        )

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Erreur lors du chargement des classes.'
                );

            }

            return response.json();

        })

        .then(data => {

            classe.innerHTML = `
                <option value="">
                    -- Sélectionner une classe --
                </option>
            `;


            if (data.length === 0) {

                classe.innerHTML = `
                    <option value="">
                        Aucune classe disponible
                    </option>
                `;

                classe.disabled = true;

                return;
            }


            data.forEach(function (item) {

                const option =
                    document.createElement('option');


                option.value =
                    item.id;


                option.textContent =
                    item.nom_complet;


                if (
                    classeSelectionnee
                    &&
                    classeSelectionnee == item.id
                ) {

                    option.selected = true;

                }


                classe.appendChild(option);

            });


            classe.disabled = false;

        })

        .catch(error => {

            console.error(
                'Erreur chargement classes :',
                error
            );


            classe.innerHTML = `
                <option value="">
                    Erreur lors du chargement
                </option>
            `;

            classe.disabled = true;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT DE SECTION
    |--------------------------------------------------------------------------
    */

    section.addEventListener(
        'change',
        function () {

            chargerClasses(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION
    |--------------------------------------------------------------------------
    */

    @if(isset($inscription))

        chargerClasses(
            @json($inscription->classe->section),
            @json($inscription->classe_id)
        );

    @else

        classe.innerHTML = `
            <option value="">
                -- Sélectionner d'abord une section --
            </option>
        `;

        classe.disabled = true;

    @endif

});

</script>
