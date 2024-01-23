<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class PersonPolicy extends BasePolicy
{
    public function show(User $user, Person $person)
    {
        return $this->isOwner($user, $person);
    }

    public function edit(User $user, Person $person)
    {
        return $this->isOwner($user, $person);
    }

    public function update(User $user, Person $person)
    {
        return $this->isOwner($user, $person);
    }

    public function destroy(User $user, Person $person)
    {
        return $this->isOwner($user, $person);
    }
}
