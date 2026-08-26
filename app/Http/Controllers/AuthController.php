<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLogin()
    {
        // Si l'utilisateur est déjà connecté
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Recherche de l'utilisateur
        $user = User::where('email', $request->email)->first();

        // Utilisateur inexistant
        if (!$user) {

            return back()
                ->withInput()
                ->with('error', 'Adresse e-mail ou mot de passe incorrect.');

        }

        // Mot de passe incorrect
        if (!Hash::check($request->password, $user->password)) {

            return back()
                ->withInput()
                ->with('error', 'Adresse e-mail ou mot de passe incorrect.');

        }

        // Les administrateurs disposent d'un portail et d'une session séparés.
        if ($user->type === 'Admin') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Utilisez le portail administrateur pour accéder à ce compte.');
        }

        // Compte désactivé
        if (!$user->actif) {

            return back()
                ->withInput()
                ->with('error', 'Votre compte est désactivé. Contactez un administrateur.');

        }

        // Connexion
        Auth::login($user);

        // Régénérer la session (sécurité)
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
