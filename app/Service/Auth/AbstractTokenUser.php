<?php

namespace App\Service\Auth;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

abstract class AbstractTokenUser extends Authenticatable
{
    // mutator, so token would be automatically hashed when set
    protected function apiToken(): Attribute
    {
        return Attribute::set(fn ($token) => hash('sha256', $token));
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
