<?php

namespace Tests\Unit;

use App\Models\Family;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class FamilyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_family_has_a_user_relationship()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $family = Family::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $family->user);
        $this->assertEquals($user->id, $family->user->id);
    }

    /** @test */
    public function test_family_can_be_filled_with_mass_assignment()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $family = Family::create([
            'name' => 'Smith Family',
            'user_id' => $user->id,
        ]);

        $this->assertEquals('Smith Family', $family->name);
        $this->assertEquals($user->id, $family->user_id);
    }
}
