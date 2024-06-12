<?php

namespace App\Service\Auth;

use App\Traits\UsesTokenAuth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

abstract class AbstractTokenUser extends Authenticatable
{
    public function generateToken()
    {
        // ensure that generated token is unique
        do {
            $this->api_token = Str::random(50) . substr((string) Carbon::now()->timestamp, -1, -5);
        } while (self::where('api_token', Hash::make($this->api_token))->exists());

        $this->token_expires_at = Carbon::now()->addWeeks(2)->toDateTime();

        $this->save();

        return $this->api_token;
    }

    public function dismissToken()
    {
        $this->api_token = null;
        $this->token_expires_at = null;

        return $this->save();
    }

    // mutator, so token would be automatically hashed when set
    protected function apiToken(): Attribute
    {
        return Attribute::set(fn ($token) => Hash::make($token));
    }

    // same as password
    protected function password(): Attribute
    {
        return Attribute::set(fn ($password) => Hash::make($password));
    }

    // automatically cast string to Carbon object
    protected function tokenExpiresAt(): Attribute
    {
        return Attribute::get(fn ($value) => Carbon::parse($value));
    }
}
