<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //Show total count of managers,Users and Profiles on Dashboard When you will login
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin') {
            // For admin, count total managers, users, and profiles
            $totalManagers = User::where('role', 'manager')->count();
            $totalUsers = User::where('role', 'user')->count();
            $totalProfiles = Profile::count();
            return response()->json([
                'total_managers' => $totalManagers,
                'total_users' => $totalUsers,
                'total_profiles' => $totalProfiles,
            ]);
        } elseif ($currentUser->role === 'manager') {
            // For manager, count total users and profiles under that manager
            $totalUsers = User::where('created_by', $currentUser->id)->count();
            $totalProfiles = Profile::where('user_id', $currentUser->id)->count();
            return response()->json([
                'total_users' => $totalUsers,
                'total_profiles' => $totalProfiles,
            ]);
        } else {
            // For regular user, count total profiles of their manager
            $manager = User::find($currentUser->created_by);
            $totalProfiles = Profile::where('user_id', $manager->id)->count();
            return response()->json([
                'total_profiles' => $totalProfiles,
            ]);
        }
    }
}
