<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Service\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    )
    {}

    public function register(RegisterRequest $request)
    {
        return response()->json([
            'user' => $this->service->register($request->validated())
        ]);
    }

    public function login(LoginRequest $request)
    {
        return response()->json([
            'token' => $this->service->login($request->validated())
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json([
            'user' => $this->service->dismissToken($request->user())
        ]);
    }
}
