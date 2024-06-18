<?php

namespace App\Models;

use App\Service\Auth\AbstractTokenUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends AbstractTokenUser
{
    use Notifiable;
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
