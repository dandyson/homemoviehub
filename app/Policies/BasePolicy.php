<?php

namespace App\Policies;

class BasePolicy
{
    protected function isOwner($user, $model)
    {
        return $user->id === $model->user_id;
    }
}
