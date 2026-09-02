@extends('layouts.app')

@section('title', isset($eleve) ? 'Modifier un élève' : 'Ajouter un élève')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>

        </div>

        <a
                href="{{ route('annees.index') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 sm:w-auto"
            >
                <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i>
                <span>Retour aux élèves</span>
            </a>

    </div>

    <div class="bg-white rounded-xl shadow-sm border mb-6">

        <div class="px-6 py-5">

            <h2 class="text-2xl font-bold text-slate-700">

                {{ isset($eleve)
                    ? 'Modifier l’élève'
                    : 'Ajouter un élève' }}

            </h2>

            <p class="text-sm text-slate-500 mt-1">

                {{ isset($eleve)
                    ? 'Modifiez les informations de l’élève.'
                    : 'Enregistrez un nouvel élève dans l’établissement.' }}

            </p>

        </div>

        <form
            action="{{ isset($eleve)
                ? route('eleves.update', $eleve)
                : route('eleves.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            @if(isset($eleve))
                @method('PUT')
            @endif


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- ================================================= --}}
                    {{-- MATRICULE --}}
                    {{-- ================================================= --}}

                    <div class="md:col-span-2">

                        <label class="block mb-2 font-medium text-slate-700">

                            Matricule

                        </label>

                        <input
                            type="text"
                            name="matricule"

                            value="{{ old(
                                'matricule',
                                $eleve->matricule ?? $matricule
                            ) }}"

                            readonly

                            class="w-full border rounded-lg px-4 py-2.5
                                   bg-slate-100 text-slate-600
                                   cursor-not-allowed">

                        <p class="text-xs text-slate-400 mt-1">

                            Le matricule est généré automatiquement.

                        </p>

                    </div>


                    {{-- ================================================= --}}
                    {{-- NOM --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Nom

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="text"
                            name="nom"

                            value="{{ old(
                                'nom',
                                $eleve->nom ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('nom')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- POSTNOM --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Postnom

                        </label>

                        <input
                            type="text"
                            name="postnom"

                            value="{{ old(
                                'postnom',
                                $eleve->postnom ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('postnom')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- PRÉNOM --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Prénom

                        </label>

                        <input
                            type="text"
                            name="prenom"

                            value="{{ old(
                                'prenom',
                                $eleve->prenom ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('prenom')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- SEXE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Sexe

                        </label>

                        <select
                            name="sexe"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                            <option value="">
                                Sélectionner
                            </option>

                            <option
                                value="M"
                                {{ old(
                                    'sexe',
                                    $eleve->sexe ?? ''
                                ) == 'M'
                                    ? 'selected'
                                    : '' }}>

                                Masculin

                            </option>

                            <option
                                value="F"
                                {{ old(
                                    'sexe',
                                    $eleve->sexe ?? ''
                                ) == 'F'
                                    ? 'selected'
                                    : '' }}>

                                Féminin

                            </option>

                        </select>

                        @error('sexe')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- DATE DE NAISSANCE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Date de naissance

                        </label>

                        <input
                            type="date"
                            name="date_naissance"

                            value="{{ old(
                                'date_naissance',
                                isset($eleve->date_naissance)
                                    ? $eleve->date_naissance->format('Y-m-d')
                                    : ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('date_naissance')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- LIEU DE NAISSANCE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Lieu de naissance

                        </label>

                        <input
                            type="text"
                            name="lieu_naissance"

                            value="{{ old(
                                'lieu_naissance',
                                $eleve->lieu_naissance ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('lieu_naissance')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- TÉLÉPHONE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Téléphone

                        </label>

                        <input
                            type="text"
                            name="telephone"

                            value="{{ old(
                                'telephone',
                                $eleve->telephone ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('telephone')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- ADRESSE --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Adresse

                        </label>

                        <input
                            type="text"
                            name="adresse"

                            value="{{ old(
                                'adresse',
                                $eleve->adresse ?? ''
                            ) }}"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('adresse')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- PHOTO --}}
                    {{-- ================================================= --}}

                    <div>

                        <label class="block mb-2 font-medium text-slate-700">

                            Photo

                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept="image/*"

                            class="w-full border rounded-lg px-4 py-2.5
                                   focus:ring-2 focus:ring-blue-500
                                   focus:outline-none">

                        @error('photo')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror


                        @if(isset($eleve) && $eleve->photo)

                            <div class="mt-3">

                                <img
                                    src="{{ asset(
                                        'storage/' . $eleve->photo
                                    ) }}"

                                    alt="Photo de l'élève"

                                    class="w-20 h-20 object-cover
                                           rounded-full border">

                            </div>

                        @endif

                    </div>


                    {{-- ================================================= --}}
                    {{-- STATUT --}}
                    {{-- ================================================= --}}

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
                                {{ old(
                                    'actif',
                                    $eleve->actif ?? 1
                                ) == 1
                                    ? 'selected'
                                    : '' }}>

                                Actif

                            </option>

                            <option
                                value="0"
                                {{ old(
                                    'actif',
                                    $eleve->actif ?? 1
                                ) == 0
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


            {{-- ===================================================== --}}
            {{-- BOUTONS --}}
            {{-- ===================================================== --}}

            <div class="border-t border-slate-200 px-6 py-4
                        flex justify-end gap-3">

                <a
                    href="{{ route('eleves.index') }}"

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

                    {{ isset($eleve)
                        ? 'Enregistrer les modifications'
                        : 'Enregistrer l’élève' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
