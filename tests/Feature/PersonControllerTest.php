<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
class PersonControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function handle_avatar_upload_method()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $person = Person::factory()->create([
            'user_id' => $user->id,
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->postJson(route('avatar-upload', $person->id), ['avatar' => $file]);

        $response->assertStatus(200);
        $this->assertNotNull($person->refresh()->avatar);
    }

    /** @test */
    public function index_method_returns_correct_view_with_correct_people_data()
    {
        $user = User::factory()->create();

        $people = Person::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('person.index'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Person/PersonIndex')
            ->has('people', count($people), fn (Assert $page) => $page 
                ->where('user_id', $user->id)
                ->etc()
            )
        ); 
    }

    // Add similar tests for other methods (create, store, show, edit, update, destroy)
}
