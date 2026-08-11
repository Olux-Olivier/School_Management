@extends('layouts.app')

@section('title', 'Consultation de l’élève')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    {{-- ===================================================== --}}
    {{-- EN-TÊTE --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col md:flex-row
                md:items-center md:justify-between gap-4 mb-6">

        <div>

            <h1 class="text-2xl font-bold text-slate-700">
                Consultation de l’élève
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Informations détaillées de l’élève.
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('eleves.index') }}"
                class="px-4 py-2 rounded-lg
                       bg-gray-200 text-gray-700
                       hover:bg-gray-300 transition">

                <i class="fas fa-arrow-left mr-2"></i>

                Retour

            </a>


            <a
                href="{{ route('eleves.edit', $eleve) }}"
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


        {{-- ================================================= --}}
        {{-- EN-TÊTE --}}
        {{-- ================================================= --}}

        <div class="px-6 py-6 border-b bg-slate-50">

            <div class="flex flex-col md:flex-row
                        md:items-center gap-6">


                {{-- PHOTO --}}

                <div class="flex-shrink-0">

                    @if($eleve->photo)

                        <img
                            src="{{ asset(
                                'storage/' . $eleve->photo
                            ) }}"

                            alt="Photo de {{ $eleve->nom_complet }}"

                            class="w-28 h-28 rounded-full
                                   object-cover border-4
                                   border-white shadow">

                    @else

                        <div
                            class="w-28 h-28 rounded-full
                                   bg-slate-200
                                   flex items-center
                                   justify-center
                                   border-4 border-white
                                   shadow">

                            <i
                                class="fas fa-user
                                       text-4xl
                                       text-slate-400">
                            </i>

                        </div>

                    @endif

                </div>


                {{-- IDENTITÉ --}}

                <div class="flex-1">

                    <p class="text-sm text-slate-500">
                        Matricule
                    </p>

                    <p
                        class="text-lg font-semibold
                               text-blue-600 mt-1">

                        {{ $eleve->matricule }}

                    </p>


                    <h2
                        class="text-3xl font-bold
                               text-slate-800 mt-1">

                        {{ $eleve->nom_complet }}

                    </h2>

                </div>


                {{-- ================================================= --}}
                {{-- STATUT --}}
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

                               {{ $eleve->actif

                                    ? 'bg-green-100 text-green-700 hover:bg-green-200'

                                    : 'bg-red-100 text-red-700 hover:bg-red-200' }}">

                        {{ $eleve->statut_libelle }}

                    </button>


                    <p class="text-xs text-slate-400 mt-2">
                        Cliquez pour modifier le statut.
                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATIONS PERSONNELLES --}}
        {{-- ===================================================== --}}

        <div class="p-6">

            <h3
                class="text-lg font-semibold
                       text-slate-700 mb-4">

                Informations personnelles

            </h3>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- Nom --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Nom
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->nom }}

                    </p>

                </div>


                {{-- Postnom --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Postnom
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->postnom ?: '-' }}

                    </p>

                </div>


                {{-- Prénom --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Prénom
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->prenom ?: '-' }}

                    </p>

                </div>


                {{-- Sexe --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Sexe
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->sexe_libelle }}

                    </p>

                </div>


                {{-- Date de naissance --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Date de naissance
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->date_naissance
                            ? $eleve->date_naissance->format('d/m/Y')
                            : '-' }}

                    </p>

                </div>


                {{-- Lieu de naissance --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Lieu de naissance
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->lieu_naissance ?: '-' }}

                    </p>

                </div>


                {{-- Téléphone --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Téléphone
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->telephone ?: '-' }}

                    </p>

                </div>


                {{-- Adresse --}}

                <div class="border rounded-lg p-4">

                    <p class="text-sm text-slate-500">
                        Adresse
                    </p>

                    <p class="text-lg font-semibold
                              text-slate-700 mt-1">

                        {{ $eleve->adresse ?: '-' }}

                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TRAÇABILITÉ --}}
            {{-- ================================================= --}}

            <div class="mt-8">

                <h3
                    class="text-lg font-semibold
                           text-slate-700 mb-4">

                    Traçabilité

                </h3>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Création --}}

                    <div class="border rounded-lg p-4">

                        <p class="text-sm text-slate-500 mb-2">
                            Créé par
                        </p>


                        @if($eleve->createdBy)

                            <p class="font-semibold
                                      text-slate-700">

                                {{ $eleve->createdBy->nom_complet }}

                            </p>

                        @else

                            <p class="text-slate-400">
                                Non renseigné
                            </p>

                        @endif


                        <p class="text-xs text-slate-400 mt-1">

                            {{ $eleve->created_at
                                ? $eleve->created_at->format('d/m/Y H:i')
                                : '-' }}

                        </p>

                    </div>


                    {{-- Modification --}}

                    <div class="border rounded-lg p-4">

                        <p class="text-sm text-slate-500 mb-2">
                            Dernière modification par
                        </p>


                        @if($eleve->updatedBy)

                            <p class="font-semibold
                                      text-slate-700">

                                {{ $eleve->updatedBy->nom_complet }}

                            </p>

                        @else

                            <p class="text-slate-400">
                                Aucune modification
                            </p>

                        @endif


                        <p class="text-xs text-slate-400 mt-1">

                            {{ $eleve->updated_at
                                ? $eleve->updated_at->format('d/m/Y H:i')
                                : '-' }}

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

        text: 'Voulez-vous changer le statut de cet élève ?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Oui, changer',

        cancelButtonText: 'Annuler'

    }).then((result) => {

        if (!result.isConfirmed) {

            return;

        }


        fetch(
            "{{ route('eleves.toggle-status', $eleve) }}",
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
                    'Erreur serveur'
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
            | Couleurs du badge
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

            } else {

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
                'Impossible de modifier le statut de l’élève.',
                'error'
            );

        });

    });

}

</script>

@endsection
