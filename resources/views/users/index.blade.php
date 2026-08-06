@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <div class="bg-white rounded-lg shadow">

        <div class="flex justify-between items-center p-5 border-b">

            <h2 class="text-2xl font-bold">

                Gestion des utilisateurs

            </h2>

            <a href="{{ route('users.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                + Ajouter

            </a>

        </div>

        <div class="p-5">

            <input
                type="text"
                id="search"
                placeholder="Rechercher un utilisateur..."
                class="w-full md:w-96 border rounded-lg px-4 py-2">

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-left">Nom complet</th>

                        <th class="px-4 py-3 text-left">Username</th>

                        <th class="px-4 py-3 text-left">Téléphone</th>

                        <th class="px-4 py-3 text-left">Type</th>

                        <th class="px-4 py-3 text-left">Statut</th>

                        <th class="px-4 py-3 text-center">Actions</th>

                    </tr>

                </thead>

                <tbody id="tbody">

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

const search = document.getElementById('search');

let timeout = null;

search.addEventListener('keyup', function(){

    clearTimeout(timeout);

    timeout = setTimeout(loadUsers,300);

});

window.onload = loadUsers;

function loadUsers(){

    fetch(`{{ route('users.index') }}?search=${search.value}`,{

        headers:{

            'X-Requested-With':'XMLHttpRequest'

        }

    })

    .then(response => response.json())

    .then(users => {

        let html = '';

        if(users.length==0){

            html = `

                <tr>

                    <td colspan="6"
                        class="text-center py-6">

                        Aucun utilisateur trouvé.

                    </td>

                </tr>

            `;

        }

        users.forEach(user=>{

            html += `

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-4 py-3">

                        ${user.nom_complet}

                    </td>

                    <td class="px-4 py-3">

                        ${user.username}

                    </td>

                    <td class="px-4 py-3">

                        ${user.telephone ?? ''}

                    </td>

                    <td class="px-4 py-3">

                        ${
                            user.type=='Admin'

                            ? '<span class="bg-red-100 text-red-700 px-2 py-1 rounded">Admin</span>'

                            : '<span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">Agent</span>'
                        }

                    </td>

                    <td class="px-4 py-3">

                        ${
                            user.actif

                            ? '<span class="bg-green-100 text-green-700 px-2 py-1 rounded">Actif</span>'

                            : '<span class="bg-gray-200 text-gray-700 px-2 py-1 rounded">Inactif</span>'
                        }

                    </td>
                    <td class="px-4 py-3 text-center">

                        <a
                            href="/utilisateurs/${user.id}/edit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

                            Modifier

                        </a>

                        <a href="/utilisateurs/${user.id}/show"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">

                            Voir

                        </a>

                    </td>

                </tr>

            `;

        });

        document.getElementById('tbody').innerHTML = html;

    });

}

</script>

@endsection
