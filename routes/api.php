<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Public Routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

## Password Reset ##
#send email##
Route::post("/forgot-password", [AuthController::class, "resetPassword"]);
#change password#
Route::post('/reset-password', [AuthController::class, "handleResetPassword"]);

#render form ##
Route::get('reset-password/{token}', function ($token) {
})->name('password.reset');

// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// });

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // User-related routes
    Route::post('/user/{user}/restore', [UserController::class, 'restore']);
    Route::delete('/user/{user}/force_delete', [UserController::class, 'forceDelete']);
    Route::apiResource('users', UserController::class);

    // Auth-related routes
    Route::post("/logout", [AuthController::class, "logout"]);
});
