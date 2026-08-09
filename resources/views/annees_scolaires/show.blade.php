@extends('layouts.app')

@section('title', 'Consultation année scolaire')

@section('breadcrumb')
    Accueil / Années scolaires / Consultation
@endsection

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- En-tête -->

    <div class="flex flex-col md:flex-row md:items-center
                md:justify-between gap-4 mb-6">

        <div>

            <h2 class="text-2xl font-bold text-slate-700">
                Consultation de l'année scolaire
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Informations détaillées sur l'année scolaire.
            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('annees.index') }}"
                class="inline-flex items-center px-4 py-2
                       rounded-lg bg-gray-200 text-gray-700
                       hover:bg-gray-300 transition">

                <i class="fas fa-arrow-left mr-2"></i>

                Retour

            </a>

            <a
                href="{{ route('annees.edit', $annee) }}"
                class="inline-flex items-center px-4 py-2
                       rounded-lg bg-blue-600 text-white
                       hover:bg-blue-700 transition">

                <i class="fas fa-edit mr-2"></i>

                Modifier

            </a>

        </div>

    </div>


    <!-- Carte principale -->

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <!-- Bandeau -->

        <div class="px-6 py-6 bg-slate-50 border-b">

            <div class="flex flex-col md:flex-row
                        md:items-center md:justify-between gap-4">

                <div>

                    <p class="text-sm text-slate-500 mb-1">
                        Année scolaire
                    </p>

                    <h1 class="text-3xl font-bold text-slate-800">
                        {{ $annee->libelle }}
                    </h1>

                </div>


                <!-- Statut -->

                <div>

                    @if($annee->actif)

                        <button
                            type="button"
                            id="statusBadge"
                            onclick="toggleStatus()"

                            class="px-5 py-2.5 rounded-full
                                   bg-green-100 text-green-700
                                   hover:bg-green-200
                                   font-semibold transition">

                            <i class="fas fa-check-circle mr-2"></i>

                            Actif

                        </button>

                    @else

                        <button
                            type="button"
                            id="statusBadge"
                            onclick="toggleStatus()"

                            class="px-5 py-2.5 rounded-full
                                   bg-red-100 text-red-700
                                   hover:bg-red-200
                                   font-semibold transition">

                            <i class="fas fa-times-circle mr-2"></i>

                            Inactif

                        </button>

                    @endif

                </div>

            </div>

        </div>


        <!-- Informations -->

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                <!-- Date début -->

                <div class="border rounded-xl p-5">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-10 h-10 rounded-lg
                                    bg-blue-100 text-blue-600
                                    flex items-center justify-center">

                            <i class="fas fa-calendar-plus"></i>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Date de début
                            </p>

                            <p class="font-semibold text-gray-800">

                                {{ $annee->date_debut->format('d/m/Y') }}

                            </p>

                        </div>

                    </div>

                </div>


                <!-- Date fin -->

                <div class="border rounded-xl p-5">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-10 h-10 rounded-lg
                                    bg-orange-100 text-orange-600
                                    flex items-center justify-center">

                            <i class="fas fa-calendar-minus"></i>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Date de fin
                            </p>

                            <p class="font-semibold text-gray-800">

                                {{ $annee->date_fin->format('d/m/Y') }}

                            </p>

                        </div>

                    </div>

                </div>


                <!-- Créée le -->

                <div class="border rounded-xl p-5">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-lg
                                    bg-purple-100 text-purple-600
                                    flex items-center justify-center">

                            <i class="fas fa-clock"></i>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Créée le
                            </p>

                            <p class="font-semibold text-gray-800">

                                {{ $annee->created_at
                                    ? $annee->created_at->format('d/m/Y H:i')
                                    : '-' }}

                            </p>

                        </div>

                    </div>

                </div>


                <!-- Dernière modification -->

                <div class="border rounded-xl p-5">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-lg
                                    bg-gray-100 text-gray-600
                                    flex items-center justify-center">

                            <i class="fas fa-history"></i>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Dernière modification
                            </p>

                            <p class="font-semibold text-gray-800">

                                {{ $annee->updated_at
                                    ? $annee->updated_at->format('d/m/Y H:i')
                                    : '-' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Utilisateurs -->

            <div class="mt-8 border-t pt-6">

                <h3 class="text-lg font-semibold text-slate-700 mb-4">

                    Traçabilité

                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Créateur -->

                    <div>

                        <p class="text-sm text-gray-500 mb-1">
                            Créé par
                        </p>

                        <p class="font-medium text-gray-800">

                            {{ $annee->createdBy?->nom_complet ?? 'Non disponible' }}

                        </p>

                    </div>


                    <!-- Modificateur -->

                    <div>

                        <p class="text-sm text-gray-500 mb-1">
                            Modifié par
                        </p>

                        <p class="font-medium text-gray-800">

                            {{ $annee->updatedBy?->nom_complet ?? 'Aucune modification' }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

function toggleStatus()
{

    const actif =
        {{ $annee->actif ? 'true' : 'false' }};


    /*
    |--------------------------------------------------------------------------
    | Une année active ne peut pas être désactivée
    |--------------------------------------------------------------------------
    */

    if (actif) {

        Swal.fire({

            title: 'Année scolaire active',

            text: 'L’année scolaire active ne peut pas être désactivée directement.',

            icon: 'info',

            confirmButtonText: 'OK'

        });

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        title: 'Activer cette année ?',

        text: 'L’année scolaire actuellement active sera automatiquement désactivée.',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Oui, activer',

        cancelButtonText: 'Annuler',

        confirmButtonColor: '#2563eb'

    }).then((result) => {

        if (!result.isConfirmed) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Requête AJAX
        |--------------------------------------------------------------------------
        */

        fetch(
            "{{ route('annees.toggle-status', $annee) }}",
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


            Swal.fire({

                icon: 'success',

                title: 'Succès',

                text: data.message,

                timer: 1500,

                showConfirmButton: false

            }).then(() => {

                location.reload();

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
