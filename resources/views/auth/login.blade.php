<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Connexion | Synergie School</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

    <!-- CONTENEUR PRINCIPAL -->
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden p-4 sm:p-6 lg:p-10
                before:absolute before:-left-32 before:-top-32 before:h-96 before:w-96 before:rounded-full before:bg-blue-600/20 before:blur-3xl
                after:absolute after:-bottom-40 after:-right-24 after:h-96 after:w-96 after:rounded-full after:bg-cyan-500/10 after:blur-3xl">

        <!-- CARTE PRINCIPALE -->
        <div
            class="relative z-10 w-full max-w-6xl overflow-hidden rounded-3xl
                   border border-white/10 bg-white shadow-2xl shadow-black/30">

            <div class="grid min-h-[650px] lg:grid-cols-[1.08fr_.92fr]">

                <!-- ===================================================== -->
                <!-- SLIDER -->
                <!-- ===================================================== -->

                <div
                    id="slider"
                    class="hidden lg:block relative bg-[#0755dd] overflow-hidden">

                    <!-- SLIDE 1 -->
                    <div
                        class="slide absolute inset-0
                               opacity-100
                               transition-opacity
                               duration-700
                               ease-in-out">

                        <img
                            src="{{ asset('slider/slide_1.png') }}"
                            alt="Gestion des élèves"
                            class="absolute inset-0
                                   w-full h-full
                                   object-contain">

                    </div>


                    <!-- SLIDE 2 -->
                    <div
                        class="slide absolute inset-0
                               opacity-0
                               transition-opacity
                               duration-700
                               ease-in-out">

                        <img
                            src="{{ asset('slider/slide_2.png') }}"
                            alt="Gestion des paiements"
                            class="absolute inset-0
                                   w-full h-full
                                   object-contain">

                    </div>


                    <!-- SLIDE 3 -->
                    <div
                        class="slide absolute inset-0
                               opacity-0
                               transition-opacity
                               duration-700
                               ease-in-out">

                        <img
                            src="{{ asset('slider/slide_3.png') }}"
                            alt="Pilotage de l'établissement"
                            class="absolute inset-0
                                   w-full h-full
                                   object-contain">

                    </div>


                    <!-- INDICATEURS -->
                    <div
                        class="absolute bottom-5
                               left-1/2
                               -translate-x-1/2
                               z-30
                               flex items-center
                               gap-3">

                        <!-- Slide 1 -->
                        <button
                            type="button"
                            data-slide="0"
                            aria-label="Afficher la première slide"
                            class="slider-dot
                                   w-3 h-3
                                   rounded-full
                                   bg-white
                                   scale-125
                                   transition-all
                                   duration-300">
                        </button>


                        <!-- Slide 2 -->
                        <button
                            type="button"
                            data-slide="1"
                            aria-label="Afficher la deuxième slide"
                            class="slider-dot
                                   w-3 h-3
                                   rounded-full
                                   bg-white/40
                                   transition-all
                                   duration-300">
                        </button>


                        <!-- Slide 3 -->
                        <button
                            type="button"
                            data-slide="2"
                            aria-label="Afficher la troisième slide"
                            class="slider-dot
                                   w-3 h-3
                                   rounded-full
                                   bg-white/40
                                   transition-all
                                   duration-300">
                        </button>

                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- PARTIE CONNEXION -->
                <!-- ===================================================== -->

                <div class="flex items-center justify-center bg-white">

                    <div class="w-full max-w-md px-6 py-10 sm:px-10 lg:px-12">


                        <!-- ============================================= -->
                        <!-- LOGO MOBILE -->
                        <!-- ============================================= -->

                        <div class="mb-10 text-center lg:hidden">

                            <div
                                class="w-16 h-16
                                       rounded-2xl
                                       bg-blue-600
                                       mx-auto
                                       flex
                                       items-center
                                       justify-center
                                       text-white
                                       text-2xl
                                       font-bold shadow-lg shadow-blue-200">

                                SS

                            </div>

                            <h1 class="text-2xl font-bold text-gray-800 mt-4">

                                Synergie School

                            </h1>

                            <p class="text-gray-500 mt-1">

                                Système de Gestion Scolaire

                            </p>

                        </div>


                        <!-- ============================================= -->
                        <!-- TITRE -->
                        <!-- ============================================= -->

                        <p class="mb-2 text-sm font-semibold text-blue-600">Heureux de vous revoir</p>
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-950 mb-3">

                            Connexion

                        </h2>

                        <p class="text-slate-500 leading-6 mb-8">

                            Connectez-vous pour accéder à l'application.

                        </p>


                        <!-- ============================================= -->
                        <!-- MESSAGE ERREUR -->
                        <!-- ============================================= -->

                        @if(session('error'))

                            <div
                                class="mb-6
                                       bg-red-50
                                       border
                                       border-red-300
                                       text-red-700
                                       px-4
                                       py-3
                                       rounded-xl"
                                role="alert">

                                {{ session('error') }}

                            </div>

                        @endif


                        <!-- ============================================= -->
                        <!-- FORMULAIRE -->
                        <!-- ============================================= -->

                        <form method="POST" action="{{ route('login.post') }}">

                            @csrf


                            <!-- EMAIL -->
                            <div class="mb-5">

                                <label
                                    for="email"
                                    class="block
                                           mb-2
                                           font-medium
                                           text-slate-700 text-sm">

                                    Adresse e-mail

                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    autofocus
                                    class="w-full
                                           border
                                           border-slate-200 bg-slate-50
                                           rounded-xl
                                           px-4
                                           py-3.5
                                           focus:ring-2
                                           focus:ring-blue-500
                                           focus:border-blue-500
                                           focus:bg-white focus:outline-none transition"
                                    placeholder="email@ecole.com"
                                    required>

                                @error('email')

                                    <small class="text-red-600 mt-1 block">

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>


                            <!-- MOT DE PASSE -->
                            <div class="mb-6">

                                <label
                                    for="password"
                                    class="block
                                           mb-2
                                           font-medium
                                           text-slate-700 text-sm">

                                    Mot de passe

                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="current-password"
                                    class="w-full
                                           border
                                           border-slate-200 bg-slate-50
                                           rounded-xl
                                           px-4
                                           py-3.5
                                           focus:ring-2
                                           focus:ring-blue-500
                                           focus:border-blue-500
                                           focus:bg-white focus:outline-none transition"
                                    placeholder="Votre mot de passe"
                                    required>

                                @error('password')

                                    <small class="text-red-600 mt-1 block">

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>


                            <!-- BOUTON CONNEXION -->
                            <button
                                type="submit"
                                class="w-full
                                       bg-blue-600
                                       hover:bg-blue-700 hover:-translate-y-0.5
                                       active:bg-blue-900
                                       transition
                                       duration-200
                                       text-white
                                       py-3.5
                                       rounded-xl
                                       font-semibold shadow-lg shadow-blue-200">

                                Se connecter

                            </button>

                        </form>

                        <p class="mt-10 text-center text-xs text-slate-400">
                            © {{ date('Y') }} Synergie School · Accès sécurisé
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ============================================================= -->
    <!-- JAVASCRIPT SLIDER -->
    <!-- ============================================================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const slider = document.getElementById('slider');

            const slides = slider.querySelectorAll('.slide');

            const dots = slider.querySelectorAll('.slider-dot');


            let currentSlide = 0;

            let sliderInterval = null;


            // Temps entre deux slides
            const slideDelay = 5000;


            /*
            |--------------------------------------------------------------------------
            | AFFICHER UNE SLIDE
            |--------------------------------------------------------------------------
            */

            function showSlide(index) {

                // Retour première slide
                if (index >= slides.length) {

                    index = 0;

                }


                // Retour dernière slide
                if (index < 0) {

                    index = slides.length - 1;

                }


                /*
                |--------------------------------------------------------------------------
                | IMAGES
                |--------------------------------------------------------------------------
                */

                slides.forEach(function (slide, i) {

                    if (i === index) {

                        slide.classList.remove('opacity-0');

                        slide.classList.add('opacity-100');

                        slide.style.zIndex = '10';

                    } else {

                        slide.classList.remove('opacity-100');

                        slide.classList.add('opacity-0');

                        slide.style.zIndex = '0';

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | INDICATEURS
                |--------------------------------------------------------------------------
                */

                dots.forEach(function (dot, i) {

                    if (i === index) {

                        dot.classList.remove('bg-white/40');

                        dot.classList.add(
                            'bg-white',
                            'scale-125'
                        );

                    } else {

                        dot.classList.remove(
                            'bg-white',
                            'scale-125'
                        );

                        dot.classList.add('bg-white/40');

                    }

                });


                currentSlide = index;

            }


            /*
            |--------------------------------------------------------------------------
            | SLIDE SUIVANTE
            |--------------------------------------------------------------------------
            */

            function nextSlide() {

                const nextIndex =
                    (currentSlide + 1) % slides.length;

                showSlide(nextIndex);

            }


            /*
            |--------------------------------------------------------------------------
            | DÉMARRER LE SLIDER
            |--------------------------------------------------------------------------
            */

            function startSlider() {

                stopSlider();

                sliderInterval = setInterval(
                    nextSlide,
                    slideDelay
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ARRÊTER LE SLIDER
            |--------------------------------------------------------------------------
            */

            function stopSlider() {

                if (sliderInterval !== null) {

                    clearInterval(sliderInterval);

                    sliderInterval = null;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CLIC SUR LES POINTS
            |--------------------------------------------------------------------------
            */

            dots.forEach(function (dot) {

                dot.addEventListener('click', function () {

                    const index = Number(
                        this.dataset.slide
                    );

                    showSlide(index);

                    // Recommencer le compteur
                    startSlider();

                });

            });


            /*
            |--------------------------------------------------------------------------
            | PAUSE LORS DU SURVOL
            |--------------------------------------------------------------------------
            */

            slider.addEventListener('mouseenter', function () {

                stopSlider();

            });


            /*
            |--------------------------------------------------------------------------
            | REPRENDRE APRÈS LE SURVOL
            |--------------------------------------------------------------------------
            */

            slider.addEventListener('mouseleave', function () {

                startSlider();

            });


            /*
            |--------------------------------------------------------------------------
            | INITIALISATION
            |--------------------------------------------------------------------------
            */

            showSlide(0);

            startSlider();

        });

    </script>

</body>

</html>
