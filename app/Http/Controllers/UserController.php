<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // Display a listing of the resource.
    public function index()
    {
        $users = User::all();
        if (!$users) {
            return response()->json(["message" => "No users were found"], 201);
        };
        return response()->json(['users' => $users], 201);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {

        $fileds = $req->validate([
            'first_name' => "string|required",
            'last_name' => "string|required",
            'username' => "string|required|unique:users,username",
            'email' => "email|required|unique:users,email",
            'password' => "string|required|confirmed|min:8",
            'role_id' => 'required|exists:roles,id',
        ]);

        // Call the register method from the AuthController to create a new user
        $user = app('App\Http\Controllers\AuthController')->register($req);

        // Return a response
        return $user;

    }

    // Display the specified resource.
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);

    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $fileds = $req->validate([
            'first_name' => "sometimes|required",
            'last_name' => "sometimes|required",
            'username' => "sometimes|required|unique:users,username," . $user->id,
            'email' => "sometimes|email|required|unique:users,email," . $user->id,
            'password' => "sometimes|required|confirmed|min:8",
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        if (isset($fileds['password'])) {
            $fileds['password'] = Hash::make($fileds['password']);
        }

        $user->update($fileds);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);

    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->restore();
        return response()->json(['message' => 'User restored successfully'], 201);

    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        };

        $user->forceDelete();
        return response()->json(['message' => 'User premently deleted successfully'], 201);

    }
}
