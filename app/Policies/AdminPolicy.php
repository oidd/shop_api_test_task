<?php

namespace App\Policies;

use App\Models\Admin;
use App\Service\Auth\AbstractTokenUser;

class AdminPolicy
{
    public function update(Admin $user, Admin $updatingAdmin): bool
    {
        if ($user->id === $updatingAdmin->id)
            return true;

        return false;
    }
}
