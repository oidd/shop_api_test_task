<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use App\Service\Auth\AbstractTokenUser;

class OrderPolicy
{
    public function store(AbstractTokenUser $user)
    {
        if ($user instanceof Customer) // Only customer can make new order.
            return true;

        return false;
    }

    public function show(AbstractTokenUser $user, Order $order)
    {
        if ($user instanceof Admin)
            return true;

        if ($order->customer->id === $user->id) // Customer can see its own order
            return true;

        return false;
    }
}
