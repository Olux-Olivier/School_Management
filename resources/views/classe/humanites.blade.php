@extends('layouts.app')

@section('title', 'Classes')

@section('breadcrumb')
Liste des Classes - Humanités
@endsection

@section('content')

<div class="max-w-7xl mx-auto py-8">


<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-700">
            Classes - Humanités
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Gestion des classes des Humanités.
        </p>
    </div>

    <a
        href="{{ route('classes.create', ['section' => 'Humanités']) }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl
               bg-blue-600 px-5 py-2.5 text-sm font-medium text-white
               shadow-sm transition hover:bg-blue-700"
    >
        <i class="fas fa-plus text-xs"></i>
        Ajouter une classe
    </a>

</div>


{{-- NAVIGATION --}}
<div class="bg-white border rounded-xl shadow-sm mb-6">

    <div class="flex flex-wrap gap-2 p-3">

        <a
            href="{{ route('classes.maternelle') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium
                   text-slate-600 hover:bg-slate-100"
        >
            Maternelle
        </a>

        <a
            href="{{ route('classes.primaire') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium
                   text-slate-600 hover:bg-slate-100"
        >
            Primaire
        </a>

        <a
            href="{{ route('classes.secondaire') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium
                   text-slate-600 hover:bg-slate-100"
        >
            Secondaire
        </a>

        <a
            href="{{ route('classes.humanites') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium
                   bg-blue-600 text-white"
        >
            Humanités
        </a>

    </div>

</div>


@if(session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50
                px-5 py-4 text-sm text-green-700">

        <i class="fas fa-check-circle mr-2"></i>

        {{ session('success') }}

    </div>

@endif


{{-- RECHERCHE --}}
<div class="bg-white border rounded-xl shadow-sm mb-6">

    <div class="p-4">

        <div class="relative max-w-md">

            <i class="fas fa-search absolute left-4 top-1/2
                      -translate-y-1/2 text-slate-400"></i>

            <input
                type="text"
                id="searchClasse"
                placeholder="Rechercher une classe, une option..."
                class="w-full rounded-lg border border-slate-300
                       py-2.5 pl-11 pr-4 text-sm
                       focus:border-blue-500 focus:ring-2
                       focus:ring-blue-200 focus:outline-none"
            >

        </div>

    </div>

</div>


{{-- TABLE --}}
<div class="bg-white border rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 border-b">

                <tr>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Classe
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Section
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Niveau
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Option
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Variante
                    </th>

                    <th class="px-6 py-4 text-left font-semibold text-slate-600">
                        Statut
                    </th>

                    <th class="px-6 py-4 text-right font-semibold text-slate-600">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($classes as $classe)

                    <tr
                        class="classe-row hover:bg-slate-50 transition"
                        data-search="{{ strtolower(
                            $classe->nom_complet . ' ' .
                            $classe->section . ' ' .
                            ($classe->option ?? '') . ' ' .
                            ($classe->variante ?? '')
                        ) }}"
                    >

                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $classe->nom }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $classe->section }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $classe->niveau_libelle }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $classe->option ?? '—' }}
                        </td>

                        <td class="px-6 py-4">

                            @if($classe->variante)

                                <span class="inline-flex items-center rounded-full
                                             bg-blue-100 px-3 py-1 text-xs
                                             font-semibold text-blue-700">
                                    {{ $classe->variante }}
                                </span>

                            @else

                                <span class="text-slate-400">
                                    —
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">

                            <span
                                class="inline-flex items-center rounded-full px-3 py-1
                                text-xs font-semibold
                                {{ $classe->actif
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}"
                            >
                                {{ $classe->statut_libelle }}
                            </span>

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route('classes.show', $classe) }}"
                                    class="w-9 h-9 inline-flex items-center justify-center
                                           rounded-lg bg-slate-100 text-slate-600
                                           hover:bg-slate-200"
                                    title="Voir"
                                >
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                <a
                                    href="{{ route('classes.edit', $classe) }}"
                                    class="w-9 h-9 inline-flex items-center justify-center
                                           rounded-lg bg-blue-100 text-blue-600
                                           hover:bg-blue-200"
                                    title="Modifier"
                                >
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-12 text-center text-slate-500">

                            <i class="fas fa-school text-3xl mb-3 text-slate-300"></i>

                            <p>
                                Aucune classe des Humanités enregistrée.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('searchClasse');

    const rows = document.querySelectorAll('.classe-row');

    search.addEventListener('input', function () {

        const valeur = this.value.toLowerCase().trim();

        rows.forEach(function (row) {

            row.style.display =
                row.dataset.search.includes(valeur)
                    ? ''
                    : 'none';

        });

    });

});

</script>

@endsection
