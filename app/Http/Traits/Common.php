<?php
namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait Common
{
    public function CreateUser($req)
    {
        $fileds = $req->validate([
            'fullname' => "string|required",
            'username' => "string|required|unique:users,username",
            'email' => "email|required|unique:users,email",
            'password' => "string|required|confirmed|min:8",
        ]);

        $fileds['password'] = Hash::make($fileds['password']);
        $user = User::create($fileds);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);

    }
}
