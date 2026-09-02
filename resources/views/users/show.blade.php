@extends('layouts.admin')

@section('title', 'Détails de l’utilisateur')

@section('content')

<div class="max-w-6xl mx-auto py-8">

    <!-- En-tête -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Détails de l'utilisateur
            </h1>

            <p class="text-gray-500 mt-1">
                Consultez les informations complètes de cet utilisateur.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('users.edit',$user) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                Modifier

            </a>

            <a href="{{ route('users.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Retour

            </a>

        </div>

    </div>

    <div class="bg-white rounded-xl shadow">

        <div class="p-8">

            <div class="grid md:grid-cols-3 gap-10">

                <!-- Photo -->

                <div class="text-center">

                    @if($user->photo)

                        <img
                            src="{{ asset('storage/'.$user->photo) }}"
                            class="w-48 h-48 rounded-full object-cover mx-auto border-4 border-blue-100 shadow">

                    @else

                        <div
                            class="w-48 h-48 rounded-full bg-gray-200 flex items-center justify-center text-7xl mx-auto">

                            👤

                        </div>

                    @endif

                    <h2 class="mt-5 text-2xl font-bold">

                        {{ $user->nom_complet }}

                    </h2>

                    <p class="text-gray-500">

                        {{ $user->username }}

                    </p>

                    <div class="mt-4">

                        <button

                            id="statusBadge"

                            onclick="toggleStatus()"

                            class="px-4 py-2 rounded-full transition

                                {{ $user->actif

                                    ? 'bg-green-100 text-green-700 hover:bg-green-200'

                                    : 'bg-red-100 text-red-700 hover:bg-red-200' }}">

                            {{ $user->statut_libelle }}

                        </button>

                    </div>

                </div>

                <!-- Informations -->

                <div class="md:col-span-2">

                    <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-5">

                        Informations générales

                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="text-gray-500 text-sm">

                                Nom complet

                            </label>

                            <p class="font-semibold text-lg">

                                {{ $user->nom_complet }}

                            </p>

                        </div>

                        <div>

                            <label class="text-gray-500 text-sm">

                                Sexe

                            </label>

                            <p class="font-semibold">

                                {{ $user->sexe_libelle }}

                            </p>

                        </div>

                        <div>

                            <label class="text-gray-500 text-sm">

                                Téléphone

                            </label>

                            <p class="font-semibold">

                                {{ $user->telephone ?: '-' }}

                            </p>

                        </div>

                        <div>

                            <label class="text-gray-500 text-sm">

                                Email

                            </label>

                            <p class="font-semibold">

                                {{ $user->email ?: '-' }}

                            </p>

                        </div>

                        <div>

                            <label class="text-gray-500 text-sm">

                                Nom d'utilisateur

                            </label>

                            <p class="font-semibold">

                                {{ $user->username }}

                            </p>

                        </div>

                        <div>

                            <label class="text-gray-500 text-sm">

                                Type

                            </label>

                            <p>

                                <span class="
                                    px-3 py-1 rounded-full
                                    {{ $user->type == 'Admin'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-blue-100 text-blue-700' }}
                                ">
                                    {{ $user->type }}
                                </span>

                            </p>

                        </div>

                    </div>

                    <!-- Traçabilité -->

                    <div class="mt-10">

                        <h3 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-5">

                            Traçabilité

                        </h3>

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>

                                <label class="text-gray-500 text-sm">

                                    Créé le

                                </label>

                                <p class="font-semibold">

                                    {{ $user->created_at->format('d/m/Y à H:i') }}

                                </p>

                            </div>

                            <div>

                                <label class="text-gray-500 text-sm">

                                    Créé par

                                </label>

                                <p class="font-semibold">

                                    {{ $user->createdBy?->nom_complet ?? '-' }}

                                </p>

                            </div>

                            <div>

                                <label class="text-gray-500 text-sm">

                                    Dernière modification

                                </label>

                                <p class="font-semibold">

                                    {{ $user->updated_at->format('d/m/Y à H:i') }}

                                </p>

                            </div>

                            <div>

                                <label class="text-gray-500 text-sm">

                                    Modifié par

                                </label>

                                <p class="font-semibold">

                                    {{ $user->updatedBy?->nom_complet ?? '-' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<script>

function toggleStatus()
{

    Swal.fire({

        title: 'Confirmation',

        text: 'Voulez-vous changer le statut de cet utilisateur ?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText: 'Oui',

        cancelButtonText: 'Annuler'

    }).then((result)=>{

        if(result.isConfirmed){

            fetch("{{ route('users.toggle-status',$user) }}",{

                method:'PATCH',

                headers:{

                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,

                    'X-Requested-With':'XMLHttpRequest'

                }

            })

            .then(response=>response.json())

            .then(data=>{

                if(!data.success){

                    Swal.fire(

                        'Erreur',

                        data.message,

                        'error'

                    );

                    return;

                }

                let badge=document.getElementById('statusBadge');

                badge.innerHTML=data.statut;

                if(data.actif){

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

                }else{

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

                Swal.fire(

                    'Succès',

                    data.message,

                    'success'

                );

            });

        }

    });

}

</script>

@endsection
