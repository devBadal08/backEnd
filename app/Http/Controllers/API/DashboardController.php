<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalProfiles = Profile::count();

        return response()->json([
            'total_users' => $totalUsers,
            'total_managers' => $totalManagers,
            'total_profiles' => $totalProfiles,
        ]);
    }
}

