<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HandlesMoney
{
    private function handlingMoneyAttributes() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    private function validateAmount(float $amount): bool
    {
        if (strlen(explode('.', (string) $amount)[1] ?? '') > 2)
            throw new \Exception('Passed float has to have two or less decimal places.', 422);

        return true;
    }
}
