<?php

namespace App\Service\Auth;

use App\Http\Requests\Auth\registerRequest;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private AbstractTokenUser $tokenUser,
        // receiving an empty instance,
        // depending on what class should it be: a Customer or Admin.
        // maybe should've received a string that contains a FQCN,
        // but in the end why bother if we can access static properties
        // from an object.
    )
    {}

    public function register(array $data): AbstractTokenUser
    {
        return $this->tokenUser::create($data);
    }

    // I am convinced that we are allowed to throw exceptions right from service method.
    // if ever needed, we could try-catch it from controller to send a different response,
    // but in this case the default laravel exception handler doing just fine.

    public function login(array $data): string
    {
        if (!($user = $this->tokenUser::where('email', $data['email']))->exists())
            throw new AuthenticationException('No entry found with this email.');

        $user = $user->first();

        if (!Hash::check($data['password'], $user->password))
            throw new AuthenticationException('Wrong password.');

        return $this->generateToken($user);
    }

    public function dismissToken(AbstractTokenUser $user): bool
    {
        $user->api_token = null;
        $user->token_expires_at = null;

        return $user->saveOrFail();
    }

    private function generateToken(AbstractTokenUser $user): string
    {
        // ensure that generated token is unique
        do {
            $token = Str::random(50) . substr((string) Carbon::now()->timestamp, -5);
        } while ($user::where('api_token', hash('sha256', $token))->exists());

        $user->api_token = $token;
        $user->token_expires_at = Carbon::now()->addWeeks(2)->toDateTime();

        $user->saveOrFail();

        return $token;
    }
}
