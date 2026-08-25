<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Synergie School</title>

    <script>
        try {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', savedTheme === 'dark' || (!savedTheme && prefersDark));
        } catch (error) {
            document.documentElement.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
    </script>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>

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
        html.dark{
            color-scheme:dark;
            --dark-bg:#182235;
            --dark-sidebar:#0f172a;
            --dark-header:#0f172a;
            --dark-card:#111b2e;
            --dark-card-hover:#162238;
            --dark-input:#111827;
            --dark-border:rgba(148,163,184,.18);
            --dark-border-hover:rgba(148,163,184,.28);
            --dark-text-primary:#f1f5f9;
            --dark-text-secondary:#94a3b8;
            --dark-text-muted:#64748b;
            --dark-blue:#3b82f6;
            --dark-blue-hover:#2563eb;
        }
        html.dark body,html.dark .page-content{background-color:var(--dark-bg)!important;color:var(--dark-text-primary)}
        html.dark #sidebar{background-color:var(--dark-sidebar)!important;color:#e2e8f0!important;border-color:var(--dark-border)!important}
        html.dark #sidebar nav a:not(.bg-blue-600):hover{background-color:rgba(59,130,246,.10)!important}
        html.dark #sidebar .sidebar-footer,html.dark #sidebar>div:first-of-type{border-color:var(--dark-border)!important}
        html.dark header{background-color:var(--dark-header)!important;border-bottom:1px solid rgba(148,163,184,.15)}
        html.dark header h2{color:#f8fafc!important}
        html.dark header button.rounded-full,html.dark #mobileSidebarButton,html.dark #desktopSidebarButton{background-color:rgba(148,163,184,.10)!important;color:#cbd5e1!important}
        html.dark header button.rounded-full:hover,html.dark #mobileSidebarButton:hover,html.dark #desktopSidebarButton:hover{background-color:rgba(148,163,184,.18)!important}
        html.dark footer{background-color:var(--dark-sidebar)!important;border-color:rgba(148,163,184,.15)!important;color:var(--dark-text-secondary)!important}
        html.dark .bg-white{background-color:var(--dark-card)!important}
        html.dark .bg-slate-50,html.dark .bg-slate-100,html.dark .bg-gray-100{background-color:#1e293b!important}
        html.dark .bg-blue-50,html.dark .bg-blue-100{background-color:rgba(59,130,246,.12)!important}
        html.dark .bg-green-50,html.dark .bg-green-100,html.dark .bg-emerald-50,html.dark .bg-emerald-100{background-color:rgba(34,197,94,.12)!important}
        html.dark .bg-indigo-50,html.dark .bg-indigo-100,html.dark .bg-violet-50,html.dark .bg-violet-100,html.dark .bg-purple-50,html.dark .bg-purple-100{background-color:rgba(139,92,246,.12)!important}
        html.dark .bg-orange-50,html.dark .bg-orange-100,html.dark .bg-amber-50,html.dark .bg-amber-100{background-color:rgba(249,115,22,.12)!important}
        html.dark .bg-teal-50,html.dark .bg-teal-100,html.dark .bg-cyan-50,html.dark .bg-cyan-100{background-color:rgba(20,184,166,.12)!important}
        html.dark .bg-red-50,html.dark .bg-red-100{background-color:rgba(239,68,68,.12)!important}
        html.dark .text-slate-950,html.dark .text-slate-900,html.dark .text-slate-800,html.dark .text-slate-700,html.dark .text-gray-900,html.dark .text-gray-800,html.dark .text-gray-700{color:var(--dark-text-primary)!important}
        html.dark .text-slate-600,html.dark .text-slate-500,html.dark .text-gray-600,html.dark .text-gray-500{color:var(--dark-text-secondary)!important}
        html.dark .text-slate-400,html.dark .text-gray-400{color:var(--dark-text-muted)!important}
        html.dark .bg-white.border,html.dark .border-slate-100,html.dark .border-slate-200,html.dark .border-slate-300,html.dark .divide-slate-100>*+*{border-color:var(--dark-border)!important}
        html.dark input,html.dark select,html.dark textarea{background-color:var(--dark-input)!important;border-color:#334155!important;color:#f8fafc!important}
        html.dark input::placeholder,html.dark textarea::placeholder{color:var(--dark-text-muted)}
        html.dark input:focus,html.dark select:focus,html.dark textarea:focus{border-color:var(--dark-blue)!important;box-shadow:0 0 0 3px rgba(59,130,246,.15)!important;outline:none}
        html.dark select option{background-color:var(--dark-input);color:#f8fafc}
        html.dark #userDropdown{background-color:var(--dark-input)!important;border-color:#334155!important;color:var(--dark-text-primary)}
        html.dark #userDropdown a:hover{background-color:#1e293b!important}
        html.dark table thead{background-color:#1e293b!important}
        html.dark table tbody{--tw-divide-opacity:1;--tw-divide-color:#334155}
        html.dark table tbody tr:hover{background-color:rgba(30,41,59,.72)!important}
        html.dark hr{border-color:#334155}
        html.dark .page-content .group.bg-white{border-color:var(--dark-border)!important;box-shadow:0 4px 16px rgba(0,0,0,.12);transition:all .2s ease}
        html.dark .page-content .group.bg-white:hover{background-color:var(--dark-card-hover)!important;border-color:var(--dark-border-hover)!important;transform:translateY(-2px)}
        html.dark .hover\:bg-slate-50:hover,html.dark .hover\:bg-slate-100:hover,html.dark .hover\:bg-slate-200:hover,html.dark .hover\:bg-gray-100:hover{background-color:#1e293b!important}
        html.dark .shadow-sm,html.dark .shadow-md,html.dark .shadow-lg,html.dark .shadow-xl,html.dark .shadow-2xl{--tw-shadow-color:rgb(0 0 0 / .22)}
        html.dark .swal2-popup{background:var(--dark-input);border:1px solid #334155;color:var(--dark-text-primary)}
        html.dark .swal2-title,html.dark .swal2-html-container{color:var(--dark-text-primary)}
        html.dark ::-webkit-scrollbar{width:8px;height:8px}
        html.dark ::-webkit-scrollbar-track{background:var(--dark-sidebar)}
        html.dark ::-webkit-scrollbar-thumb{background:#334155;border-radius:10px}
        html.dark ::-webkit-scrollbar-thumb:hover{background:#475569}
    </style>
</head>

<body class="bg-slate-100 text-slate-800 overflow-x-hidden transition-colors duration-200 dark:bg-slate-950 dark:text-slate-200">

<div class="h-screen overflow-hidden">

    <!-- ========================= -->
    <!-- SIDEBAR -->
    <!-- ========================= -->

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 h-screen bg-white text-slate-700 dark:bg-slate-900 dark:text-slate-200 flex flex-col border-r border-slate-200 dark:border-slate-700 shadow-xl lg:shadow-none" aria-label="Navigation principale">

        <!-- Logo -->

        <div class="h-20 flex-shrink-0 flex items-center justify-center border-b border-slate-200 relative">

            <div class="text-center">

                <div class="sidebar-brand w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto text-2xl font-bold transition-all duration-300">

                    SM

                </div>
{{--
                <h1 class="mt-2 text-lg font-bold">

                    Synergie School

                </h1> --}}

            </div>

        </div>

        <!-- MENU -->

        <button id="closeSidebar" type="button" class="lg:hidden absolute right-3 top-5 w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100" aria-label="Fermer le menu"><i class="fas fa-times"></i></button>

        <nav class="flex-1 min-h-0 mt-6 overflow-y-auto overflow-x-hidden">

            <a href="{{ route('dashboard') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-home w-6"></i>

                <span>Accueil</span>

            </a>

            <a href="{{ route('users.index') }}"

                class="flex items-center px-6 py-3 transition

                {{ request()->routeIs('users.*')

                    ? 'bg-blue-600 text-white'

                    : 'hover:bg-slate-100' }}">

                <i class="fas fa-users w-6"></i>

                <span>Utilisateurs</span>

            </a>

            <a href="{{ route('annees.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('annees.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-calendar-alt w-6"></i>

                <span>Années scolaires</span>

            </a>

            <a href="{{ route('classes.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('classes.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-school w-6"></i>

                <span>Classes</span>

            </a>

            <a href="{{ route('eleves.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('eleves.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-user-graduate w-6"></i>

                <span>Élèves</span>

            </a>

            <a href="{{ route('inscriptions.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('inscriptions.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-file-signature w-6"></i>

                <span>Inscriptions</span>

            </a>

            <a href="{{ route('reinscriptions.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('reinscriptions.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-retweet w-6"></i>

                <span>Réinscriptions</span>

            </a>

            <a href="{{ route('frais.dashboard') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('frais.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-money-bill-wave w-6"></i>

                <span>Gestion des frais</span>

            </a>

            <a href="{{ route('paiements.index') }}"

                class="flex items-center px-6 py-3 transition {{ request()->routeIs('paiements.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100' }}">

                <i class="fas fa-chart-bar w-6"></i>

                <span>Paiements</span>

            </a>

            <a href="#"

                class="flex items-center px-6 py-3 hover:bg-slate-100 transition">

                <i class="fas fa-cog w-6"></i>

                <span>Paramètres</span>

            </a>

        </nav>

        <!-- Footer Sidebar -->

        <div class="sidebar-footer flex-shrink-0 border-t border-slate-200 p-4 text-center text-sm text-slate-400">

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

        <header class="bg-white dark:bg-slate-900 shadow-sm min-h-16 sm:min-h-20 flex-shrink-0 flex items-center justify-between gap-3 px-3 sm:px-5 lg:px-8">

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

                <!-- Thème clair / sombre -->
                <button id="themeToggle" type="button"
                    class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition"
                    aria-label="Activer le mode sombre" title="Changer le thème">
                    <i id="themeIcon" class="fas fa-moon"></i>
                </button>

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

        <footer class="bg-white dark:bg-slate-900 border-t dark:border-slate-700 min-h-14 flex-shrink-0 flex items-center justify-center px-3 text-center text-gray-500 text-xs sm:text-sm">

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
const themeToggle=document.getElementById('themeToggle');
const themeIcon=document.getElementById('themeIcon');

function updateThemeButton(){
    const isDark=document.documentElement.classList.contains('dark');
    themeIcon.className=isDark ? 'fas fa-sun' : 'fas fa-moon';
    themeToggle.setAttribute('aria-label',isDark ? 'Activer le mode clair' : 'Activer le mode sombre');
    themeToggle.title=isDark ? 'Mode clair' : 'Mode sombre';
}

function setTheme(theme){
    document.documentElement.classList.toggle('dark',theme==='dark');
    localStorage.setItem('theme',theme);
    updateThemeButton();
}

updateThemeButton();

themeToggle.addEventListener('click',function(){
    const nextTheme=document.documentElement.classList.contains('dark') ? 'light' : 'dark';
    setTheme(nextTheme);
});

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
