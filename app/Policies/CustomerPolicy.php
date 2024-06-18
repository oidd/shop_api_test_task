<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Service\Auth\AbstractTokenUser;

class CustomerPolicy
{
    public function show(AbstractTokenUser $user, Customer $customer)
    {
        if ($user instanceof Admin)
            return true;

        if ($user->id === $customer->id)
            return true;

        return false;
    }

    public function update(AbstractTokenUser $user, Customer $customer)
    {
        if ($user instanceof Admin)
            return true;

        if ($user->id === $customer->id)
            return true;

        return false;
    }
}
