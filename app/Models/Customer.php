<?php

namespace App\Models;

use App\Service\Auth\AbstractTokenUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends AbstractTokenUser
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'api_token',
        'api_token_expire',
        'updated_at',
        'created_at',
    ];
}
