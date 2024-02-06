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

    /** @test */
    public function create_method_displays_create_form()
    {
        $user = User::factory()->create();

        $families = Family::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('person.create'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Person/PersonDetails')
            ->has('families', count($families), fn (Assert $page) => $page 
                ->has('id')
                ->has('name')
                ->missing('created_at')
                ->missing('updated_at')
                ->etc()
            )
        ); 
    }

    /** @test */
    public function store_method_creates_new_person_with_existing_family()
    {
        $user = User::factory()->create();
        $family = Family::factory()->create([
            'name' => 'Doe',
            'user_id' => $user->id
        ]);

        $data = [
            'name' => 'John',
            'family' => ['name' => $family->name],
        ];

        $response = $this->actingAs($user)->post(route('person.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => ['type' => 'Success', 'text' => 'Person created successfully']]);
        
        $this->assertDatabaseHas('people', [
            'name' => 'John',
            'user_id' => $user->id,
            'family_id' => $family->id,
        ]);
    }

    /** @test */
    public function store_method_creates_new_person_with_new_family()
    {
        $user = User::factory()->create();
        $family = Family::factory()->create([
            'name' => 'Doe',
            'user_id' => $user->id
        ]);

        $data = [
            'name' => 'John',
            'family' => ['name' => 'Johnson'],
        ];

        $response = $this->actingAs($user)->post(route('person.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => ['type' => 'Success', 'text' => 'Person created successfully']]);

        $newFamily = Family::where('name', 'Johnson')->first();
        
        $this->assertDatabaseHas('people', [
            'name' => 'John',
            'user_id' => Auth::id(),
            'family_id' => $newFamily->id,
        ]);

        $this->assertDatabaseHas('families', [
            'name' => 'Johnson',
            'user_id' => $user->id,
        ]);
    }
}
