<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Service\Auth\AbstractTokenUser;

class ProductPolicy
{
    public function store(AbstractTokenUser $user)
    {
        return ($user instanceof Admin);
    }

    public function update(AbstractTokenUser $user)
    {
        return ($user instanceof Admin);
    }

    public function delete(AbstractTokenUser $user)
    {
        return ($user instanceof Admin);
    }
}
