<?php

namespace App\Contracts;

interface HaveBalanceContract
{
    public function balance();
    public function addToBalance(float $amount);
}
