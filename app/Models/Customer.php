<?php

namespace App\Models;

use App\Contracts\HaveBalanceContract;
use App\Contracts\HaveOrdersContract;
use App\Service\Auth\AbstractTokenUser;
use App\Traits\HandlesMoney;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends AbstractTokenUser implements HaveBalanceContract, HaveOrdersContract
{
    use HasFactory;
    use HandlesMoney;

    protected $fillable = [
        'name',
        'email',
        'balance',
        'password'
    ];

    protected $hidden = [
        'password',
        'api_token',
        'token_expires_at',
        'updated_at',
        'created_at',
    ];

    protected $appends = [
        'balance',
    ];

    public function balance(): Attribute
    {
        return $this->handlingMoneyAttributes();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addToBalance(float $amount): float
    {
        $this->validateAmount($amount);

        $sum = $this->getAttributes()['balance'] + $amount * 100;

        $this->balance = $sum / 100;

        $this->saveOrFail();

        return $this->balance;
    }
}
