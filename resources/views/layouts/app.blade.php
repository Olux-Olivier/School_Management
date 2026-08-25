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

    <script>
        try {
            if (
                window.matchMedia('(min-width: 1024px)').matches &&
                localStorage.getItem('sidebar-collapsed') === 'true'
            ) {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        } catch (error) {
            // localStorage peut être indisponible selon les réglages du navigateur.
        }
    </script>
    <style>
        html,body{height:100%}body{overflow:hidden}
        #sidebar,#appMain{transition:width .3s ease,margin-left .3s ease,transform .3s ease}
        #sidebar{width:260px}#appMain{margin-left:260px}
        html.sidebar-is-collapsed #sidebar,#sidebar.sidebar-collapsed{width:70px}
        html.sidebar-is-collapsed #appMain,#sidebar.sidebar-collapsed+#sidebarOverlay+#appMain{margin-left:70px}
        html.sidebar-is-collapsed #sidebar nav span,html.sidebar-is-collapsed #sidebar .sidebar-footer,#sidebar.sidebar-collapsed nav span,#sidebar.sidebar-collapsed .sidebar-footer{display:none}
        html.sidebar-is-collapsed #sidebar .sidebar-brand,#sidebar.sidebar-collapsed .sidebar-brand{width:42px;height:42px;font-size:1rem}
        #sidebar nav a{position:relative}
        html.sidebar-is-collapsed #sidebar nav a,#sidebar.sidebar-collapsed nav a{justify-content:center;padding-left:0;padding-right:0}
        html.sidebar-is-collapsed #sidebar nav a i,#sidebar.sidebar-collapsed nav a i{width:auto}
        @media(min-width:1024px){html.sidebar-is-collapsed #sidebar nav a:hover:after,#sidebar.sidebar-collapsed nav a:hover:after{content:attr(data-label);position:absolute;left:62px;z-index:60;white-space:nowrap;border-radius:6px;background:#0f172a;padding:7px 10px;color:#fff;font-size:.75rem;box-shadow:0 8px 20px rgb(15 23 42/.2)}}
        @media(max-width:1023px){#sidebar{width:260px;transform:translateX(-100%)}#sidebar.mobile-open{transform:translateX(0)}#appMain,#sidebar.sidebar-collapsed+#sidebarOverlay+#appMain{margin-left:0}}
        .page-content{min-width:0}.page-content .overflow-x-auto{max-width:100%;-webkit-overflow-scrolling:touch}.page-content table{min-width:max-content}
    </style>
</head>

<body class="bg-slate-100 overflow-x-hidden">

<div class="h-screen overflow-hidden">

    <!-- ========================= -->
    <!-- SIDEBAR -->
    <!-- ========================= -->

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 h-screen bg-slate-900 text-white flex flex-col shadow-xl lg:shadow-none" aria-label="Navigation principale">

        <!-- Logo -->

        <div class="h-20 flex-shrink-0 flex items-center justify-center border-b border-slate-700 relative">

            <div class="text-center">

                <div class="sidebar-brand w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center mx-auto text-2xl font-bold transition-all duration-300">

                    SM

                </div>
{{--
                <h1 class="mt-2 text-lg font-bold">

                    Synergie School

                </h1> --}}

            </div>

        </div>

        <!-- MENU -->

        <button id="closeSidebar" type="button" class="lg:hidden absolute right-3 top-5 w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-800" aria-label="Fermer le menu"><i class="fas fa-times"></i></button>

        <nav class="flex-1 min-h-0 mt-6 overflow-y-auto overflow-x-hidden">

            <a href="{{ route('dashboard') }}"

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

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('annees.*') ? 'bg-blue-600' : 'hover:bg-slate-800' }}">

                <i class="fas fa-calendar-alt w-6"></i>

                <span>Années scolaires</span>

            </a>

            <a href="{{ route('classes.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('classes.*') ? 'bg-blue-600' : 'hover:bg-slate-800' }}">

                <i class="fas fa-school w-6"></i>

                <span>Classes</span>

            </a>

            <a href="{{ route('eleves.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('eleves.*') ? 'bg-blue-600' : 'hover:bg-slate-800' }}">

                <i class="fas fa-user-graduate w-6"></i>

                <span>Élèves</span>

            </a>

            <a href="{{ route('inscriptions.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('inscriptions.*') ? 'bg-blue-600' : 'hover:bg-slate-800' }}">

                <i class="fas fa-file-signature w-6"></i>

                <span>Inscriptions</span>

            </a>

            <a href="{{ route('reinscriptions.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('reinscriptions.*') ? 'bg-blue-600' : 'hover:bg-slate-800' }}">

                <i class="fas fa-retweet w-6"></i>

                <span>Réinscriptions</span>

            </a>

            <a href="{{ route('frais.dashboard') }}"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-money-bill-wave w-6"></i>

                <span>Gestion des frais</span>

            </a>

            <a href="{{ route('paiements.index') }}"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-chart-bar w-6"></i>

                <span>Paiements</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-800">

                <i class="fas fa-cog w-6"></i>

                <span>Paramètres</span>

            </a>

        </nav>

        <!-- Footer Sidebar -->

        <div class="sidebar-footer flex-shrink-0 border-t border-slate-700 p-4 text-center text-sm text-slate-400">

            Version 1.0

        </div>

    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-950/60 lg:hidden" aria-hidden="true"></div>

    <!-- ========================= -->
    <!-- CONTENU -->
    <!-- ========================= -->

    <main id="appMain" class="h-screen min-w-0 flex flex-col overflow-hidden">

        <!-- ========================= -->
        <!-- HEADER -->
        <!-- ========================= -->

        <header class="bg-white shadow-sm min-h-16 sm:min-h-20 flex-shrink-0 flex items-center justify-between gap-3 px-3 sm:px-5 lg:px-8">

            <div class="flex min-w-0 items-center gap-2 sm:gap-4">
                <button id="mobileSidebarButton" type="button" class="lg:hidden flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200" aria-label="Ouvrir le menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <button id="desktopSidebarButton" type="button" class="hidden lg:flex flex-shrink-0 w-10 h-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200" aria-label="Reduire la barre laterale" aria-controls="sidebar" aria-expanded="true">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="min-w-0">

                <h2 class="truncate text-base sm:text-xl lg:text-2xl font-bold text-slate-700">

                    @yield('title','School Management')

                </h2>

                <p class="hidden sm:block truncate text-sm text-slate-500 mt-1">

                    @yield('breadcrumb')

                </p>

                </div>
            </div>

            <div class="flex flex-shrink-0 items-center gap-2 sm:gap-4 lg:gap-6">

                <!-- Notification -->

                <button
                    class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200" aria-label="Notifications">

                    <i class="fas fa-bell text-slate-600"></i>

                </button>

                <!-- Utilisateur -->

                <div class="relative">

                    <button

                        id="userMenuButton"

                        class="flex items-center gap-2 sm:gap-3">

                        @if(auth()->user()->photo)

                            <img

                                src="{{ asset('storage/'.auth()->user()->photo) }}"

                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border">

                        @else

                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                                {{ strtoupper(substr(auth()->user()->nom,0,1)) }}

                            </div>

                        @endif

                        <div class="hidden md:block text-left max-w-40">

                            <div class="font-semibold truncate">

                                {{ auth()->user()->nom_complet }}

                            </div>

                            <div class="text-sm text-gray-500">

                                {{ auth()->user()->type }}

                            </div>

                        </div>

                        <i class="hidden sm:block fas fa-chevron-down text-gray-500"></i>

                    </button>

                    <!-- Menu -->

                    <div

                        id="userDropdown"

                        class="hidden absolute right-0 z-50 mt-3 w-56 max-w-[calc(100vw-1.5rem)] bg-white rounded-lg shadow-xl border">

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

        <section class="page-content flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-3 sm:p-5 lg:p-8">

            <div class="max-w-7xl mx-auto min-w-0">

                @yield('content')

            </div>

        </section>

        <!-- ========================= -->
        <!-- FOOTER -->
        <!-- ========================= -->

        <footer class="bg-white border-t min-h-14 flex-shrink-0 flex items-center justify-center px-3 text-center text-gray-500 text-xs sm:text-sm">

            © {{ date('Y') }} Synergie School | Version 1.0

        </footer>

    </main>

</div>

<script>
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('sidebarOverlay');
const mobileButton=document.getElementById('mobileSidebarButton');
const closeButton=document.getElementById('closeSidebar');
const desktopButton=document.getElementById('desktopSidebarButton');
const desktopBreakpoint=window.matchMedia('(min-width: 1024px)');
const button=document.getElementById('userMenuButton');
const menu=document.getElementById('userDropdown');

sidebar.querySelectorAll('nav a').forEach(function(link){
    const label=link.querySelector('span');
    if(label) link.dataset.label=label.textContent.trim();
});

function mobileSidebar(open){
    if(desktopBreakpoint.matches) open=false;
    sidebar.classList.toggle('mobile-open',open);
    overlay.classList.toggle('hidden',!open);
    mobileButton.setAttribute('aria-expanded',String(open));
}

function desktopSidebar(collapsed){
    document.documentElement.classList.toggle('sidebar-is-collapsed',collapsed);
    sidebar.classList.toggle('sidebar-collapsed',collapsed);
    desktopButton.setAttribute('aria-expanded',String(!collapsed));
    desktopButton.setAttribute('aria-label',collapsed ? 'Deployer la barre laterale' : 'Reduire la barre laterale');
}

function restoreDesktopSidebar(){
    const collapsed=desktopBreakpoint.matches && localStorage.getItem('sidebar-collapsed')==='true';
    desktopSidebar(collapsed);
}

restoreDesktopSidebar();

desktopButton.addEventListener('click',function(){
    const collapsed=!document.documentElement.classList.contains('sidebar-is-collapsed');
    desktopSidebar(collapsed);
    localStorage.setItem('sidebar-collapsed',String(collapsed));
});

mobileButton.addEventListener('click',function(){mobileSidebar(true)});
closeButton.addEventListener('click',function(){mobileSidebar(false)});
overlay.addEventListener('click',function(){mobileSidebar(false)});
desktopBreakpoint.addEventListener('change',function(){mobileSidebar(false);restoreDesktopSidebar()});

sidebar.querySelectorAll('a').forEach(function(link){
    link.addEventListener('click',function(){
        if(!desktopBreakpoint.matches) mobileSidebar(false);
    });
});

button.addEventListener('click',function(e){
    e.stopPropagation();
    menu.classList.toggle('hidden');
});

document.addEventListener('click',function(){menu.classList.add('hidden')});
document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){
        mobileSidebar(false);
        menu.classList.add('hidden');
    }
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
