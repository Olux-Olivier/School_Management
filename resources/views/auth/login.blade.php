<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Connexion | Synergie School</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        <div class="grid md:grid-cols-2">

            <!-- Partie gauche -->

            <div class="bg-blue-700 text-white p-12 flex flex-col justify-center">

                <div class="text-center">

                    <div class="w-28 h-28 rounded-full bg-white mx-auto flex items-center justify-center text-blue-700 text-5xl font-bold">

                        SS

                    </div>

                    <h1 class="text-4xl font-bold mt-8">

                        Synergie School

                    </h1>

                    <p class="mt-4 text-blue-100 leading-7">

                        Système de Gestion Scolaire

                    </p>

                    <p class="mt-8 text-sm text-blue-200">

                        Gestion des élèves, inscriptions,
                        réinscriptions, paiements et administration.

                    </p>

                </div>

            </div>

            <!-- Partie droite -->

            <div class="p-10">

                <h2 class="text-3xl font-bold text-gray-700 mb-2">

                    Connexion

                </h2>

                <p class="text-gray-500 mb-8">

                    Connectez-vous pour accéder à l'application.

                </p>

                @if(session('error'))

                    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">

                        {{ session('error') }}

                    </div>

                @endif

                <form method="POST" action="{{ route('login.post') }}">

                    @csrf

                    <!-- Username -->

                    <div class="mb-5">

                        <label class="block mb-2 font-medium text-gray-700">
                            Adresse e-mail
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            autofocus
                            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        @error('email')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror

                    </div>

                    <!-- Mot de passe -->

                    <div class="mb-6">

                        <label class="block mb-2 font-medium text-gray-700">

                            Mot de passe

                        </label>

                        <input
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        @error('password')

                            <small class="text-red-600">

                                {{ $message }}

                            </small>

                        @enderror

                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 transition text-white py-3 rounded-lg font-semibold">

                        Se connecter

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>
