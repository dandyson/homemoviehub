<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Person;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_method_displays_video_details()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);
        $people = Person::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);
        $locations = Location::factory()->count(2)->create();

        foreach ($locations as $location) {
            $video->locations()->attach($location->id);
        }
        foreach ($people as $person) {
            $video->people()->attach($person->id);
        }

        $response = $this->actingAs($user, 'auth0-session')->get(route('video.show', ['video' => $video->id]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Video/VideoShow')
            ->where('video.id', $video->id)
            ->has('video.locations')
        );
    }

    /** @test */
    public function create_method_displays_details_form()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $people = Person::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get(route('video.create'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Video/VideoDetails')
            ->has('people', count($people), fn (Assert $page) => $page
                ->has('user_id')
                ->where('user_id', $user->id)
                ->etc()
            )
        );
    }

    /** @test */
    public function edit_method_displays_details_form()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);
        $people = Person::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        foreach ($people as $person) {
            $video->people()->attach($person->id);
        }

        $response = $this->actingAs($user, 'auth0-session')->get(route('video.edit', ['video' => $video]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Video/VideoDetails')
            ->has('people', count($people), fn (Assert $page) => $page
                ->has('user_id')
                ->where('user_id', $user->id)
                ->etc()
            )
            ->has('updateMode')
        );
    }

    /** @test */
    public function store_method_requires_valid_data()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $this->actingAs($user, 'auth0-session');

        // Send invalid data for both title and youtube_url
        $response = $this->post(route('video.store'), [
            'title' => '', // Title is required
            'description' => 'A video without title',
            'youtube_url' => 'invalid', // Invalid YouTube URL
        ]);

        // Assert that there are errors for both fields
        $response->assertSessionHasErrors(['title', 'youtube_url']);
    }

    /** @test */
    public function store_method_creates_new_video_with_people_and_new_locations()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $people = Person::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);
        $locations = Location::factory()->count(2)->create();
        $data = [
            'title' => 'Christmas 1990',
            'description' => 'Christmas with the family',
            'youtube_url' => 'g-LHZL0pnLw',
            'featured_people' => $people->toArray(),
            'locations' => $locations->toArray(),
            'cover_image' => 'default_cover_image',
        ];

        $response = $this->actingAs($user, 'auth0-session')->post(route('video.store'), $data);

        $video = Video::first();

        $response->assertStatus(200)
            ->assertExactJson([
                'video' => [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'youtube_url' => $video->youtube_url,
                ],
                'message' => [
                    'type' => 'Success',
                    'text' => 'Video created successfully',
                ],
            ]);

        $this->assertDatabaseHas('videos', [
            'title' => 'Christmas 1990',
            'description' => 'Christmas with the family',
            'youtube_url' => 'g-LHZL0pnLw',
            'user_id' => $user->id,
        ]);

        $video = Video::first();

        // Assert that the video was created with the default cover image
        $this->assertEquals(
            $video->cover_image,
            config('app.default_cover_image')
        );

        foreach ($people as $person) {
            $this->assertTrue($video->people->contains($person));
        }

        foreach ($locations as $location) {
            $this->assertDatabaseHas('location_video', [
                'location_id' => $location->id,
                'video_id' => $video->id,
            ]);
        }
    }

    /** @test */
    public function update_method_updates_video_and_returns_correct_view()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'title' => 'Christmas 1990',
        ]);
        $people = Person::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);
        $locations = Location::factory()->count(2)->create();
        $newPeopleIds = Person::factory()->count(2)->create([
            'user_id' => $user->id,
        ])->only(['id']);

        foreach ($locations as $location) {
            $video->locations()->attach($location->id);
        }
        foreach ($people as $person) {
            $video->people()->attach($person->id);
        }

        $newVideoTitle = 'Christmas 2000';
        $newVideoDescription = 'This is a new video description';

        $response = $this->actingAs($user, 'auth0-session')->put(route('video.update', ['video' => $video]), [
            'title' => $newVideoTitle,
            'description' => $newVideoDescription,
            'youtube_url' => '1z1tv5dB5uA',
            'featured_people' => $newPeopleIds->toArray(),
        ])->assertStatus(200);

        $video->refresh();

        $this->assertEquals($newVideoTitle, $video->title);
        $this->assertEquals($newVideoDescription, $video->description);

        $response->assertInertia(fn ($page) => $page
            ->component('Video/VideoShow')
            ->where('video.id', $video->id)
            ->where('message', [
                'type' => 'Success',
                'text' => 'Video updated successfully',
            ])
        );
    }

    /** @test */
    public function show_method_requires_authentication_and_proper_ownership()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create(); // Not owned by $user

        $response = $this->actingAs($user, 'auth0-session')->get(route('video.show', ['video' => $video->id]));
        $response->assertStatus(403);
    }

    /** @test */
    public function store_method_attaches_existing_and_new_locations_to_video()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $existingLocation = Location::factory()->create(['lat' => 40.7128, 'lng' => -74.0060]);
        $newLocationData = [
            'location' => 'New Spot',
            'lat' => 35.6895,
            'lng' => 139.6917,
        ];

        $data = [
            'title' => 'Location Test',
            'description' => 'Video with multiple locations',
            'youtube_url' => 'g-LHZL0pnLw',
            'locations' => [
                $existingLocation->toArray(),
                $newLocationData,
            ],
        ];

        $response = $this->actingAs($user, 'auth0-session')->post(route('video.store'), $data);
        $response->assertStatus(200);

        $video = Video::first();
        $this->assertTrue($video->locations->contains($existingLocation));

        // Check that the new location is created and attached
        $this->assertDatabaseHas('locations', $newLocationData);
        $this->assertTrue($video->locations->contains('location', $newLocationData['location']));
    }

    /** @test */
    public function unauthorized_user_cannot_update_or_delete_video()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create(); // Video not owned by $user

        $this->actingAs($user, 'auth0-session')
            ->put(route('video.update', $video))
            ->assertStatus(403);

        $this->actingAs($user, 'auth0-session')
            ->delete(route('video.destroy', $video))
            ->assertStatus(403);
    }

    /** @test */
    public function handle_cover_image_upload_accepts_only_valid_files()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('cover_image.gif');

        $this->actingAs($user, 'auth0-session')->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        // Try one more request which should be rate limited
        $response = $this->actingAs($user, 'auth0-session')->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['cover_image']);
    }

    /** @test
     * This test ensures that if a video has existing locations attached and the user submits
     * new locations but keeps the old ones in, the old ones still remain
     */
    public function update_method_keeps_existing_locations_if_new_ones_added()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'title' => 'Christmas 1990',
        ]);
        $oldLocation1 = Location::factory()->create([
            'location' => 'London',
        ]);
        $oldLocation2 = Location::factory()->create([
            'location' => 'New York',
        ]);

        $video->locations()->attach([$oldLocation1->id, $oldLocation2->id]);

        $newLocation1 = Location::factory()->create([
            'location' => 'Dubai',
        ]);
        $newLocation2 = Location::factory()->create([
            'location' => 'Los Angeles',
        ]);

        $locationData = [
            $oldLocation1->toArray(),
            $oldLocation2->toArray(),
            $newLocation1->toArray(),
            $newLocation2->toArray(),
        ];

        $response = $this->actingAs($user, 'auth0-session')->put(route('video.update', ['video' => $video]), [
            'title' => $video->title,
            'description' => $video->description,
            'youtube_url' => $video->youtube_url,
            'locations' => $locationData,
        ])->assertStatus(200);

        $video->refresh();

        $response->assertInertia(fn ($page) => $page
            ->component('Video/VideoShow')
            ->where('video.id', $video->id)
            ->has('video.locations', 4)
            ->where('video.locations.0.id', $oldLocation1->id)
            ->where('video.locations.1.id', $oldLocation2->id)
            ->where('video.locations.2.id', $newLocation1->id)
            ->where('video.locations.3.id', $newLocation2->id)
        );
    }

    /** @test
     * This test ensures that if the user removes existing locations in the UI
     * and submits new ones, only the new locations are saved and the old ones are
     * no longer attached
     */
    public function update_method_removes_existing_locations_if_only_new_ones_added()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'title' => 'Christmas 1990',
        ]);
        $existingLocations = Location::factory()->count(10)->create();
        $newLocation1 = Location::factory()->create([
            'location' => 'London',
        ]);
        $newLocation2 = Location::factory()->create([
            'location' => 'New York',
        ]);
        $newLocationData = [
            $newLocation1->toArray(),
            $newLocation2->toArray(),
        ];

        foreach ($existingLocations as $location) {
            $video->locations()->attach($location->id);
        }

        $response = $this->actingAs($user, 'auth0-session')->put(route('video.update', ['video' => $video]), [
            'title' => $video->title,
            'description' => $video->description,
            'youtube_url' => $video->youtube_url,
            'locations' => $newLocationData,
        ])->assertStatus(200);

        $video->refresh();

        $response->assertInertia(fn ($page) => $page
            ->component('Video/VideoShow')
            ->where('video.id', $video->id)
            ->has('video.locations', 2)
            ->where('video.locations.0.id', $newLocation1->id)
            ->where('message', [
                'type' => 'Success',
                'text' => 'Video updated successfully',
            ])
        );
    }

    /** @test */
    public function destroy_method_deletes_video_and_returns_success_response()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->delete(route('video.destroy', ['video' => $video]));
        $response->assertStatus(200);

        // Check that the video is soft deleted
        $this->assertSoftDeleted('videos', ['id' => $video->id]);

        // Check that the video is not present in the active records
        $this->assertDatabaseMissing('videos', ['id' => $video->id, 'deleted_at' => null]);

        $response->assertJson(['success' => 'Video deleted successfully']);
    }

    /** @test */
    public function user_can_upload_avatar_to_local_and_not_s3_storage()
    {
        // Mock storage
        Storage::fake('public');
        Storage::fake('s3');

        // Simulate local environment
        config(['filesystems.default' => 'local']);

        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        $file = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Image uploaded successfully',
            ]);

        $video->refresh();

        $this->assertNotNull($video->cover_image);
        $this->assertEquals(1, $video->cover_image_upload_count);

        $coverImagePath = "cover-images/{$video->id} - {$video->title}/{$file->getClientOriginalName()}";
        Storage::disk('public')->assertExists($coverImagePath);
        Storage::disk('s3')->assertMissing($coverImagePath);
    }

    /**
     * @test
     */
    public function user_can_upload_avatar_to_s3_and_not_local_storage()
    {
        Storage::fake('s3');
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        // Set environment to production (simulation)
        config(['filesystems.default' => 's3']);
        $this->withoutExceptionHandling();

        $file = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Image uploaded successfully',
            ]);

        $video->refresh();
        $this->assertNotNull($video->cover_image);
        $this->assertEquals(1, $video->cover_image_upload_count);

        $coverImagePath = "cover-images/{$video->id} - {$video->title}/{$file->getClientOriginalName()}";
        Storage::disk('s3')->assertExists($coverImagePath);
        Storage::disk('public')->assertMissing($coverImagePath);
    }

    /** @test */
    public function test_user_cannot_upload_more_than_cover_image_upload_limit()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'cover_image_upload_count' => 10,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('cover_image.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('video.cover-image-upload', ['video' => $video]), [
            'cover_image' => $file,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Error: Upload limit reached for this video.',
            ]);
    }
}
