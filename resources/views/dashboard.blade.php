@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-slate-800">
            Tableau de bord
        </h1>

        <p class="text-slate-500 mt-2">
            Bienvenue dans votre espace de gestion scolaire.
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- MODULES PRINCIPAUX --}}
    {{-- ========================================================= --}}

    <div class="mb-10">

        <h2
            class="text-lg
                   font-semibold
                   text-slate-800
                   mb-4">

            Gestion scolaire

        </h2>


        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   lg:grid-cols-3
                   gap-6">


            {{-- ================================================= --}}
            {{-- ÉLÈVES --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('eleves.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-blue-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-blue-50
                           text-blue-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-user-graduate text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-blue-600
                           transition">

                    Élèves

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Ajouter, consulter et gérer
                    les informations des élèves.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-blue-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- INSCRIPTIONS --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('inscriptions.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-green-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-green-50
                           text-green-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-clipboard-list text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-green-600
                           transition">

                    Inscriptions

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Inscrire un élève et consulter
                    les inscriptions scolaires.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-green-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- RÉINSCRIPTIONS --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('reinscriptions.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-indigo-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-indigo-50
                           text-indigo-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-rotate-right text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-indigo-600
                           transition">

                    Réinscriptions

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Réinscrire les élèves pour
                    une nouvelle année scolaire.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-indigo-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- CLASSES --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('classes.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-purple-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-purple-50
                           text-purple-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-school text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-purple-600
                           transition">

                    Classes

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Créer, modifier et gérer
                    les classes scolaires.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-purple-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- ANNÉES SCOLAIRES --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('annees.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-orange-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-orange-50
                           text-orange-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-calendar-alt text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-orange-600
                           transition">

                    Années scolaires

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Gérer les différentes années
                    scolaires de l'établissement.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-orange-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- FRAIS --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('frais.dashboard') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-emerald-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-emerald-50
                           text-emerald-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-file-invoice-dollar text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-emerald-600
                           transition">

                    Frais scolaires

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Gérer les frais scolaires,
                    l'historique et leur évolution.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-emerald-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- PAIEMENTS --}}
            {{-- ================================================= --}}

            <a
                href="#"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-cyan-300
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-cyan-50
                           text-cyan-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-money-bill-wave text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-cyan-600
                           transition">

                    Paiements

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Enregistrer et consulter
                    les paiements scolaires.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-cyan-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>


            {{-- ================================================= --}}
            {{-- UTILISATEURS --}}
            {{-- ================================================= --}}

            <a hidden
                href="{{ route('users.index') }}"

                class="group
                       bg-white
                       rounded-2xl
                       border border-slate-200
                       shadow-sm
                       p-6
                       hover:shadow-md
                       hover:border-slate-400
                       transition">

                <div
                    class="w-14 h-14
                           rounded-xl
                           bg-slate-100
                           text-slate-600
                           flex
                           items-center
                           justify-center
                           mb-5">

                    <i class="fas fa-users-cog text-2xl"></i>

                </div>


                <h3
                    class="text-lg
                           font-semibold
                           text-slate-800
                           group-hover:text-slate-600
                           transition">

                    Utilisateurs

                </h3>


                <p
                    class="text-sm
                           text-slate-500
                           mt-2
                           leading-relaxed">

                    Gérer les utilisateurs et
                    les accès à l'application.

                </p>


                <div
                    class="mt-5
                           text-sm
                           font-medium
                           text-slate-600">

                    Accéder au module

                    <i
                        class="fas fa-arrow-right
                               ml-2
                               group-hover:translate-x-1
                               transition">

                    </i>

                </div>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ACCÈS RAPIDES --}}
    {{-- ========================================================= --}}

    <div>

        <h2
            class="text-lg
                   font-semibold
                   text-slate-800
                   mb-4">

            Accès rapides

        </h2>


        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   lg:grid-cols-4
                   gap-4">


            <a
                href="{{ route('eleves.create') }}"

                class="flex
                       items-center
                       gap-3
                       bg-white
                       border border-slate-200
                       rounded-xl
                       p-4
                       hover:border-blue-300
                       hover:shadow-sm
                       transition">

                <i
                    class="fas fa-user-plus
                           text-blue-600
                           text-lg">

                </i>

                <span
                    class="font-medium
                           text-slate-700">

                    Ajouter un élève

                </span>

            </a>


            <a
                href="{{ route('inscriptions.create') }}"

                class="flex
                       items-center
                       gap-3
                       bg-white
                       border border-slate-200
                       rounded-xl
                       p-4
                       hover:border-green-300
                       hover:shadow-sm
                       transition">

                <i
                    class="fas fa-clipboard-check
                           text-green-600
                           text-lg">

                </i>

                <span
                    class="font-medium
                           text-slate-700">

                    Nouvelle inscription

                </span>

            </a>


            <a
                href="{{ route('frais.create') }}"

                class="flex
                       items-center
                       gap-3
                       bg-white
                       border border-slate-200
                       rounded-xl
                       p-4
                       hover:border-emerald-300
                       hover:shadow-sm
                       transition">

                <i
                    class="fas fa-plus-circle
                           text-emerald-600
                           text-lg">

                </i>

                <span
                    class="font-medium
                           text-slate-700">

                    Ajouter un frais

                </span>

            </a>


            <a
                href="#"

                class="flex
                       items-center
                       gap-3
                       bg-white
                       border border-slate-200
                       rounded-xl
                       p-4
                       hover:border-cyan-300
                       hover:shadow-sm
                       transition">

                <i
                    class="fas fa-cash-register
                           text-cyan-600
                           text-lg">

                </i>

                <span
                    class="font-medium
                           text-slate-700">

                    Enregistrer un paiement

                </span>

            </a>

        </div>

    </div>

</div>

@endsection
