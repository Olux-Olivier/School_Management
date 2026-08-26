<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = User::where('email', $credentials['email'])
            ->where('type', 'Admin')
            ->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Identifiants administrateur incorrects.');
        }

        if (! $admin->actif) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Ce compte administrateur est désactivé.');
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
