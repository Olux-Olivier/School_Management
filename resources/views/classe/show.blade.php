@extends('layouts.app')

@section('title', 'Consultation de la classe')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-700">
                Consultation de la classe
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informations détaillées de la classe.
            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('classes.index') }}"
                class="px-4 py-2 rounded-lg
                       bg-gray-200 text-gray-700
                       hover:bg-gray-300 transition">

                <i class="fas fa-arrow-left mr-2"></i>

                Retour

            </a>

            <a
                href="{{ route('classes.edit', $classe) }}"
                class="px-4 py-2 rounded-lg
                       bg-blue-600 text-white
                       hover:bg-blue-700 transition">

                <i class="fas fa-edit mr-2"></i>

                Modifier

            </a>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- CARTE PRINCIPALE --}}
    {{-- ===================================================== --}}

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">


        {{-- En-tête de la carte --}}
        <div class="px-6 py-6 border-b bg-slate-50">

            <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-4">

                <div>

                    <p class="text-sm text-slate-500 mb-1">
                        Classe
                    </p>

                    <h2 class="text-3xl font-bold text-slate-800">

                        {{ $classe->nom_complet }}

                    </h2>

                </div>


                {{-- ================================================= --}}
                {{-- STATUT CLIQUABLE --}}
                {{-- ================================================= --}}

                <div>

                    <p class="text-sm text-slate-500 mb-2">
                        Statut
                    </p>

                    <button
                        type="button"
                        id="statusBadge"
                        onclick="toggleStatus()"

                        class="px-4 py-2 rounded-full
                               transition font-medium

                               {{ $classe->actif
                                   ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                   : 'bg-red-100 text-red-700 hover:bg-red-200' }}">

                        {{ $classe->statut_libelle }}

                    </button>

                    <p class="text-xs text-slate-400 mt-2">
                        Cliquez pour modifier le statut.
                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATIONS --}}
        {{-- ===================================================== --}}

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Nom --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Classe
                    </p>

                    <p class="text-lg font-semibold text-slate-700 mt-1">

                        {{ $classe->nom }}

                    </p>

                </div>


                {{-- Nom complet --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Libellé
                    </p>

                    <p
                        id="nomComplet"
                        class="text-lg font-semibold text-slate-700 mt-1">

                        {{ $classe->nom_complet }}

                    </p>

                </div>


                {{-- Section --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Section
                    </p>

                    <p class="text-lg font-semibold text-slate-700 mt-1">

                        {{ $classe->section }}

                    </p>

                </div>


                {{-- Niveau --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Niveau
                    </p>

                    <p class="text-lg font-semibold text-slate-700 mt-1">

                        {{ $classe->niveau_libelle }}

                    </p>

                </div>


                {{-- Option --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Option
                    </p>

                    @if($classe->niveau == 3)

                        <p class="text-lg font-semibold text-slate-700 mt-1">

                            {{ $classe->option }}

                        </p>

                    @else

                        <p class="text-lg text-slate-400 mt-1">
                            Non applicable
                        </p>

                    @endif

                </div>


                {{-- Statut --}}
                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        État
                    </p>

                    <p
                        id="statusText"
                        class="text-lg font-semibold mt-1
                        {{ $classe->actif
                            ? 'text-green-600'
                            : 'text-red-600' }}">

                        {{ $classe->statut_libelle }}

                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TRAÇABILITÉ --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                <h3 class="text-lg font-semibold text-slate-700 mb-4">

                    Traçabilité

                </h3>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Création --}}
                    <div class="border rounded-lg p-4">

                        <p class="text-sm text-slate-500 mb-2">
                            Créée par
                        </p>

                        @if($classe->createdBy)

                            <p class="font-semibold text-slate-700">

                                {{ $classe->createdBy->nom_complet }}

                            </p>

                        @else

                            <p class="text-slate-400">
                                Non renseigné
                            </p>

                        @endif


                        <p class="text-xs text-slate-400 mt-1">

                            {{ $classe->created_at
                                ? $classe->created_at->format('d/m/Y H:i')
                                : '' }}

                        </p>

                    </div>


                    {{-- Modification --}}
                    <div class="border rounded-lg p-4">

                        <p class="text-sm text-slate-500 mb-2">
                            Dernière modification par
                        </p>

                        @if($classe->updatedBy)

                            <p class="font-semibold text-slate-700">

                                {{ $classe->updatedBy->nom_complet }}

                            </p>

                        @else

                            <p class="text-slate-400">
                                Aucune modification
                            </p>

                        @endif


                        <p class="text-xs text-slate-400 mt-1">

                            {{ $classe->updated_at
                                ? $classe->updated_at->format('d/m/Y H:i')
                                : '' }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SWEETALERT --}}
{{-- ========================================================= --}}

<script>

function toggleStatus()
{

    Swal.fire({

        title: 'Confirmation',

        text: 'Voulez-vous changer le statut de cette classe ?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Oui, changer',

        cancelButtonText: 'Annuler'

    }).then((result) => {

        if (!result.isConfirmed) {

            return;

        }


        fetch(
            "{{ route('classes.toggle-status', $classe) }}",
            {
                method: 'PATCH',

                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,

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
                    'Une erreur est survenue.'
                );

            }

            return response.json();

        })

        .then(data => {

            if (!data.success) {

                Swal.fire(
                    'Erreur',
                    data.message,
                    'error'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Badge
            |--------------------------------------------------------------------------
            */

            const badge =
                document.getElementById('statusBadge');


            badge.textContent =
                data.statut;


            /*
            |--------------------------------------------------------------------------
            | Texte du statut
            |--------------------------------------------------------------------------
            */

            const statusText =
                document.getElementById('statusText');


            statusText.textContent =
                data.statut;


            /*
            |--------------------------------------------------------------------------
            | Classe active
            |--------------------------------------------------------------------------
            */

            if (data.actif) {

                badge.classList.remove(
                    'bg-red-100',
                    'text-red-700',
                    'hover:bg-red-200'
                );

                badge.classList.add(
                    'bg-green-100',
                    'text-green-700',
                    'hover:bg-green-200'
                );


                statusText.classList.remove(
                    'text-red-600'
                );

                statusText.classList.add(
                    'text-green-600'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Classe inactive
            |--------------------------------------------------------------------------
            */

            else {

                badge.classList.remove(
                    'bg-green-100',
                    'text-green-700',
                    'hover:bg-green-200'
                );

                badge.classList.add(
                    'bg-red-100',
                    'text-red-700',
                    'hover:bg-red-200'
                );


                statusText.classList.remove(
                    'text-green-600'
                );

                statusText.classList.add(
                    'text-red-600'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            Swal.fire({

                title: 'Succès',

                text: data.message,

                icon: 'success',

                timer: 2000,

                showConfirmButton: false

            });

        })

        .catch(error => {

            console.error(error);

            Swal.fire(

                'Erreur',

                'Impossible de modifier le statut.',

                'error'

            );

        });

    });

}

</script>

@endsection
