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
        $this->videoPolicy = new VideoPolicy;
    }

    /**
     * @test
     */
    public function user_can_show_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canShow = $this->videoPolicy->show($user, $video);

        $this->assertTrue($canShow);
    }

    /**
     * @test
     */
    public function user_cannot_show_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canShow = $this->videoPolicy->show($user, $video);

        $this->assertFalse($canShow);
    }

    /**
     * @test
     */
    public function user_can_edit_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canEdit = $this->videoPolicy->edit($user, $video);

        $this->assertTrue($canEdit);
    }

    /**
     * @test
     */
    public function user_cannot_edit_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canEdit = $this->videoPolicy->edit($user, $video);

        $this->assertFalse($canEdit);
    }

    /**
     * @test
     */
    public function user_can_update_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canUpdate = $this->videoPolicy->update($user, $video);

        $this->assertTrue($canUpdate);
    }

    /**
     * @test
     */
    public function user_cannot_update_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canUpdate = $this->videoPolicy->update($user, $video);

        $this->assertFalse($canUpdate);
    }

    /**
     * @test
     */
    public function user_can_destroy_own_video()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $canDestroy = $this->videoPolicy->destroy($user, $video);

        $this->assertTrue($canDestroy);
    }

    /**
     * @test
     */
    public function user_cannot_destroy_another_users_video()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $anotherUser->id]);

        $canDestroy = $this->videoPolicy->destroy($user, $video);

        $this->assertFalse($canDestroy);
    }
}
