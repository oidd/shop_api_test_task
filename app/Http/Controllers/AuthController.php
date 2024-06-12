<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\loginRequest;
use App\Http\Requests\Auth\registerRequest;
use App\Service\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    )
    {}

    public function register(registerRequest $request)
    {
        return response()->json(
            $this->service->register($request->validated())
        );
    }

    public function login(loginRequest $request)
    {
        return response()->json([
            'message' => $this->service->login($request->validated())
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json([
            'message' => $this->service->dismissToken($request->user())
        ]);
    }
}
