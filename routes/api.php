<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    RegisterController,
    LoginController,
    PasswordResetRequestController,
    NewPasswordController,
    AdminController,
    ManagerController,
    Usercontroller,
    ProfileController,
    EducationController,

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


//ROute for log-in
Route::post('login', [LoginController::class, 'login']);

//Route for register (currently not in use)
Route::post('register', [RegisterController::class, 'register']);

//Route for reset password which sent a reset link
Route::post('reset-link', [PasswordResetRequestController::class, 'sendEmail']);

//Route for change password
Route::post('reset-password', [NewPasswordController::class, 'reset']);


Route::group(['middleware' => ['auth:sanctum']], function () {

    //Routes for user
    Route::controller(UserController::class)->group(function () {
        Route::get('allusers', 'listUsers');
        Route::get('users', 'index');
        Route::get('{id}/user/edit', 'edit');
        Route::post('user/store', 'store');
        Route::post('{id}/user/update', 'update');
        Route::post('{id}/user/userupdate', 'user_update');
        Route::post('{id}/user/show', 'show');
        Route::delete('{id}/user/delete', 'destroy');
    });

    //Routes for manager

    Route::controller(ManagerController::class)->group(function () {
        Route::get('managers', 'index');
        Route::get('{id}/manager/edit', 'edit');
        Route::post('manager/store', 'store');
        Route::post('{id}/manager/update', 'update');
        Route::post('{id}/manager/managerupdate', 'manager_update');
        Route::post('{id}/manager/show', 'show');
    });

    //Routes for profile 

    Route::controller(ProfileController::class)->group(function () {
        Route::get('{id}/profiles', 'index');
        Route::post('profile/personal/store', 'store');
        Route::post('{id}/profile/update', 'profile_update');
        Route::delete('{id}/profile/delete', 'destroy');


    });

    // Routes for profile education
    Route::controller(EducationController::class)->group(function () {
        Route::post('profile/education/store', 'storeEducation');
        Route::post('{id}/education/update', 'educationUpdate');


    });

    //Routes for admin to get all user and manager simultaneously

Route::get('allmembers', [AdminController::class, 'index']);

});