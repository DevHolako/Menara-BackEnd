<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Traits\Common;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{


    use Common;
    public function register(Request $request)
    {
        $is_Created = User::count();
        if ($is_Created > 1) {
            return response(["message" => "Register method not allowed"], 401);
        };
        $this->CreateUser($request);
    }

    public function login(Request $request)
    {
        $fileds = $request->validate([
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

            $login_time = now();
            $clientIP = $request->ip();

            // Mail::to($user->email)->send(new MailNotfy($user->fullname, $login_time, $clientIP));

            $token = $user->createToken($user->fullname)->plainTextToken;
            return response([
                "user" => new UserResource($user),
                "token" => $token,
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->revokeTokens();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function resetPassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send password reset email to the user
        $response = Password::broker()->sendResetLink(
            $request->only('email')
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


    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'retypeNewPassword' => 'required|same:newPassword',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 401);

        }

        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        return response(['message' => 'Password updated successfully.'], 200);
    }
}
