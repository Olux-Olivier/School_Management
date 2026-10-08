@extends('layouts.app')

@section('title', 'Inscriptions')

@section('content')

<div class="w-full max-w-none px-4 sm:px-6 lg:px-8 py-8">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Inscriptions
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Recherchez les inscriptions par année scolaire, section ou classe.
            </p>
        </div>

        <a href="{{ route('inscriptions.create') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle inscription
        </a>
    </div>

    {{-- MESSAGE DE SUCCÈS --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTRES DE RECHERCHE --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-slate-800">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filtres de recherche
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Sélectionnez au moins un critère pour afficher les inscriptions.
            </p>
        </div>

        <form method="GET"
              action="{{ route('inscriptions.index') }}"
              id="searchForm">

            <input type="hidden" name="rechercher" value="1">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- ANNÉE SCOLAIRE --}}
                <div>
                    <label for="annee_scolaire_id"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Année scolaire
                    </label>

                    <select name="annee_scolaire_id"
                            id="annee_scolaire_id"
                            class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <option value="">Toutes les années</option>

                        @foreach($anneesScolaires as $annee)
                            <option value="{{ $annee->id }}"
                                {{ request('annee_scolaire_id') == $annee->id ? 'selected' : '' }}>
                                {{ $annee->libelle }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- SECTION --}}
                <div>
                    <label for="section"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Section
                    </label>

                    <select name="section"
                            id="section"
                            class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <option value="">Toutes les sections</option>

                        <option value="maternelle"
                            {{ request('section') === 'maternelle' ? 'selected' : '' }}>
                            Maternelle
                        </option>

                        <option value="primaire"
                            {{ request('section') === 'primaire' ? 'selected' : '' }}>
                            Primaire
                        </option>

                        <option value="secondaire"
                            {{ request('section') === 'secondaire' ? 'selected' : '' }}>
                            Secondaire
                        </option>

                        <option value="humanites"
                            {{ request('section') === 'humanites' ? 'selected' : '' }}>
                            Humanités
                        </option>

                    </select>
                </div>

                {{-- CLASSE --}}
                <div>
                    <label for="classe_id"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Classe
                    </label>

                    <select name="classe_id"
                            id="classe_id"
                            class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <option value="">Toutes les classes</option>

                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}"
                                    data-niveau="{{ $classe->niveau }}"
                                {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom_complet }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- RECHERCHE ÉLÈVE --}}
                <div>
                    <label for="search"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Élève (facultatif)
                    </label>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>

                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Matricule ou nom..."
                               autocomplete="off"
                               class="w-full border border-slate-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

            </div>

            {{-- BOUTONS --}}
            <div class="flex flex-wrap justify-end gap-3 mt-5">

                <a href="{{ route('inscriptions.index') }}"
                   class="inline-flex items-center px-4 py-2.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                    <i class="fas fa-rotate-left mr-2"></i>
                    Réinitialiser
                </a>

                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>

            </div>

        </form>
    </div>

    {{-- RÉSULTATS : AFFICHÉS UNIQUEMENT APRÈS RECHERCHE --}}
    @if($rechercheEffectuee && $criteresRenseignes)

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- EN-TÊTE DES RÉSULTATS --}}
            <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        Résultats de la recherche
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $inscriptions->total() }}
                        inscription(s) trouvée(s)
                    </p>
                </div>

            </div>

            {{-- TABLEAU --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Élève
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Année scolaire
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Section
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Classe
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Date
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Montant
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">
                                Statut
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-slate-600 whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse($inscriptions as $inscription)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- ÉLÈVE --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $inscription->eleve->nom ?? '' }}
                                        {{ $inscription->eleve->postnom ?? '' }}
                                        {{ $inscription->eleve->prenom ?? '' }}
                                    </p>

                                    <p class="text-xs text-blue-600 mt-1">
                                        {{ $inscription->eleve->matricule ?? '—' }}
                                    </p>
                                </td>

                                {{-- ANNÉE --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $inscription->anneeScolaire->libelle ?? '—' }}
                                </td>

                                {{-- SECTION --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $sectionLibelle = match ((int) ($inscription->classe->niveau ?? -1)) {
                                            0 => 'Maternelle',
                                            1 => 'Primaire',
                                            2 => 'Secondaire',
                                            3 => 'Humanités',
                                            default => '—',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ $sectionLibelle }}
                                    </span>
                                </td>

                                {{-- CLASSE --}}
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-700">
                                        {{ $inscription->classe->nom_complet
                                            ?? $inscription->classe->nom
                                            ?? '—' }}
                                    </p>
                                </td>

                                {{-- DATE --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $inscription->date_inscription
                                        ? $inscription->date_inscription->format('d/m/Y')
                                        : '—' }}
                                </td>

                                {{-- MONTANT --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-slate-700">
                                        {{ number_format($inscription->montant ?? 0, 2, ',', ' ') }}
                                        FC
                                    </span>
                                </td>

                                {{-- STATUT --}}
                                <td class="px-6 py-4">
                                    @if($inscription->actif)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        <a href="{{ route('inscriptions.show', $inscription) }}"
                                           title="Consulter"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('inscriptions.edit', $inscription) }}"
                                           title="Modifier"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="{{ route('inscriptions.pdf', $inscription) }}"
                                           target="_blank"
                                           title="Télécharger le PDF"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                            <i class="fas fa-search text-xl"></i>
                                        </div>

                                        <p class="font-medium text-slate-700">
                                            Aucune inscription trouvée
                                        </p>

                                        <p class="text-sm text-slate-500 mt-1">
                                            Essayez de modifier vos critères de recherche.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            @if($inscriptions->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $inscriptions->withQueryString()->links() }}
                </div>
            @endif

        </div>

    @elseif($rechercheEffectuee)

        {{-- RECHERCHE SANS CRITÈRE --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
            <i class="fas fa-filter text-amber-600 text-2xl mb-3"></i>

            <p class="font-semibold text-amber-800">
                Sélectionnez au moins un critère
            </p>

            <p class="text-sm text-amber-700 mt-1">
                Choisissez une année scolaire, une section, une classe ou saisissez le nom d'un élève.
            </p>
        </div>

    @else

        {{-- ÉCRAN INITIAL : AUCUNE LISTE --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">

            <div class="w-16 h-16 mx-auto rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mb-4">
                <i class="fas fa-magnifying-glass text-2xl"></i>
            </div>

            <h2 class="text-lg font-semibold text-slate-800">
                Rechercher des inscriptions
            </h2>

            <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                Sélectionnez les critères souhaités puis cliquez sur
                « Rechercher » pour afficher uniquement les inscriptions correspondantes.
            </p>

        </div>

    @endif

</div>

{{-- FILTRAGE DES CLASSES SELON LA SECTION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sectionSelect = document.getElementById('section');
    const classeSelect = document.getElementById('classe_id');

    function filtrerClasses() {
        const section = sectionSelect.value;

        const niveaux = {
            maternelle: '0',
            primaire: '1',
            secondaire: '2',
            humanites: '3'
        };

        const niveauRecherche = niveaux[section];
        const classeActuelle = classeSelect.value;

        Array.from(classeSelect.options).forEach(function (option) {

            if (option.value === '') {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const correspond =
                !niveauRecherche ||
                option.dataset.niveau === niveauRecherche;

            option.hidden = !correspond;
            option.disabled = !correspond;
        });

        // Si la classe sélectionnée ne correspond plus à la section,
        // on réinitialise la classe.
        const optionSelectionnee = classeSelect.options[classeSelect.selectedIndex];

        if (
            classeActuelle &&
            optionSelectionnee &&
            optionSelectionnee.dataset.niveau &&
            niveauRecherche &&
            optionSelectionnee.dataset.niveau !== niveauRecherche
        ) {
            classeSelect.value = '';
        }
    }

    sectionSelect.addEventListener('change', filtrerClasses);

    // Appliquer le filtre lors de l'ouverture des résultats.
    filtrerClasses();

});
</script>

@endsection

