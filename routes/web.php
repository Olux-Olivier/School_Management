<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnneeScolaireController;

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

/*
|--------------------------------------------------------------------------
| Utilisateurs
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('utilisateurs')->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('users.index');

    Route::get('/create', [UserController::class, 'create'])->name('users.create');

    Route::post('/store', [UserController::class, 'store'])->name('users.store');

    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');

    Route::get('/{user}/show', [UserController::class, 'show'])->name('users.show');

    Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

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
