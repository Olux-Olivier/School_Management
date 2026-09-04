@extends('layouts.app')

@section('title', 'Détails de la classe')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    <div>

        {{-- Retour --}}
        <a
            href="{{ route('classes.index') }}"
            class="inline-flex items-center
                   text-sm
                   text-slate-500
                   hover:text-blue-600
                   transition
                   mb-3">

            <i class="fas fa-arrow-left mr-2"></i>

            Retour à la liste des classes

        </a>


        <h1 class="text-2xl font-bold text-slate-800">

            {{ $classe->nom_complet }}

        </h1>


        <p class="text-sm text-slate-500 mt-1">

            Consultation de la classe

        </p>

    </div>


    {{-- Actions --}}
    <div class="flex flex-wrap gap-3">


        {{-- Retour à la liste --}}
        <a
            href="{{ route('classes.index') }}"

            class="inline-flex items-center
                   justify-center
                   px-5 py-2.5
                   rounded-lg
                   bg-slate-200
                   text-slate-700
                   hover:bg-slate-300
                   transition">

            <i class="fas fa-list mr-2"></i>

            Liste des classes

        </a>


        {{-- Modifier --}}
        <a
            href="{{ route('classes.edit', $classe) }}"

            class="inline-flex items-center
                   justify-center
                   px-5 py-2.5
                   rounded-lg
                   bg-blue-600
                   text-white
                   hover:bg-blue-700
                   transition">

            <i class="fas fa-edit mr-2"></i>

            Modifier la classe

        </a>

    </div>

</div>



{{-- ========================================================= --}}
{{-- INFORMATIONS DE LA CLASSE --}}
{{-- ========================================================= --}}

<div
    class="bg-white
           rounded-xl
           shadow-sm
           border border-slate-200
           overflow-hidden
           mb-6">


    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">


            {{-- Nom --}}

            <div>

                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    Classe
                </p>

                <p class="text-lg font-semibold text-slate-800 mt-1">

                    {{ $classe->nom }}

                </p>

            </div>


            {{-- Niveau --}}

            <div>

                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    Niveau
                </p>

                <p class="text-lg font-semibold text-slate-800 mt-1">

                    {{ $classe->niveau_libelle }}

                </p>

            </div>


            {{-- Section --}}

            <div>

                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    Section
                </p>

                <p class="text-lg font-semibold text-slate-800 mt-1">

                    {{ $classe->section ?: '—' }}

                </p>

            </div>


            {{-- Option --}}

            <div>

                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    Option
                </p>

                <p class="text-lg font-semibold text-slate-800 mt-1">

                    {{ $classe->option ?: '—' }}

                </p>

            </div>


            {{-- Variante --}}

            <div>

                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                    Variante
                </p>

                <div class="mt-1">

                    @if($classe->variante)

                        <span
                            class="inline-flex items-center justify-center
                                   min-w-10 h-9 px-3
                                   rounded-lg
                                   bg-indigo-100
                                   text-indigo-700
                                   font-bold">

                            {{ $classe->variante }}

                        </span>

                    @else

                        <span class="text-lg font-semibold text-slate-400">
                            —
                        </span>

                    @endif

                </div>

            </div>

        </div>


        {{-- Nom complet --}}

        <div class="mt-6 pt-5 border-t border-slate-100">

            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                Désignation complète
            </p>

            <p class="text-lg font-semibold text-blue-600 mt-1">

                {{ $classe->nom_complet }}

            </p>

        </div>


        {{-- Statut --}}

        <div class="mt-6 pt-5 border-t border-slate-100">

            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">
                Statut
            </p>


            @if($classe->actif)

                <span
                    class="inline-flex items-center
                           px-4 py-2
                           rounded-full
                           bg-green-100
                           text-green-700
                           text-sm
                           font-medium">

                    <span
                        class="w-2 h-2
                               rounded-full
                               bg-green-500
                               mr-2">
                    </span>

                    Active

                </span>

            @else

                <span
                    class="inline-flex items-center
                           px-4 py-2
                           rounded-full
                           bg-red-100
                           text-red-700
                           text-sm
                           font-medium">

                    <span
                        class="w-2 h-2
                               rounded-full
                               bg-red-500
                               mr-2">
                    </span>

                    Inactive

                </span>

            @endif

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- ANNÉE SCOLAIRE --}}
{{-- ========================================================= --}}

<div
    class="bg-white
           rounded-xl
           shadow-sm
           border border-slate-200
           p-6
           mb-6">


    <form
        method="GET"
        action="{{ route('classes.show', $classe) }}">

        <div class="flex flex-col md:flex-row md:items-end gap-4">


            <div class="w-full md:max-w-md">

                <label
                    for="annee_scolaire_id"
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Année scolaire

                </label>


                <select
                    name="annee_scolaire_id"
                    id="annee_scolaire_id"

                    onchange="this.form.submit()"

                    class="w-full
                           border border-slate-300
                           rounded-lg
                           px-4 py-3
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           focus:outline-none">


                    @foreach($anneesScolaires as $annee)

                        <option
                            value="{{ $annee->id }}"

                            {{ $anneeScolaire->id == $annee->id
                                ? 'selected'
                                : '' }}>

                            {{ $annee->libelle }}

                            @if($annee->actif)
                                (Active)
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>


            <noscript>

                <button
                    type="submit"
                    class="px-5 py-3
                           rounded-lg
                           bg-blue-600
                           text-white
                           hover:bg-blue-700">

                    Afficher

                </button>

            </noscript>

        </div>

    </form>

</div>



