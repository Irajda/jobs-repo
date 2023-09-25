<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Manager\AuthManager;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    private AuthManager $authManager;

    public function __construct(AuthManager $authManager){

        $this->authManager = $authManager;
    }

    /**
     * Login user
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authManager->loginUser($request->validated());
        $code = $result['code'];
        unset($result['code']);
        return response()->json($result, $code);
    }
}
