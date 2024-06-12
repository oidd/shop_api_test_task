<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\loginRequest;
use App\Http\Requests\Auth\registerRequest;
use App\Models\Customer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(registerRequest $request)
    {
        return response()->json(
            Customer::create($request->validated())
        );
    }

    public function login(loginRequest $request)
    {
        if (!($customer = Customer::where('email', $request->email))->exists())
            throw new AuthenticationException('No entry found with this email.');

        $customer = $customer->first();

        if (!Hash::check($request->password, $customer->password))
            throw new AuthenticationException('Wrong password.');

        return response()->json([
            'token' => $customer->generateToken()
        ]);
    }

    public function logout(Request $request)
    {
        return response()->json($request->user()->dismissToken());
    }
}
