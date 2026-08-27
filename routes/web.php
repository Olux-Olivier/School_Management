<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\ReinscriptionController;
use App\Http\Controllers\FraisController;
use App\Http\Controllers\HistoriqueFraisController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Utilisateurs
|--------------------------------------------------------------------------
*/

Route::prefix('administration')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::middleware(['auth:admin', 'admin'])->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::prefix('utilisateurs')->group(function () {

        Route::get('/', [UserController::class, 'index'])->name('users.index');

        Route::get('/create', [UserController::class, 'create'])->name('users.create');

        Route::post('/store', [UserController::class, 'store'])->name('users.store');

        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');

        Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');

        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
        });
    });

});



/*|--------------------------------------------------------------------------
| Années scolaires
|--------------------------------------------------------------------------*/

Route::middleware('auth')->prefix('annees-scolaires')->group(function () {

    Route::get('/', [AnneeScolaireController::class, 'index'])
        ->name('annees.index');

    Route::get('/create', [AnneeScolaireController::class, 'create'])
        ->name('annees.create');

    Route::post('/store', [AnneeScolaireController::class, 'store'])
        ->name('annees.store');

    Route::get('/{annee}/show', [AnneeScolaireController::class, 'show'])
        ->name('annees.show');

    Route::get('/{annee}/edit', [AnneeScolaireController::class, 'edit'])
        ->name('annees.edit');

    Route::put('/{annee}', [AnneeScolaireController::class, 'update'])
        ->name('annees.update');

    Route::patch('/{annee}/toggle-status', [AnneeScolaireController::class, 'toggleStatus'])
        ->name('annees.toggle-status');

});

/*|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------*/

Route::prefix('classes')
    ->middleware('auth')
    ->group(function () {

        // Liste
        Route::get('/', [ClasseController::class, 'index'])
            ->name('classes.index');

        // Formulaire d'ajout
        Route::get('/create', [ClasseController::class, 'create'])
            ->name('classes.create');

        // Enregistrement
        Route::post('/store', [ClasseController::class, 'store'])
            ->name('classes.store');

        // Modification
        Route::get('/{classe}/edit', [ClasseController::class, 'edit'])
            ->name('classes.edit');

        // Mise à jour
        Route::put('/{classe}', [ClasseController::class, 'update'])
            ->name('classes.update');

        // Consultation
        Route::get('/{classe}/show', [ClasseController::class, 'show'])
            ->name('classes.show');

        // Activation / désactivation
        Route::patch('/{classe}/toggle-status', [ClasseController::class, 'toggleStatus'])
            ->name('classes.toggle-status');

    });

/*|--------------------------------------------------------------------------
| Élèves
|--------------------------------------------------------------------------*/


Route::prefix('eleves')
    ->middleware('auth')
    ->group(function () {

        // Liste des élèves
        Route::get('/', [EleveController::class, 'index'])
            ->name('eleves.index');

        // Formulaire d'ajout
        Route::get('/create', [EleveController::class, 'create'])
            ->name('eleves.create');

        // Enregistrer un élève
        Route::post('/store', [EleveController::class, 'store'])
            ->name('eleves.store');

        // Formulaire de modification
        Route::get('/{eleve}/edit', [EleveController::class, 'edit'])
            ->name('eleves.edit');

        // Modifier un élève
        Route::put('/{eleve}', [EleveController::class, 'update'])
            ->name('eleves.update');

        // Consultation
        Route::get('/{eleve}/show', [EleveController::class, 'show'])
            ->name('eleves.show');

        // Activer / désactiver
        Route::patch('/{eleve}/toggle-status', [EleveController::class, 'toggleStatus'])
            ->name('eleves.toggle-status');

    });


Route::prefix('inscriptions')->name('inscriptions.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LISTE
    |--------------------------------------------------------------------------
    */

    Route::get('/', [InscriptionController::class, 'index'])
        ->name('index');


    /*
    |--------------------------------------------------------------------------
    | CRÉATION
    |--------------------------------------------------------------------------
    */

    Route::get('/create', [InscriptionController::class, 'create'])
        ->name('create');


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE ÉLÈVES
    |--------------------------------------------------------------------------
    */

    Route::get('/search-eleves', [InscriptionController::class, 'searchEleves'])
        ->name('search-eleves');


    /*
    |--------------------------------------------------------------------------
    | CLASSES SELON LA SECTION
    |--------------------------------------------------------------------------
    */

    Route::get('/classes', [InscriptionController::class, 'classes'])
        ->name('classes');


    /*
    |--------------------------------------------------------------------------
    | ENREGISTREMENT
    |--------------------------------------------------------------------------
    */

    Route::post('/', [InscriptionController::class, 'store'])
        ->name('store');

    /*
    |--------------------------------------------------------------------------
    | generation pdf
    |--------------------------------------------------------------------------
    */

    Route::get('/{inscription}/pdf', [InscriptionController::class, 'pdf'])
        ->name('pdf');

    /*
    |--------------------------------------------------------------------------
    | CONSULTATION
    |--------------------------------------------------------------------------
    */

    Route::get('/{inscription}', [InscriptionController::class, 'show'])
        ->name('show');


    /*
    |--------------------------------------------------------------------------
    | MODIFICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/{inscription}/edit', [InscriptionController::class, 'edit'])
        ->name('edit');


    /*
    |--------------------------------------------------------------------------
    | MISE À JOUR
    |--------------------------------------------------------------------------
    */

    Route::put('/{inscription}', [InscriptionController::class, 'update'])
        ->name('update');


    /*
    |--------------------------------------------------------------------------
    | SUPPRESSION
    |--------------------------------------------------------------------------
    */

    Route::delete('/{inscription}', [InscriptionController::class, 'destroy'])
        ->name('destroy');

});

