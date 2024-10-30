<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Video;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected VideoPolicy $videoPolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->videoPolicy = new VideoPolicy();
    }

    public function test_user_can_show_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canShow = $this->videoPolicy->show($user, $video);

        $this->assertTrue($canShow);
    }

    public function test_user_cannot_show_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canShow = $this->videoPolicy->show($user, $video);

        $this->assertFalse($canShow);
    }

    public function test_user_can_edit_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canEdit = $this->videoPolicy->edit($user, $video);

        $this->assertTrue($canEdit);
    }

    public function test_user_cannot_edit_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canEdit = $this->videoPolicy->edit($user, $video);

        $this->assertFalse($canEdit);
    }

    public function test_user_can_update_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canUpdate = $this->videoPolicy->update($user, $video);

        $this->assertTrue($canUpdate);
    }

    public function test_user_cannot_update_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canUpdate = $this->videoPolicy->update($user, $video);

        $this->assertFalse($canUpdate);
    }

    public function test_user_can_destroy_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canDestroy = $this->videoPolicy->destroy($user, $video);

        $this->assertTrue($canDestroy);
    }

    public function test_user_cannot_destroy_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canDestroy = $this->videoPolicy->destroy($user, $video);

        $this->assertFalse($canDestroy);
    }
}
