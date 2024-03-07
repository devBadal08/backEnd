<?php

use App\Http\Controllers\API\Usercontroller;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\NewPasswordController;
use App\Http\Controllers\API\PasswordResetRequestController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ManagerController;

use App\Http\Controllers\Api\{
    LoginController,
    RegisterController
};

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::controller(Authcontroller::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login','login');
}); */

Route::post('reset-link', [PasswordResetRequestController::class, 'sendEmail']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);

Route::get('users', [UserController::class, 'index']);
Route::get('/{id}/edit', [UserController::class, 'edit']);
Route::middleware('auth:sanctum')->post('/{id}/update', [UserController::class, 'update']);
Route::get('/{id}/show', [UserController::class, 'show']);
Route::delete('/{id}/delete', [UserController::class, 'destroy']);

// Route::apiResource('posts',PostController::class);

// Route::get('posts',[PostController::class,'index']);
// Route::post('posts',[PostController::class,'store']);
// Route::get('posts/{post}',[PostController::class,'show']);
// Route::put('posts/{post}',[PostController::class,'update']);
// Route::delete('posts',[PostController::class,'destroy']);

// Route::get('posts/{post}',[PostController::class,'show']);

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum', 'roles:admin']], function () {
    // Route::get('users', function () {
    // dd('usrs list');
    Route::get('users', [UserController::class, 'index']);

    // });
});

// Route::group(['middleware'=>['auth','roles:admin,user']],function(){
// });

// Route::controller(AdminController::class)->group(function(){
//     Route::get('list_mgr', 'index');
//     Route::post('store_mgr','store');
// });

Route::controller(ManagerController::class)->group(function(){
    Route::get('manager', 'index');
    Route::post('manager/store','store');
    
    
    // manager/edit
    // manager/update
    // manager/delete
});
