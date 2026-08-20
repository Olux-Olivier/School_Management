@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

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


        {{-- ===================================================== --}}
        {{-- ANNÉE SCOLAIRE --}}
        {{-- ===================================================== --}}

        <div
            class="mx-6 mt-6
                   p-4
                   rounded-lg
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


                {{-- INTITULÉ --}}

                <div>

                    <label
                        for="intitule"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Intitulé du frais

                        <span class="text-red-500">*</span>

                    </label>

                    <input
                        type="text"
                        name="intitule"
                        id="intitule"

                        value="{{ old(
                            'intitule',
                            $frais->intitule
                        ) }}"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                    @error('intitule')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- CLASSE --}}

                <div>

                    <label
                        for="classe_id"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

                        Classe

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="classe_id"
                        id="classe_id"

                        class="w-full
                               border border-slate-300
                               rounded-lg
                               px-4 py-3
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               focus:outline-none">

                        <option value="">
                            -- Sélectionner une classe --
                        </option>

                        @foreach($classes as $classe)

                            <option
                                value="{{ $classe->id }}"

                                @selected(
                                    old(
                                        'classe_id',
                                        $frais->classe_id
                                    ) == $classe->id
                                )>

                                {{ $classe->nom }}

                                @if($classe->option)
                                    — {{ $classe->option }}
                                @endif

                                — {{ ucfirst($classe->section) }}

                            </option>

                        @endforeach

                    </select>

                    <p class="text-xs text-slate-500 mt-2">

                        Seules les classes actives sont proposées.

                    </p>

                    @error('classe_id')

                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- MONTANT --}}

                <div>

                    <label
                        for="montant"
                        class="block
                               text-sm
                               font-medium
                               text-slate-700
                               mb-2">

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
                                $frais->montant
                            ) }}"

                            min="0"
                            step="0.01"

                            class="w-full
                                   border border-slate-300
                                   rounded-lg
                                   px-4 py-3
                                   pr-14
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
        {{-- BOUTONS --}}
        {{-- ===================================================== --}}

        <div
            class="px-6 py-5
                   bg-slate-50
                   flex
                   flex-col-reverse
                   md:flex-row
                   md:justify-end
                   gap-3">

            <a
                href="{{ route('frais.index') }}"

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

                Enregistrer les modifications

            </button>

        </div>

    </form>

</div>

@endsection
