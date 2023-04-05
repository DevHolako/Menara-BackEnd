<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\eCommerce\ArticleController;
use App\Http\Controllers\Api\eCommerce\CategorieController;
use App\Http\Controllers\Api\eCommerce\ClientController;
use App\Http\Controllers\Api\RolesAndPermissions\PermissionController;
use App\Http\Controllers\Api\RolesAndPermissions\RoleController;
use App\Http\Controllers\Api\Users\UserController;
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
Route::get('/', function () {
    return "Hey from Api v2";
});

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

# ------ Password Routes ------ #
#send link
Route::post("/forgot-password", [AuthController::class, "resetPassword"]);
#change password
Route::post('/reset-password', [AuthController::class, "handleResetPassword"]);
#render form
Route::get('reset-password/{token}', function ($token) {
});

// ------------ Protected Routes ------------ //

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Auth-related routes
    Route::post("/logout", [AuthController::class, "logout"]);

    // User-related routes
    Route::apiResource('users', UserController::class);
    Route::post('/user/{user}/restore', [UserController::class, 'restore']);
    Route::delete('/user/{user}/force_delete', [UserController::class, 'forceDelete']);

    // Client-related routes
    Route::apiResource('clients', ClientController::class);
    Route::post('/clients/{client}/restore', [ClientController::class, 'restore']);
    Route::delete('/clients/{client}/force_delete', [ClientController::class, 'forceDelete']);

    // Article-related routes
    Route::apiResource('articles', ArticleController::class);
    Route::post('/articles/article/restore', [ArticleController::class, 'restore']);
    Route::delete('/articles/article/force_delete', [ArticleController::class, 'forceDelete']);

    // Categorie-related routes
    Route::apiResource('categories', CategorieController::class);
    Route::post('/categories/categorie/restore', [CategorieController::class, 'restore']);
    Route::delete('/categories/categorie/force_delete', [CategorieController::class, 'forceDelete']);

    // Admin Routes
    Route::middleware(['role:Owner|Admin'])->group(function () {
        // roles and permes routes
        Route::apiResource('/permissions', PermissionController::class);
        Route::apiResource('/roles', RoleController::class);
        Route::post('/roles/{role}/permissions', [RoleController::class, 'GivePermission']);
        Route::delete('/roles/{role}/permissions', [RoleController::class, 'RevokePermission']);

    });

});
