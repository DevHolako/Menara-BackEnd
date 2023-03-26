<?php

namespace App\Http\Controllers;

use App\Mail\MailNotfy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function register(Request $req)
    {
        $fileds = $req->validate([
            'first_name' => "string|required",
            'last_name' => "string|required",
            'username' => "string|required|unique:users,username",
            'email' => "email|required|unique:users,email",
            'password' => "string|required|confirmed|min:8",
            'role_id' => 'required|exists:roles,id',
        ]);

        $fileds['password'] = Hash::make($fileds['password']);

        $user = User::create($fileds);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function login(Request $req)
    {
        $fileds = $req->validate([
            "login" => 'required',
            "password" => 'string|required',
        ]);

        // Check Email Or Password
        $user = User::where("email", $fileds['login'])
            ->orWhere('username', $fileds['login'])
            ->first();

        if (!$user || !Hash::check($fileds['password'], $user->password)) {
            return response([
                "message" => "The email/username or password you entered is incorrect. Please try again.",
            ], 401);
        } else {

            $fullname = $user->first_name . " " . $user->last_name;
            $login_time = now();
            $clientIP = $req->ip();

            Mail::to($user->email)->send(new MailNotfy($fullname, $login_time, $clientIP));

            $token = $user->createToken(env("TOKEN_SALT"))->plainTextToken;
            return response([
                "message" => "Wellcome back $fullname",
                "token" => "this is your new token : $token",
            ], 201);
        }

    }

    public function logout(Request $req)
    {

        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 201);

    }
}
