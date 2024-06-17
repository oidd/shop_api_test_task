<?php

namespace App\Policies;

use App\Models\Admin;
use App\Service\Auth\AbstractTokenUser;

class CategoryPolicy
{
    public function update(AbstractTokenUser $user)
    {
        return ($user instanceof Admin);
    }

    public function delete(AbstractTokenUser $user)
    {
        return ($user instanceof Admin);
    }
}
