@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('breadcrumb')
    Accueil / Utilisateurs
@endsection

@section('content')

<div class="max-w-7xl mx-auto py-4 sm:py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-700">Gestion des utilisateurs</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gérez les comptes et les accès à l'application.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>
            Ajouter un utilisateur
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <div class="relative max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fas fa-search"></i>
                </span>

                <input type="search" id="search"
                    placeholder="Rechercher un utilisateur..."
                    autocomplete="off"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">Nom complet</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">Nom d'utilisateur</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">Téléphone</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">Type</th>
                        <th class="px-6 py-4 text-left font-semibold text-slate-600 whitespace-nowrap">Statut</th>
                        <th class="px-6 py-4 text-right font-semibold text-slate-600 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody id="usersTable" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-3"></i>
                            <p>Chargement des utilisateurs...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('search');
    const table = document.getElementById('usersTable');
    const indexUrl = @json(route('users.index'));
    const usersBaseUrl = @json(url('/administration/utilisateurs'));
    let timer = null;
    let request = null;

    function showMessage(icon, message) {
        table.replaceChildren();
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 6;
        cell.className = 'px-6 py-12 text-center text-slate-500';

        const symbol = document.createElement('i');
        symbol.className = icon + ' text-3xl mb-3';
        const text = document.createElement('p');
        text.textContent = message;

        cell.append(symbol, text);
        row.appendChild(cell);
        table.appendChild(row);
    }

    function makeCell(className, value) {
        const cell = document.createElement('td');
        cell.className = className;
        cell.textContent = value || '—';
        return cell;
    }

    function makeBadge(label, className) {
        const badge = document.createElement('span');
        badge.className = 'inline-flex px-3 py-1 rounded-full text-sm ' + className;
        badge.textContent = label;
        return badge;
    }

    function makeAction(href, title, icon, className) {
        const link = document.createElement('a');
        link.href = href;
        link.title = title;
        link.setAttribute('aria-label', title);
        link.className = 'w-9 h-9 flex items-center justify-center rounded-lg transition ' + className;

        const symbol = document.createElement('i');
        symbol.className = 'fas ' + icon;
        link.appendChild(symbol);
        return link;
    }

    function renderUsers(users) {
        table.replaceChildren();

        if (!users.length) {
            showMessage('fas fa-users', 'Aucun utilisateur trouvé.');
            return;
        }

        users.forEach(function (user) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-slate-50 transition';

            const name = makeCell('px-6 py-4 font-semibold text-slate-700 whitespace-nowrap', user.nom_complet);
            const username = makeCell('px-6 py-4 text-slate-600 whitespace-nowrap', user.username);
            const phone = makeCell('px-6 py-4 text-slate-600 whitespace-nowrap', user.telephone);

            const type = document.createElement('td');
            type.className = 'px-6 py-4';
            type.appendChild(makeBadge(
                user.type,
                user.type === 'Admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'
            ));

            const status = document.createElement('td');
            status.className = 'px-6 py-4';
            status.appendChild(makeBadge(
                user.actif ? 'Actif' : 'Inactif',
                user.actif ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-700'
            ));

            const actions = document.createElement('td');
            actions.className = 'px-6 py-4';
            const actionList = document.createElement('div');
            actionList.className = 'flex justify-end gap-2';
            const userUrl = usersBaseUrl + '/' + encodeURIComponent(user.id);

            actionList.append(
                makeAction(userUrl + '/show', 'Consulter', 'fa-eye', 'bg-blue-100 text-blue-600 hover:bg-blue-200'),
                makeAction(userUrl + '/edit', 'Modifier', 'fa-edit', 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200')
            );
            actions.appendChild(actionList);

            row.append(name, username, phone, type, status, actions);
            table.appendChild(row);
        });
    }

    function loadUsers() {
        if (request) request.abort();
        request = new AbortController();

        const url = new URL(indexUrl, window.location.origin);
        url.searchParams.set('search', search.value.trim());

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: request.signal
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Erreur de chargement');
            return response.json();
        })
        .then(renderUsers)
        .catch(function (error) {
            if (error.name !== 'AbortError') {
                showMessage('fas fa-triangle-exclamation', 'Impossible de charger les utilisateurs.');
            }
        });
    }

    search.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(loadUsers, 300);
    });

    loadUsers();
});
</script>

@endsection
