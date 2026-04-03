<?php

namespace App\Http\Controllers;

use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $adminUsers = User::where('role_id', 1)->count();
        $activeUsers = User::where('status', 1)->count();

        return view('settings.index', compact('totalUsers', 'adminUsers', 'activeUsers'));
    }
}