{{-- ========================================================= --}}
{{-- LISTE DES ÉLÈVES --}}
{{-- ========================================================= --}}

<div
    class="bg-white
           rounded-xl
           shadow-sm
           border border-slate-200
           overflow-hidden">


    {{-- En-tête --}}

    <div
        class="px-6 py-5
               border-b border-slate-200
               flex flex-col sm:flex-row
               sm:items-center
               sm:justify-between
               gap-3">


        <div>

            <h2 class="text-lg font-semibold text-slate-800">

                Élèves inscrits

            </h2>

            <p class="text-sm text-slate-500 mt-1">

                {{ $inscriptions->total() }}
                élève(s) inscrit(s)

                pour

                <span class="font-medium text-slate-700">

                    {{ $anneeScolaire->libelle }}

                </span>

            </p>

        </div>


        {{-- Nombre --}}

        <div
            class="px-4 py-2
                   rounded-lg
                   bg-blue-50
                   text-blue-700
                   text-sm
                   font-semibold">

            {{ $inscriptions->total() }} élèves

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- TABLEAU --}}
    {{-- ===================================================== --}}

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50">

                <tr>

                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        #

                    </th>


                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        Matricule

                    </th>


                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        Élève

                    </th>


                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        Sexe

                    </th>


                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        Date d'inscription

                    </th>


                    <th
                        class="px-6 py-4
                               text-left
                               font-semibold
                               text-slate-600">

                        Statut

                    </th>


                    <th
                        class="px-6 py-4
                               text-right
                               font-semibold
                               text-slate-600">

                        Action

                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">


                @forelse($inscriptions as $index => $inscription)

                    <tr class="hover:bg-slate-50 transition">


                        {{-- Numéro --}}

                        <td class="px-6 py-4 text-slate-500">

                            {{ $inscriptions->firstItem() + $index }}

                        </td>


                        {{-- Matricule --}}

                        <td class="px-6 py-4">

                            <span
                                class="font-medium
                                       text-blue-600">

                                {{ $inscription->eleve->matricule ?? '—' }}

                            </span>

                        </td>


                        {{-- Élève --}}

                        <td class="px-6 py-4">

                            <div>

                                <p
                                    class="font-semibold
                                           text-slate-800">

                                    {{ $inscription->eleve->nom ?? '' }}
                                    {{ $inscription->eleve->postnom ?? '' }}
                                    {{ $inscription->eleve->prenom ?? '' }}

                                </p>

                            </div>

                        </td>


                        {{-- Sexe --}}

                        <td class="px-6 py-4">

                            {{ $inscription->eleve->sexe_libelle
                                ?? $inscription->eleve->sexe
                                ?? '—' }}

                        </td>


                        {{-- Date --}}

                        <td class="px-6 py-4 whitespace-nowrap">

                            {{ $inscription->date_inscription
                                ? $inscription->date_inscription->format('d/m/Y')
                                : '—' }}

                        </td>


                        {{-- Statut --}}

                        <td class="px-6 py-4">

                            @if($inscription->actif)

                                <span
                                    class="inline-flex items-center
                                           px-3 py-1
                                           rounded-full
                                           text-xs
                                           font-medium
                                           bg-green-100
                                           text-green-700">

                                    <span
                                        class="w-1.5 h-1.5
                                               rounded-full
                                               bg-green-500
                                               mr-2">
                                    </span>

                                    Active

                                </span>

                            @else

                                <span
                                    class="inline-flex items-center
                                           px-3 py-1
                                           rounded-full
                                           text-xs
                                           font-medium
                                           bg-red-100
                                           text-red-700">

                                    <span
                                        class="w-1.5 h-1.5
                                               rounded-full
                                               bg-red-500
                                               mr-2">
                                    </span>

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- Action --}}

                        <td class="px-6 py-4">

                            <div class="flex justify-end">


                                <a
                                    href="{{ route(
                                        'eleves.show',
                                        $inscription->eleve
                                    ) }}"

                                    title="Consulter l'élève"

                                    class="w-9 h-9
                                           flex items-center
                                           justify-center
                                           rounded-lg
                                           bg-blue-50
                                           text-blue-600
                                           hover:bg-blue-100
                                           transition">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="px-6 py-12 text-center">


                            <div
                                class="flex flex-col
                                       items-center">


                                <div
                                    class="w-14 h-14
                                           rounded-full
                                           bg-slate-100
                                           flex items-center
                                           justify-center
                                           text-slate-400
                                           mb-4">

                                    <i class="fas fa-users text-xl"></i>

                                </div>


                                <p
                                    class="font-medium
                                           text-slate-700">

                                    Aucun élève inscrit

                                </p>


                                <p
                                    class="text-sm
                                           text-slate-500
                                           mt-1">

                                    Aucun élève n'est inscrit
                                    dans cette classe pour
                                    cette année scolaire.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>



    {{-- ===================================================== --}}
    {{-- PAGINATION --}}
    {{-- ===================================================== --}}

    @if($inscriptions->hasPages())

        <div
            class="px-6 py-4
                   border-t border-slate-200">

            {{ $inscriptions
                ->withQueryString()
                ->links() }}

        </div>

    @endif

</div>



{{-- ========================================================= --}}
{{-- RETOUR --}}
{{-- ========================================================= --}}

<div class="mt-6">

    <a
        href="{{ route('classes.index') }}"

        class="inline-flex items-center
               px-5 py-2.5
               rounded-lg
               bg-slate-200
               text-slate-700
               hover:bg-slate-300
               transition">

        <i class="fas fa-arrow-left mr-2"></i>

        Retour à la liste des classes

    </a>

</div>

</div>

@endsection
