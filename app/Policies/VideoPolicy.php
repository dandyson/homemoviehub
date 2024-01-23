<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy extends BasePolicy
{
    public function show(User $user, Video $video)
    {
        return $this->isOwner($user, $video);
    }

    public function edit(User $user, Video $video)
    {
        return $this->isOwner($user, $video);
    }

    public function update(User $user, Video $video)
    {
        return $this->isOwner($user, $video);
    }

    public function destroy(User $user, Video $video)
    {
        return $this->isOwner($user, $video);
    }
}
