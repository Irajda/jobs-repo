<?php

namespace App\Manager;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthManager
{
    public function loginUser($request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return [
                'success' => false,
                'message' => 'Email & Password does not match with our record.',
                'code' => 401
            ];
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->active)
            return [
                'success' => false,
                'message' => 'User is blocked!',
                'code' => 403
            ];

        $token = $user->createToken('auth_token');

        $accessToken = $token->plainTextToken;
        //  $expiresAt = $token->token->expires_at;

        return [
            'success' => true,
            'message' => 'User Logged In Successfully',
            'token' => $accessToken,
            // 'expires_at' => $expiresAt,
            'code' => 200,
        ];
    }
}
