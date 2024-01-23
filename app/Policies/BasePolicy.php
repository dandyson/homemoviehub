<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
    protected function isOwner($user, $model)
    {
        return $user->id === $model->user_id;
    }
}
