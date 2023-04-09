<?php
namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

trait Common
{
    public function CreateUser($req)
    {
        $fileds = $req->validate([
            'fullname' => 'string|required',
            'username' => 'string|required|unique:users,username',
            'email' => 'email|required|unique:users,email',
            'role' => 'string|sometimes',
            'password' => 'string|required|confirmed|min:8',
        ]);
        $fileds['password'] = Hash::make($fileds['password']);

        if (!isset($fileds['role'])) {
            $fileds['role'] = 'User';
        }

        if (!Role::where('name', $fileds['role'])->exists()) {
            $fileds['role'] = 'User';
        }

        $newUser = User::create($fileds);
        $newUser->assignRole($fileds['role']);

        return response()->json(["message" => 'User created successfully'], 201);

    }
}
