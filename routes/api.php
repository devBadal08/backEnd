<?php

use App\Http\Controllers\API\Authcontroller;
use App\Http\Controllers\API\PasswordresetRequestcontroller;
use App\Http\Controllers\API\changeoldpasswordcontroller;
use App\Http\Controllers\API\Usercontroller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(Authcontroller::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});
Route::post('reset-password', [changeoldpasswordcontroller::class, 'reset']);
Route::post('reset-link', [PasswordresetRequestcontroller::class, 'sendEmail']);

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('/{id}/show', 'show');
    Route::get('/{id}/edit', 'edit');
    Route::post('/{id}/update', 'update');
    Route::delete('/{id}/delete', 'destroy');
});

Route::apiResource('posts', PostController::class);







// Route::put('edit-profile',[Usercontroller::class,'edit']);
//Route::post('user-update',[UserController::class,'update']);
// Route::get('posts',[PostController::class,'index']);
// Route::post('posts',[PostController::class,'store']);
// Route::put('posts/{post}',[PostController::class,'update']);
// Route::delete('posts',[PostController::class,'destroy']);
//Route::post('demo', [changeoldpasswordcontroller::class,'passwordResetProcess']);
// Route::post('ResetLink', [PasswordresetRequestcontroller::class,'sendEmail']);