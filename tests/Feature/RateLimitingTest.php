<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function person_avatar_upload_route_allows_requests_within_rate_limit()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create([
            'user_id' => $user->id,
            'avatar_upload_count' => 0,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Perform requests within the rate limit
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($user)->postJson(route('avatar-upload', ['person' => $person->id]), [
                'avatar' => $file,
            ]);

            $response->assertStatus(200);
        }
    }

    /** @test */
    public function person_avatar_upload_route_blocks_requests_exceeding_rate_limit()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create([
            'user_id' => $user->id,
            'avatar_upload_count' => 0,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Perform requests within the rate limit
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($user)->postJson(route('avatar-upload', ['person' => $person->id]), [
                'avatar' => $file,
            ]);

            $response->assertStatus(200);
        }

        // Try one more request which should be rate limited
        $response = $this->actingAs($user)->postJson(route('avatar-upload', ['person' => $person->id]), [
            'avatar' => $file,
        ]);

        $response->assertStatus(429);
    }

    /** @test */
    public function video_cover_image_upload_route_allows_requests_within_rate_limit()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('cover_image.jpg');

        // Perform requests within the rate limit
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($user)->postJson(route('video.cover-image-upload', ['video' => $video]), [
                'cover_image' => $file,
            ]);

            $response->assertStatus(200);
        }
    }

    /** @test */
    public function video_cover_image_upload_route_blocks_requests_exceeding_rate_limit()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('cover_image.jpg');

        // Perform requests within the rate limit
        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)->postJson(route('video.cover-image-upload', ['video' => $video]), [
                'cover_image' => $file,
            ]);
        }

        // Try one more request which should be rate limited
        $response = $this->actingAs($user)->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        $response->assertStatus(429);
    }
}