/*|--------------------------------------------------------------------------
| Réinscriptions
|--------------------------------------------------------------------------*/

Route::prefix('reinscriptions')->name('reinscriptions.')->group(function () {

     /*
        |--------------------------------------------------------------------------
        | Liste des élèves à réinscrire
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [ReinscriptionController::class, 'index']
        )->name('index');


        /*
        |--------------------------------------------------------------------------
        | Formulaire de réinscription
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{inscription}/create',
            [ReinscriptionController::class, 'create']
        )->name('create');


        /*
        |--------------------------------------------------------------------------
        | Enregistrer la réinscription
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [ReinscriptionController::class, 'store']
        )->name('store');


});

/* Route pour les frais */

Route::prefix('frais')
    ->name('frais.')
    ->controller(FraisController::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', 'dashboard')
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | COMPARAISON
        |--------------------------------------------------------------------------
        */

        Route::get('/comparaison', 'comparaison')
            ->name('comparaison');


        /*
        |--------------------------------------------------------------------------
        | CLASSES PAR SECTION
        |--------------------------------------------------------------------------
        |
        | Cette route doit être placée avant /{frais}/edit
        |
        */

        Route::get('/classes', 'classesParSection')
            ->name('classes');


        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        Route::get('/', 'index')
            ->name('index');


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Route::get('/create', 'create')
            ->name('create');


        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post('/', 'store')
            ->name('store');


        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        */

        Route::get('/{frais}/edit', 'edit')
            ->name('edit');


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        Route::put('/{frais}', 'update')
            ->name('update');

    });

Route::get('/historique-frais', [HistoriqueFraisController::class,'index'])->name('frais.historique');
Route::get('/evolution-frais', [HistoriqueFraisController::class,'evolution'])->name('frais.evolution');


/*|--------------------------------------------------------------------------
| Paiements
|--------------------------------------------------------------------------*/
Route::prefix('paiements')
    ->name('paiements.')
    ->group(function () {

        Route::get('/dashboard', [
            PaiementController::class,
            'dashboard'
        ])->name('dashboard');

        // Détails des paiements d'une journée
        Route::get('/details-jour', [PaiementController::class, 'detailsJour'])
        ->name('details-jour');

        // Recherche des élèves et historique
        Route::get('/', [
            PaiementController::class,
            'index'
        ])->name('index');

        Route::get('/{eleve}', [
            PaiementController::class,
            'show'
        ])->name('show');

        Route::get('/{eleve}/create', [
            PaiementController::class,
            'create'
        ])->name('create');

        Route::post('/', [
            PaiementController::class,
            'store'
        ])->name('store');

        Route::get('/{paiement}/edit', [
            PaiementController::class,
            'edit'
        ])->name('edit');

        Route::put('/{paiement}', [
            PaiementController::class,
            'update'
        ])->name('update');



    });



/*|--------------------------------------------------------------------------
| Route pour créer un administrateur par défaut
|--------------------------------------------------------------------------*/

Route::get('/create-default-admin', function () {

    $admin = User::where('username', 'admin')->first();

    if ($admin) {
        return response()->json([
            'success' => false,
            'message' => 'L’administrateur existe déjà.',
            'username' => $admin->username,
        ]);
    }

    $admin = User::create([
        'nom' => 'Administrateur',
        'postnom' => 'Système',
        'prenom' => 'Admin',
        'sexe' => 'M',
        'telephone' => null,
        'email' => 'admin@school.local',
        'username' => 'admin',
        'password' => Hash::make('admin123'),
        'type' => 'Admin',
        'photo' => null,
        'actif' => true,
        'created_by' => null,
        'updated_by' => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Administrateur créé avec succès.',
        'admin' => [
            'id' => $admin->id,
            'nom' => $admin->nom,
            'username' => $admin->username,
            'email' => $admin->email,
        ],
    ]);
});
