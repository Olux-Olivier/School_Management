<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('actif', true)->count(),
            'inactiveUsers' => User::where('actif', false)->count(),
            'adminUsers' => User::where('type', 'Admin')->count(),
            'recentUsers' => User::latest()->take(5)->get(),
        ]);
    }
}
