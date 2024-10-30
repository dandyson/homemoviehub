<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Family;
use App\Models\Video;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_created()
    {
        $user = User::create([
            'auth0' => (string) \Str::uuid(),
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'email_verified' => true,
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);
    }

    /** @test */
    public function a_user_can_have_families()
    {
        $user = User::factory()->create();
        $family = Family::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->families);
        $this->assertTrue($user->families->contains($family));
    }

    /** @test */
    public function a_user_can_have_videos()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->videos);
        $this->assertTrue($user->videos->contains($video));
    }

    /** @test */
    public function a_user_can_have_people()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->people);
        $this->assertTrue($user->people->contains($person));
    }
}
