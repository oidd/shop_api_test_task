<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use App\Service\Auth\AbstractTokenUser;

class OrderPolicy
{
    public function create(AbstractTokenUser $user)
    {
        if ($user instanceof Customer)
            return true;

        return false;
    }

    public function update(AbstractTokenUser $user)
    {
        if ($user instanceof Admin)
            return true;

        return false;
    }

    public function index(AbstractTokenUser $user)
    {
        if ($user instanceof Admin)
            return true;

        return false;
    }

    public function destroy(AbstractTokenUser $user)
    {
        if ($user instanceof Admin)
            return true;

        return false;
    }

    public function show(AbstractTokenUser $user, Order $order)
    {
        if ($user instanceof Admin)
            return true;

        if ($order->customer()->id === $user->id)
            return true;

        return false;
    }
}
