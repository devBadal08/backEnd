<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\API\DB;

class AdminController extends Controller
{

  // List all the managers and all profiles under particular manager
  public function index(Request $request)
  {
    // $users = User::get();
    // return response()->json($users);
    $perPage = $request->query('per_page', 10);
    $minAge = $request->query('min_age');
    $birthYear = $request->query('birth_year');
    $gender = $request->query('gender');
    $village = $request->query('village');
    $city = $request->query('city');
    $state = $request->query('state');
    $searchTerm = $request->query('keyword');

    $managerQuery = User::where('role', 'manager')
      ->with('profiles') // Eager load profiles for each manager
      ->get();
    // $managerQuery = User::where('created_by', auth()->user()->id);
    // echo'here';exit;

    if (isset($searchTerm) && !empty($searchTerm)) {
      $managerQuery->filterBySearch($searchTerm);
    }
    if (isset($minAge) && !empty($minAge)) {
      $managerQuery->filterByAge($minAge);
    }
    if (isset($birthYear) && !empty($birthYear)) {
      $managerQuery->filterByBirthYear($birthYear);
    }
    if (isset($gender) && !empty($gender)) {
      $managerQuery->filterByGender($gender);
    }
    if (isset($village) && !empty($village)) {
      $managerQuery->filterByLocation($village);
    }
    if (isset($city) && !empty($city)) {
      $managerQuery->filterByLocation($city);
    }
    if (isset($state) && !empty($state)) {
      $managerQuery->filterByLocation($state);
    }

    return response()->json($managerQuery);
    // return response()->json($managerQuery->paginate($perPage));
  }
}
