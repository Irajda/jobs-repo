<?php

namespace App\Manager;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class AuthManager
{
    public function loginUser(array $requestData)
    {
        if (!Auth::attempt(['email' => $requestData['email'], 'password' => $requestData['password']])) {
            return [
                'success' => false,
                'message' => 'Email or Password does not match with our record.',
                'code' => 401
            ];
        }

        /** @var User $user */
        $user = auth()->user();

        if (!$user->active) {
            return [
                'success' => false,
                'message' => 'User is blocked!',
                'code' => 403
            ];
        }

        $token = $user->createToken('auth_token');

        return [
            'success' => true,
            'message' => 'User Logged In Successfully',
            'token' => $token->plainTextToken,
            'code' => 200,
        ];
    }
}
