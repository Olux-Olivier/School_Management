@extends('layouts.app')

@section('title', 'Élèves')

@section('content')

<div class="max-w-7xl mx-auto py-8">

    {{-- EN-TÊTE --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-700">
                Élèves
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Gestion et consultation des élèves.
            </p>
        </div>

        <a href="{{ route('eleves.create') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Ajouter un élève
        </a>
    </div>

    {{-- MESSAGE DE SUCCÈS --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- RECHERCHE --}}
    <div class="bg-white rounded-xl shadow-sm border mb-6">
        <div class="p-5">
            <label for="searchInput"
                   class="block text-sm font-medium text-slate-700 mb-2">
                Rechercher un élève
            </label>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>

                <input
                    type="text"
                    id="searchInput"
                    value="{{ $search ?? '' }}"
                    placeholder="Matricule, nom, postnom, prénom ou téléphone..."
                    autocomplete="off"
                    class="w-full border rounded-lg pl-11 pr-10 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >

                <button
                    type="button"
                    id="clearSearch"
                    class="{{ !empty($search) ? '' : 'hidden' }} absolute inset-y-0 right-0 px-4 text-slate-400 hover:text-slate-700"
                    title="Effacer la recherche">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <p class="text-xs text-slate-400 mt-2">
                Saisissez au moins une partie du matricule ou du nom pour rechercher un élève.
            </p>
        </div>
    </div>

    {{-- AFFICHER LES RÉSULTATS UNIQUEMENT APRÈS UNE RECHERCHE --}}
    @if($rechercheEffectuee)

        {{-- INFORMATIONS SUR LES RÉSULTATS --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
            <div class="text-sm text-slate-500">
                @if($eleves->total() > 0)
                    Affichage de
                    <span class="font-semibold text-slate-700">
                        {{ $eleves->firstItem() }}
                    </span>
                    à
                    <span class="font-semibold text-slate-700">
                        {{ $eleves->lastItem() }}
                    </span>
                    sur
                    <span class="font-semibold text-slate-700">
                        {{ $eleves->total() }}
                    </span>
                    élève(s)
                @else
                    Aucun élève trouvé.
                @endif
            </div>

            <div class="text-sm text-slate-500">
                Recherche :
                <span class="font-semibold text-blue-600">
                    "{{ $search }}"
                </span>
            </div>
        </div>

        {{-- TABLEAU DES ÉLÈVES --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Matricule
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Élève
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Sexe
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Téléphone
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">
                                Statut
                            </th>

                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($eleves as $eleve)
                            <tr class="hover:bg-slate-50 transition">

                                {{-- MATRICULE --}}
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-blue-600">
                                        {{ $eleve->matricule }}
                                    </span>
                                </td>

                                {{-- IDENTITÉ --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">

                                        @if($eleve->photo)
                                            <img
                                                src="{{ asset('storage/' . $eleve->photo) }}"
                                                alt="Photo de {{ $eleve->nom_complet }}"
                                                class="w-10 h-10 rounded-full object-cover border">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                                                <i class="fas fa-user text-slate-400"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-slate-700">
                                                {{ $eleve->nom_complet }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- SEXE --}}
                                <td class="px-6 py-4">
                                    <span class="text-slate-600">
                                        {{ $eleve->sexe_libelle }}
                                    </span>
                                </td>

                                {{-- TÉLÉPHONE --}}
                                <td class="px-6 py-4">
                                    <span class="text-slate-600">
                                        {{ $eleve->telephone ?: '-' }}
                                    </span>
                                </td>

                                {{-- STATUT --}}
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm
                                        {{ $eleve->actif
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700' }}">
                                        {{ $eleve->statut_libelle }}
                                    </span>
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">

                                        <a
                                            href="{{ route('eleves.show', $eleve) }}"
                                            title="Consulter"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a
                                            href="{{ route('eleves.edit', $eleve) }}"
                                            title="Modifier"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center">
                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-users text-2xl text-slate-400"></i>
                                        </div>

                                        <p class="font-medium text-slate-600">
                                            Aucun élève trouvé.
                                        </p>

                                        <p class="text-sm text-slate-400 mt-1">
                                            Aucun résultat pour « {{ $search }} ».
                                        </p>

                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($eleves->hasPages())
                <div class="px-6 py-4 border-t flex justify-center">
                    {{ $eleves->links() }}
                </div>
            @endif
        </div>

    @else

        {{-- ÉTAT INITIAL : AUCUN ÉLÈVE AFFICHÉ --}}
        <div class="bg-white rounded-xl shadow-sm border px-6 py-14 text-center">
            <div class="flex flex-col items-center">

                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                    <i class="fas fa-search text-2xl text-blue-500"></i>
                </div>

                <h2 class="font-semibold text-slate-700">
                    Rechercher un élève
                </h2>

                <p class="text-sm text-slate-500 mt-2 max-w-md">
                    Utilisez la barre de recherche ci-dessus pour retrouver un élève par son matricule, son nom, son postnom, son prénom ou son téléphone.
                </p>

            </div>
        </div>

    @endif

</div>

{{-- RECHERCHE DYNAMIQUE --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');

    let timer = null;

    searchInput.addEventListener('input', function () {

        clearTimeout(timer);

        const search = this.value.trim();

        if (search.length > 0) {
            clearSearch.classList.remove('hidden');
        } else {
            clearSearch.classList.add('hidden');
        }

        timer = setTimeout(function () {

            // Si le champ est vide, revenir à la page initiale.
            if (search === '') {
                window.location.href = "{{ route('eleves.index') }}";
                return;
            }

            const url = new URL(
                "{{ route('eleves.index') }}",
                window.location.origin
            );

            url.searchParams.set('search', search);
            url.searchParams.set('rechercher', '1');
            url.searchParams.set('page', '1');

            window.location.href = url.toString();

        }, 1500);
    });

    clearSearch.addEventListener('click', function () {

        searchInput.value = '';

        window.location.href = "{{ route('eleves.index') }}";
    });

});
</script>

@endsection
