<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion administrateur | Synergie School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-950 text-slate-800 antialiased">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden p-4">
        <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-blue-600/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-80 w-80 rounded-full bg-violet-600/20 blur-3xl"></div>

        <div class="relative z-10 grid w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl lg:grid-cols-2">
            <section class="hidden bg-gradient-to-br from-blue-700 to-slate-950 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-xl font-bold ring-1 ring-white/20">SA</div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[.2em] text-blue-200">Espace sécurisé</p>
                    <h1 class="mt-4 text-4xl font-bold leading-tight">Administration<br>School MA</h1>
                    <p class="mt-4 text-blue-100">Gérez les utilisateurs et les accès depuis une interface entièrement séparée.</p>
                </div>
                <p class="text-xs text-blue-200">Accès réservé aux administrateurs autorisés</p>
            </section>

            <section class="p-7 sm:p-10 lg:p-12">
                <div class="mb-8 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 font-bold text-white">SA</div>
                </div>
                <p class="text-sm font-semibold text-blue-600">Portail administrateur</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Connexion</h2>
                <p class="mt-2 text-sm text-slate-500">Utilisez un compte possédant le rôle Administrateur.</p>

                @if(session('error'))
                    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Adresse e-mail</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="admin@ecole.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Mot de passe</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Votre mot de passe" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>
                    <button class="w-full rounded-xl bg-blue-600 py-3.5 font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">Se connecter à l’administration</button>
                </form>
                </section>
        </div>
    </main>
</body>
</html>
