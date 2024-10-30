<?php

namespace Tests\Unit;

use App\Models\Family;
use App\Models\Person;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_person_can_be_created()
    {
        $user = User::factory()->create();
        $family = Family::factory()->create();

        $person = Person::create([
            'name' => 'John Doe',
            'user_id' => $user->id,
            'family_id' => $family->id,
            'avatar' => null,
        ]);

        $this->assertDatabaseHas('people', [
            'name' => 'John Doe',
            'user_id' => $user->id,
            'family_id' => $family->id,
        ]);
    }

    /** @test */
    public function a_person_belongs_to_a_family()
    {
        $family = Family::factory()->create();
        $person = Person::factory()->create(['family_id' => $family->id]);

        $this->assertInstanceOf(Family::class, $person->family);
        $this->assertEquals($family->id, $person->family->id);
    }

    /** @test */
    public function a_person_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $person->user);
        $this->assertEquals($user->id, $person->user->id);
    }

    /** @test */
    public function a_person_can_have_videos()
    {
        $person = Person::factory()->create();
        $video = Video::factory()->create();
        $person->videos()->attach($video);

        $this->assertCount(1, $person->videos);
        $this->assertTrue($person->videos->contains($video));
    }
}
