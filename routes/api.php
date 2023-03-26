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

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // User-related routes
    Route::post('/user/{user}/restore', [UserController::class, 'restore']);
    Route::delete('/user/{user}/force_delete', [UserController::class, 'forceDelete']);
    Route::apiResource('users', UserController::class);

    // Auth-related routes
    Route::post("/logout", [AuthController::class, "logout"]);
});
