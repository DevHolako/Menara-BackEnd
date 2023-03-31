<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MailNotfy;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

        // Mail::to($user->email)->send(new MailNotfy($fullname, $login_time, $clientIP));

        $token = $user->createToken($user->username)->plainTextToken;
            return response([
                "message" => "Wellcome back $fullname",
                "token" => "$token",
            ], 201);
        }

    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 201);
    }

    public function resetPassword(Request $req)
    {
        // Validate the request data
        $req->validate([
            'email' => 'required|email',
        ]);

        // Send password reset email to the user
        $response = Password::broker()->sendResetLink(
            $req->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email.'], 200);
        } else {
            return response()->json(['message' => 'Unable to send password reset link.'], 400);
        }
    }

    public function handleResetPassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Reset the user's password
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Check the status of the password reset attempt
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successful.'], 200);
        } else {
            return response()->json(['message' => 'Unable to reset password.'], 400);
        }
    }
}
