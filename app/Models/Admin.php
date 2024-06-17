<?php

namespace App\Models;

use App\Service\Auth\AbstractTokenUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends AbstractTokenUser
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
        'token_expires_at',
        'updated_at',
        'created_at',
    ];
}
