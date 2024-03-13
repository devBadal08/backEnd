<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\API\DB;

class AdminController extends Controller
{
  public function index()
  {
    $users = User::get();
    return response()->json($users);
  }
}
