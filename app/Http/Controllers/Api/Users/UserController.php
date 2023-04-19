<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserCollection;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Traits\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Common;

    public function __construct()
    {
        $this->middleware('permission:view user')->only('index', 'show');
        $this->middleware('permission:store user')->only('index', 'show', 'store');
        $this->middleware('permission:update user')->only('index', 'show', 'update');
        $this->middleware('role:Owner')->only('destory', 'restore', 'forceDelete');
    }

    // Display a listing of the resource.
    public function index()
    {
        $users = User::where('role', '<>', 'Owner')->get();

        if ($users->isEmpty()) {
            return response()->json(["message" => "No users were found"], 204);
        }

        return new UserCollection($users);
    }

    // Store a newly created resource in storage.
    public function store(Request $req)
    {
        $response = $this->CreateUser($req);
        return $response;
    }

    // Display the specified resource.
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        }

        return new UserResource($user);
    }

    // Update the specified resource in storage.
    public function update(Request $req, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        }

        $fileds = $req->validate([
            'fullname' => "sometimes",
            'username' => "sometimes|unique:users,username," . $user->id,
            'email' => "sometimes|email|unique:users,email," . $user->id,
            'password' => "sometimes|confirmed|min:8",
            'role' => 'sometimes|exists:roles,name',
        ]);

        if (isset($fileds['password'])) {
            $fileds['password'] = Hash::make($fileds['password']);
        }

        $user->update($fileds);
        if (isset($fileds['role'])) {
            $user->syncRoles($fileds['role']);
        }

        $updated_user = new UserResource($user);
        return response(['message' => 'User updated successfully', "user" => $updated_user]);
    }

    //  Soft Delete the specified resource from storage.
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        }

        $user->delete();

        return response()->json(['message' => 'User soft-deleted successfully'], 201);
    }

    // Restoure the specified resource from storage.
    public function restore(string $id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        }

        $user->restore();
        return response()->json(['message' => 'User restored successfully'], 201);
    }

    // Remove premently the specified resource from storage.
    public function forceDelete(string $id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 204);
        };

        $user->forceDelete();
        return response()->json(['message' => 'User premently deleted successfully'], 201);
    }
}
