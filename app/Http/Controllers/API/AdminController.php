<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\API\DB;

class AdminController extends Controller
{

  // List all the managers and all profiles under particular manager
  public function index(Request $request)
  {
    // $users = User::get();
    // return response()->json($users);
    // $perPage = $request->query('per_page', 10);
    // $minAge = $request->query('min_age');
    // $birthYear = $request->query('birth_year');
    // $gender = $request->query('gender');
    // $village = $request->query('village');
    // $city = $request->query('city');
    // $state = $request->query('state');
    // $searchTerm = $request->query('keyword');

    // $managerQuery = User::where('role', 'manager')
    //   ->with('profiles') // Eager load profiles for each manager
    //   ->get();
    // $managerQuery = User::where('created_by', auth()->user()->id);
    // echo'here';exit;

    $perPage = $request->query('per_page', 10);
    $minAge = $request->query('min_age');
    $birthYear = $request->query('birth_year');
    $gender = $request->query('gender');
    $village = $request->query('village');
    $city = $request->query('city');
    $state = $request->query('state');
    $searchTerm = $request->query('keyword');

    // $managersQuery = User::where('role', 'manager')
    //   ->with('profiles')
    //   ->get();
      $managersQuery = User::where('role', 'manager')->with(['profiles']);

    if (isset($searchTerm) && !empty($searchTerm)) {
      $managersQuery->filterBySearch($searchTerm);
    }
    if (isset($minAge) && !empty($minAge)) {
      $managersQuery->filterByAge($minAge);
    }
    if (isset($birthYear) && !empty($birthYear)) {
      $managersQuery->filterByBirthYear($birthYear);
    }
    if (isset($gender) && !empty($gender)) {
      $managersQuery->filterByGender($gender);
    }
    if (isset($village) && !empty($village)) {
      $managersQuery->filterByLocation($village, $city, $state);
    }
    if (isset($city) && !empty($city)) {
      $managersQuery->filterByLocation($village, $city, $state);
    }
    if (isset($state) && !empty($state)) {
      $managersQuery->filterByLocation($village, $city, $state);
    }

    // return response()->json($managersQuery);
    // return UserResource::collection($managersQuery->paginate($perPage));
    return UserResource::collection($managersQuery);

    // return response()->json($managerQuery->paginate($perPage));
  }
}
