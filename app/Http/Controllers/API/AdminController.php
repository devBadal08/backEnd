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
    $perPage = $request->query('per_page', 10);
    $minAge = $request->query('min_age');
    $birthYear = $request->query('birth_year');
    $gender = $request->query('gender');
    $village = $request->query('village');
    $city = $request->query('city');
    $state = $request->query('state');
    $searchTerm = $request->query('keyword');

    $managersQuery = User::where('role', 'manager')
    ->orWhere(function ($query) use ($searchTerm) {
        $query->where('first_name', 'like', "%$searchTerm%")
            ->orWhere('last_name', 'like', "%$searchTerm%")
            ->orWhere('email', 'like', "%$searchTerm%")
            ->orWhere('phone', 'like', "%$searchTerm%");
    })
    ->with(['profiles' => function ($query) use ($searchTerm) {
        $query->where(function ($subQuery) use ($searchTerm) {
            $subQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone', 'like', '%' . $searchTerm . '%');
        });
    }]);

if (isset($searchTerm) && !empty($searchTerm)) {
    $managersQuery->whereHas('profiles', function ($query) use ($searchTerm) {
        $query->where(function ($subQuery) use ($searchTerm) {
            $subQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone', 'like', '%' . $searchTerm . '%');
        });
    });
}

    // $managersQuery = User::where('role', 'manager')->with('profiles');
    // // ->get();

    // if (isset($searchTerm) && !empty($searchTerm)) {
    //   // $managersQuery->filterBySearch($searchTerm);
    //   $managersQuery->whereHas('profiles', function ($q) use ($searchTerm) {
    //     $q->where(function ($query) use ($searchTerm) {
    //         $query->orWhere('first_name', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('email', 'like', '%' . $searchTerm . '%')
    //             ->orWhere('phone', 'like', '%' . $searchTerm . '%');
    //     });
    // });
    // }
    // dd($managersQuery->toSql());
    // print_r($managersQuery->toSql());die();
    if (isset($minAge) && !empty($minAge)) {
      // $managersQuery->filterByAge($minAge);
      $managersQuery->whereHas('profiles', function ($q) use ($minAge) {
        $q->whereRaw('age', '>=', $minAge);
      });
    }
    if (isset($birthYear) && !empty($birthYear)) {
      // $managersQuery->filterByBirthYear($birthYear);
      $managersQuery->whereHas('profiles', function ($q) use ($birthYear) {
        $q->whereYear('dob', '<=', $birthYear);
      });
    }
    if (isset($gender) && !empty($gender)) {
      // $managersQuery->filterByGender($gender);
      $managersQuery->whereHas('profiles', function ($q) use ($gender) {
        $q->where('gender', $gender);
      });
    }
    if (isset($village) && !empty($village)) {
      // $managersQuery->filterByLocation($village, $city, $state);
      $managersQuery->whereHas('profiles', function ($q) use ($village) {
        $q->where('village', 'like', '%' . $village . '%');
      });
    }
    if (isset($city) && !empty($city)) {
      // $managersQuery->filterByLocation($village, $city, $state);
      $managersQuery->whereHas('profiles', function ($q) use ($city) {
        $q->where('city', 'like', '%' . $city . '%');
      });
    }
    if (isset($state) && !empty($state)) {
      // $managersQuery->filterByLocation($village, $city, $state);
      $managersQuery->whereHas('profiles', function ($q) use ($state) {
        $q->where('state', 'like', '%' . $state . '%');
      });
    }


    // print_r($managersQuery->get()->toArray());
    // die();
    // return response()->json($managersQuery);
    return UserResource::collection($managersQuery->paginate($perPage));
    // return UserResource::collection($managersQuery);

    // return response()->json($managerQuery->paginate($perPage));
  }
}
