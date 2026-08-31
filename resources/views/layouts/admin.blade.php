<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') | Synergie School</title>
    <script>
        try {
            const adminTheme = localStorage.getItem('admin-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', adminTheme === 'dark' || (!adminTheme && prefersDark));
        } catch (error) {
            document.documentElement.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html.dark{color-scheme:dark}
        html.dark body,html.dark main{background:#0f172a;color:#e2e8f0}
        html.dark header{background:#111827;border-color:#334155}
        html.dark .bg-white{background-color:#111827!important}
        html.dark .bg-slate-50,html.dark .bg-slate-100,html.dark .bg-gray-100{background-color:#1e293b!important}
        html.dark .text-slate-900,html.dark .text-slate-800,html.dark .text-slate-700,html.dark .text-gray-800,html.dark .text-gray-700{color:#f1f5f9!important}
        html.dark .text-slate-600,html.dark .text-slate-500,html.dark .text-gray-500{color:#94a3b8!important}
        html.dark .border-slate-100,html.dark .border-slate-200,html.dark .border-slate-300,html.dark .divide-slate-100>*+*{border-color:#334155!important}
        html.dark input,html.dark select,html.dark textarea{background:#0f172a!important;border-color:#475569!important;color:#f8fafc!important}
        html.dark input::placeholder,html.dark textarea::placeholder{color:#64748b}
        html.dark table thead{background:#1e293b!important}
        html.dark table tbody tr:hover{background:rgba(30,41,59,.75)!important}
        html.dark .bg-blue-100{background-color:rgba(59,130,246,.15)!important}
        html.dark .bg-green-100{background-color:rgba(34,197,94,.15)!important}
        html.dark .bg-orange-100{background-color:rgba(249,115,22,.15)!important}
        html.dark .bg-violet-100{background-color:rgba(139,92,246,.15)!important}
        html.dark .bg-red-100{background-color:rgba(239,68,68,.15)!important}
        html.dark .swal2-popup{background:#111827;color:#f1f5f9;border:1px solid #334155}
        html.dark .swal2-title,html.dark .swal2-html-container{color:#f1f5f9}
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased transition-colors duration-200 dark:bg-slate-950 dark:text-slate-200">
    <div class="min-h-screen lg:flex">
        <div id="adminOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/60 lg:hidden"></div>

        <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white text-slate-700 shadow-xl transition-all dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 lg:static lg:translate-x-0 lg:shadow-none">
            <div class="flex h-20 items-center gap-3 border-b border-slate-200 px-6 dark:border-slate-800">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 font-bold text-white">SA</div>
                <div>
                    <p class="font-bold text-slate-800 dark:text-white">Synergie Admin</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Centre d’administration</p>
                </div>
            </div>

            <nav class="flex-1 space-y-2 p-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-5"></i><span>Tableau de bord</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-users-cog w-5"></i><span>Utilisateurs</span>
                </a>
                <a href="{{ route('frais.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('frais.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-money-bill-wave w-5"></i><span>Gestion des frais</span>
                </a>

            </nav>

            <div class="border-t border-slate-200 p-4 dark:border-slate-800">
                 <a href="{{ route('login.post') }}" class="flex items-center  gap-3 rounded-xl px-4 py-3 transition {{ request()->routeIs('login.post') ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-right-to-bracket w-5"></i><span>Login Agent</span>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-red-300 transition hover:bg-red-500/10 hover:text-red-200">
                        <i class="fas fa-sign-out-alt w-5"></i><span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 flex h-20 items-center justify-between border-b border-slate-200 bg-white px-4 transition-colors dark:border-slate-700 dark:bg-slate-900 sm:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <button id="adminMenuButton" class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 lg:hidden" aria-label="Ouvrir le menu"><i class="fas fa-bars"></i></button>
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-bold text-slate-800">@yield('title', 'Administration')</h1>
                        <p class="hidden truncate text-xs text-slate-500 sm:block">@yield('breadcrumb', 'Espace administrateur')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button id="adminThemeToggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700" aria-label="Activer le mode sombre" title="Changer le thème">
                        <i id="adminThemeIcon" class="fas fa-moon"></i>
                    </button>
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold">{{ auth('admin')->user()->nom_complet }}</p>
                        <p class="text-xs text-slate-500">Administrateur</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 font-bold text-white">{{ strtoupper(substr(auth('admin')->user()->nom, 0, 1)) }}</div>
                </div>
            </header>

            <section class="p-4 sm:p-6 lg:p-8">
                <div class="mx-auto max-w-7xl">@yield('content')</div>
            </section>
        </main>
    </div>

    <script>
        const adminSidebar = document.getElementById('adminSidebar');
        const adminOverlay = document.getElementById('adminOverlay');
        const adminThemeToggle = document.getElementById('adminThemeToggle');
        const adminThemeIcon = document.getElementById('adminThemeIcon');

        function updateAdminThemeButton() {
            const isDark = document.documentElement.classList.contains('dark');
            adminThemeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            adminThemeToggle.setAttribute('aria-label', isDark ? 'Activer le mode clair' : 'Activer le mode sombre');
            adminThemeToggle.title = isDark ? 'Mode clair' : 'Mode sombre';
        }

        updateAdminThemeButton();
        adminThemeToggle.addEventListener('click', function () {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
            updateAdminThemeButton();
        });
        document.getElementById('adminMenuButton').addEventListener('click', function () {
            adminSidebar.classList.toggle('-translate-x-full');
            adminOverlay.classList.toggle('hidden');
        });
        adminOverlay.addEventListener('click', function () {
            adminSidebar.classList.add('-translate-x-full');
            adminOverlay.classList.add('hidden');
        });
    </script>

    @if(session('success'))
        <script>Swal.fire({icon:'success', title:'Succès', text:@json(session('success')), timer:2000, showConfirmButton:false});</script>
    @endif
    @if(session('error'))
        <script>Swal.fire({icon:'error', title:'Erreur', text:@json(session('error'))});</script>
    @endif
</body>
</html>
