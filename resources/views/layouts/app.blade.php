<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Synergie School</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- ========================= -->
    <!-- SIDEBAR -->
    <!-- ========================= -->

    <aside class="w-72 bg-slate-900 text-white flex flex-col">

        <!-- Logo -->

        <div class="h-20 flex items-center justify-center border-b border-slate-700">

            <div class="text-center">

                <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center mx-auto text-2xl font-bold">

                    SM

                </div>
{{--
                <h1 class="mt-2 text-lg font-bold">

                    Synergie School

                </h1> --}}

            </div>

        </div>

        <!-- MENU -->

        <nav class="flex-1 mt-6">

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800 transition">

                <i class="fas fa-home w-6"></i>

                <span>Accueil</span>

            </a>

            <a href="{{ route('users.index') }}"

                class="flex items-center px-6 py-3 transition

                {{ request()->routeIs('users.*')

                    ? 'bg-blue-600'

                    : 'hover:bg-slate-800' }}">

                <i class="fas fa-users w-6"></i>

                <span>Utilisateurs</span>

            </a>

            <a href="{{ route('annees.index') }}"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-calendar-alt w-6"></i>

                <span>Années scolaires</span>

            </a>

            <a href="{{ route('classes.index') }}"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-school w-6"></i>

                <span>Classes</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-user-graduate w-6"></i>

                <span>Élèves</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-file-signature w-6"></i>

                <span>Inscriptions</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-retweet w-6"></i>

                <span>Réinscriptions</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-money-bill-wave w-6"></i>

                <span>Paiements</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-chart-bar w-6"></i>

                <span>Rapports</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-cog w-6"></i>

                <span>Paramètres</span>

            </a>

        </nav>

        <!-- Footer Sidebar -->

        <div class="border-t border-slate-700 p-4 text-center text-sm text-slate-400">

            Version 1.0

        </div>

    </aside>

    <!-- ========================= -->
    <!-- CONTENU -->
    <!-- ========================= -->

    <main class="flex-1 flex flex-col">

        <!-- ========================= -->
        <!-- HEADER -->
        <!-- ========================= -->

        <header class="bg-white shadow-sm h-20 flex items-center justify-between px-8">

            <div>

                <h2 class="text-2xl font-bold text-slate-700">

                    @yield('title','School Management')

                </h2>

                <p class="text-sm text-slate-500 mt-1">

                    @yield('breadcrumb')

                </p>

            </div>

            <div class="flex items-center gap-6">

                <!-- Notification -->

                <button
                    class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200">

                    <i class="fas fa-bell text-slate-600"></i>

                </button>

                <!-- Utilisateur -->

                <div class="relative">

                    <button

                        id="userMenuButton"

                        class="flex items-center gap-3">

                        @if(auth()->user()->photo)

                            <img

                                src="{{ asset('storage/'.auth()->user()->photo) }}"

                                class="w-12 h-12 rounded-full object-cover border">

                        @else

                            <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                                {{ strtoupper(substr(auth()->user()->nom,0,1)) }}

                            </div>

                        @endif

                        <div class="text-left">

                            <div class="font-semibold">

                                {{ auth()->user()->nom_complet }}

                            </div>

                            <div class="text-sm text-gray-500">

                                {{ auth()->user()->type }}

                            </div>

                        </div>

                        <i class="fas fa-chevron-down text-gray-500"></i>

                    </button>

                    <!-- Menu -->

                    <div

                        id="userDropdown"

                        class="hidden absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-xl border">

                        <a

                            href="#"

                            class="block px-5 py-3 hover:bg-gray-100">

                            <i class="fas fa-user mr-2"></i>

                            Mon profil

                        </a>

                        <hr>

                        <form

                            method="POST"

                            action="{{ route('logout') }}">

                            @csrf

                            <button

                                class="w-full text-left px-5 py-3 hover:bg-red-50 text-red-600">

                                <i class="fas fa-sign-out-alt mr-2"></i>

                                Déconnexion

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        <!-- ========================= -->
        <!-- CONTENU -->
        <!-- ========================= -->

        <section class="flex-1 p-8 overflow-auto">

            <div class="max-w-7xl mx-auto">

                @yield('content')

            </div>

        </section>

        <!-- ========================= -->
        <!-- FOOTER -->
        <!-- ========================= -->

        <footer class="bg-white border-t h-14 flex items-center justify-center text-gray-500 text-sm">

            © {{ date('Y') }} Synergie School | Version 1.0

        </footer>

    </main>

</div>

<script>

const button=document.getElementById('userMenuButton');

const menu=document.getElementById('userDropdown');

button.addEventListener('click',function(e){

    e.stopPropagation();

    menu.classList.toggle('hidden');

});

document.addEventListener('click',function(){

    menu.classList.add('hidden');

});

</script>

@if(session('success'))

<script>

Swal.fire({

    icon:'success',

    title:'Succès',

    text:"{{ session('success') }}",

    timer:2000,

    showConfirmButton:false

});

</script>

@endif

@if(session('error'))

<script>

Swal.fire({

    icon:'error',

    title:'Erreur',

    text:"{{ session('error') }}"

});

</script>

@endif
</body>

</html>
