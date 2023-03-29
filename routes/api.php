<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
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

// ------------ Public Routes ------------ //

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

# ------ Password Routes ------ #
#send link
Route::post("/forgot-password", [AuthController::class, "resetPassword"]);
#change password
Route::post('/reset-password', [AuthController::class, "handleResetPassword"]);
#render form
Route::get('reset-password/{token}', function ($token) {
})->name('password.reset');

// ------------ Protected Routes ------------ //

Route::group(['middleware' => ['auth:sanctum']], function () {

    // User-related routes
    Route::post('/user/{user}/restore', [UserController::class, 'restore']);
    Route::delete('/user/{user}/force_delete', [UserController::class, 'forceDelete']);

    // Auth-related routes
    Route::post("/logout", [AuthController::class, "logout"]);

    //
    Route::middleware(['role:Owner|Admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('/permissions', PermissionController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::post('/roles/{role}/permissions', [RoleController::class, 'GivePermission']);
        Route::delete('/roles/{role}/permissions', [RoleController::class, 'RevokePermission']);

    });

    Route::get('/test', function () {
        return "hey from test";
    })->middleware(['permission:test']);

});
