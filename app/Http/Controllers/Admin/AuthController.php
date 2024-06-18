<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Service\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $service
    )
    {}

    public function login(LoginRequest $request)
    {
        return response()->json([
            'token' => $this->service->login($request->validated())
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json(
            new AdminResource($this->service->dismissToken($request->user()))
        );
    }
}
