<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\Person;
use App\Models\User;

class LocationPolicy extends BasePolicy
{
    public function deleteFromVideo(User $user, Location $location)
    {
        return $location->videos()->where('user_id', $user->id)->exists();
    }
}
