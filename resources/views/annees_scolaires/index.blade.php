@extends('layouts.app')

@section('title', 'Années scolaires')


@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ===================================================== -->
    <!-- EN-TÊTE -->
    <!-- ===================================================== -->

    <div class="bg-white rounded-xl shadow-sm border mb-6">

        <div class="px-6 py-5 flex flex-col md:flex-row
                    md:items-center md:justify-between gap-4">

            <div>

                <h2 class="text-xl font-bold text-slate-700">
                    Liste des années scolaires
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Gestion des années scolaires de l'établissement.
                </p>

            </div>

            <a
                href="{{ route('annees.create') }}"
                class="inline-flex items-center justify-center
                       px-5 py-2.5 rounded-lg
                       bg-blue-600 text-white
                       hover:bg-blue-700 transition">

                <i class="fas fa-plus mr-2"></i>

                Ajouter une année

            </a>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- RECHERCHE -->
    <!-- ===================================================== -->

    <div class="bg-white rounded-xl border- shadow-sm border">

        <div class="p-6 ">

            <div class="relative max-w-md">

                <span class="absolute inset-y-0 left-0
                             flex items-center pl-4">

                    <i class="fas fa-search text-gray-400"></i>

                </span>

                <input
                    type="text"
                    id="searchAnnee"
                    placeholder="Rechercher une année scolaire..."
                    autocomplete="off"

                    class="w-full border rounded-lg
                           pl-11 pr-4 py-3
                           focus:ring-2 focus:ring-blue-500
                           focus:outline-none">

            </div>

        </div>


        <!-- ================================================= -->
        <!-- TABLEAU -->
        <!-- ================================================= -->

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 ">

                    <tr>

                        <th class="px-6 py-4 text-left
                                   text-sm font-semibold text-slate-600">
                            #
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-sm font-semibold text-slate-600">
                            Année scolaire
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-sm font-semibold text-slate-600">
                            Date de début
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-sm font-semibold text-slate-600">
                            Date de fin
                        </th>

                        <th class="px-6 py-4 text-left
                                   text-sm font-semibold text-slate-600">
                            Statut
                        </th>

                        <th class="px-6 py-4 text-center
                                   text-sm font-semibold text-slate-600">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody id="anneesTableBody">

                    @forelse($annees as $annee)

                        <tr
                            class=" hover:bg-slate-50 transition
                                   annee-row">

                            <!-- Numéro -->

                            <td class="px-6 py-4 text-sm text-gray-500">

                                {{ $loop->iteration }}

                            </td>


                            <!-- Libellé -->

                            <td class="px-6 py-4">

                                <span class="font-semibold text-slate-700">

                                    {{ $annee->libelle }}

                                </span>

                            </td>


                            <!-- Date début -->

                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $annee->date_debut->format('d/m/Y') }}

                            </td>


                            <!-- Date fin -->

                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $annee->date_fin->format('d/m/Y') }}

                            </td>


                            <!-- Statut -->

                            <td class="px-6 py-4">

                                @if($annee->actif)

                                    <button
                                        type="button"
                                        onclick="toggleStatus({{ $annee->id }}, true)"

                                        class="status-badge
                                               bg-green-100
                                               text-green-700
                                               hover:bg-green-200
                                               px-4 py-2
                                               rounded-full
                                               text-sm font-medium
                                               transition">

                                        <i class="fas fa-check-circle mr-1"></i>

                                        Actif

                                    </button>

                                @else

                                    <button
                                        type="button"
                                        onclick="toggleStatus({{ $annee->id }}, false)"

                                        class="status-badge
                                               bg-red-100
                                               text-red-700
                                               hover:bg-red-200
                                               px-4 py-2
                                               rounded-full
                                               text-sm font-medium
                                               transition">

                                        <i class="fas fa-times-circle mr-1"></i>

                                        Inactif

                                    </button>

                                @endif

                            </td>


                            <!-- Actions -->

                            <td class="px-6 py-4">

                                <div class="flex items-center
                                            justify-center gap-2">

                                    <!-- Consulter -->

                                    <a
                                        href="{{ route('annees.show', $annee) }}"

                                        title="Consulter"

                                        class="w-9 h-9 flex items-center
                                               justify-center rounded-lg
                                               bg-blue-100 text-blue-600
                                               hover:bg-blue-200 transition">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    <!-- Modifier -->

                                    <a
                                        href="{{ route('annees.edit', $annee) }}"

                                        title="Modifier"

                                        class="w-9 h-9 flex items-center
                                               justify-center rounded-lg
                                               bg-orange-100 text-orange-600
                                               hover:bg-orange-200 transition">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center">

                                <div class="text-gray-400">

                                    <i class="fas fa-calendar-alt
                                              text-4xl mb-3"></i>

                                    <p class="text-lg font-medium">

                                        Aucune année scolaire enregistrée.

                                    </p>

                                    <p class="text-sm mt-1">

                                        Commencez par ajouter une année scolaire.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    <!-- Aucun résultat de recherche -->

                    <tr id="noSearchResult" class="hidden">

                        <td
                            colspan="6"
                            class="px-6 py-10 text-center">

                            <div class="text-gray-400">

                                <i class="fas fa-search
                                          text-3xl mb-3"></i>

                                <p class="font-medium">

                                    Aucune année scolaire trouvée.

                                </p>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- JAVASCRIPT RECHERCHE -->
<!-- ========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchAnnee');

    const rows = document.querySelectorAll('.annee-row');

    const noResult = document.getElementById('noSearchResult');


    searchInput.addEventListener('input', function () {

        const search = this.value.toLowerCase().trim();

        let visibleRows = 0;


        rows.forEach(function (row) {

            const text = row.textContent.toLowerCase();

            if (text.includes(search)) {

                row.style.display = '';

                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Afficher le message si aucun résultat
        |--------------------------------------------------------------------------
        */

        if (visibleRows === 0 && search !== '') {

            noResult.classList.remove('hidden');

        } else {

            noResult.classList.add('hidden');

        }

    });

});



/*
|--------------------------------------------------------------------------
| Activation d'une année scolaire
|--------------------------------------------------------------------------
*/

function toggleStatus(id, actif)
{

    if (actif) {

        Swal.fire({

            title: 'Année scolaire active',

            text: 'Cette année est actuellement active. Elle ne peut pas être désactivée directement.',

            icon: 'info',

            confirmButtonText: 'OK'

        });

        return;
    }


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


        fetch(
            `/annees-scolaires/${id}/toggle-status`,
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

            Swal.fire(
                'Erreur',
                'Impossible de modifier le statut.',
                'error'
            );

            console.error(error);

        });

    });

}

</script>

@endsection
