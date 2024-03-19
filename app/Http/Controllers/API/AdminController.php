<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\API\DB;

class AdminController extends Controller
{

  // List all the managers and all profiles under particular manager
  public function index()
  {
    // $users = User::get();
    // return response()->json($users);

    $managers = User::where('role', 'manager')
        ->with('profiles') // Eager load profiles for each manager
        ->get();

    return response()->json($managers);
  }
}
