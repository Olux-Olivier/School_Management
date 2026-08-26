@extends('layouts.admin')

@section('title', 'Administration')

@section('breadcrumb')
    Accueil / Administration
@endsection

@section('content')
<div class="py-4 sm:py-8 space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700">
                <i class="fas fa-shield-alt"></i> Espace sécurisé
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-bold text-slate-800">Centre d’administration</h1>
            <p class="mt-2 text-slate-500">Gérez les comptes, les rôles et les accès à l’application.</p>
        </div>

        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <i class="fas fa-user-plus mr-2"></i> Nouvel utilisateur
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Utilisateurs', 'value' => $totalUsers, 'icon' => 'fa-users', 'style' => 'bg-blue-100 text-blue-600'],
            ['label' => 'Comptes actifs', 'value' => $activeUsers, 'icon' => 'fa-user-check', 'style' => 'bg-green-100 text-green-600'],
            ['label' => 'Comptes inactifs', 'value' => $inactiveUsers, 'icon' => 'fa-user-slash', 'style' => 'bg-orange-100 text-orange-600'],
            ['label' => 'Administrateurs', 'value' => $adminUsers, 'icon' => 'fa-user-shield', 'style' => 'bg-violet-100 text-violet-600'],
        ] as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-800">{{ $stat['value'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $stat['style'] }}">
                        <i class="fas {{ $stat['icon'] }} text-xl"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <a href="{{ route('users.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                <i class="fas fa-users-cog text-2xl"></i>
            </div>
            <h2 class="mt-5 text-xl font-bold text-slate-800 group-hover:text-blue-600">Gestion des utilisateurs</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">Créer, consulter, modifier, activer ou désactiver les comptes.</p>
            <span class="mt-5 inline-flex items-center font-semibold text-blue-600">Gérer les utilisateurs <i class="fas fa-arrow-right ml-2 transition group-hover:translate-x-1"></i></span>
        </a>

        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="font-bold text-slate-800">Utilisateurs récents</h2>
                    <p class="text-sm text-slate-500">Les cinq derniers comptes créés</p>
                </div>
                <a href="{{ route('users.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Voir tout</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentUsers as $user)
                    <div class="flex items-center gap-3 px-5 py-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-600">{{ strtoupper(substr($user->nom, 0, 1)) }}</div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-slate-700">{{ $user->nom_complet }}</p>
                            <p class="truncate text-sm text-slate-500">{{ $user->username }} · {{ $user->type }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->actif ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ $user->statut_libelle }}</span>
                    </div>
                @empty
                    <p class="px-5 py-10 text-center text-slate-500">Aucun utilisateur enregistré.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
