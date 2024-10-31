<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_avatar_to_local_and_not_s3_storage()
    {
        // Mock storage
        Storage::fake('public');
        Storage::fake('s3');

        // Simulate local environment
        config(['filesystems.default' => 'local']);
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $person = Person::factory()->create([
            'user_id' => $user->id,
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('avatar-upload', $person->id), [
            'avatar' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Image uploaded successfully',
            ]);

        $person->refresh();
        $this->assertNotNull($person->avatar);
        $this->assertEquals(1, $person->avatar_upload_count);

        $avatarPath = "avatars/people/{$person->id} - {$person->name}/{$file->getClientOriginalName()}";
        Storage::disk('public')->assertExists($avatarPath);
        Storage::disk('s3')->assertMissing($avatarPath);
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
        $person = Person::factory()->create([
            'user_id' => $user->id,
        ]);

        // Set environment to production (simulation)
        config(['filesystems.default' => 's3']);
        $this->withoutExceptionHandling();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('avatar-upload', $person->id), [
            'avatar' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Image uploaded successfully',
            ]);

        $person->refresh();
        $this->assertNotNull($person->avatar);
        $this->assertEquals(1, $person->avatar_upload_count);

        $avatarPath = "avatars/people/{$person->id} - {$person->name}/{$file->getClientOriginalName()}";
        Storage::disk('s3')->assertExists($avatarPath);
        Storage::disk('public')->assertMissing($avatarPath);
    }

    /** @test */
    public function index_method_returns_correct_view_with_correct_people_data()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $people = Person::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get(route('person.index'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Person/PersonIndex')
            ->has('people', count($people), fn (Assert $page) => $page
                ->where('user_id', $user->id)
                ->etc()
            )
        );
    }

    /** @test */
    public function create_method_displays_details_form()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $families = Family::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get(route('person.create'));

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
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $family = Family::factory()->create([
            'name' => 'Doe',
            'user_id' => $user->id,
        ]);

        $data = [
            'name' => 'John',
            'family' => ['name' => $family->name],
        ];

        $response = $this->actingAs($user, 'auth0-session')->post(route('person.store'), $data);

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
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $family = Family::factory()->create([
            'name' => 'Doe',
            'user_id' => $user->id,
        ]);

        $data = [
            'name' => 'John',
            'family' => ['name' => 'Johnson'],
        ];

        $response = $this->actingAs($user, 'auth0-session')->post(route('person.store'), $data);

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

    /** @test */
    public function edit_method_displays_details_form()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $person = Person::factory()->create([
            'user_id' => $user->id,
        ]);
        $family1 = Family::factory()->create([
            'name' => 'Atkinson',
            'user_id' => $user->id,
        ]);
        $family2 = Family::factory()->create([
            'name' => 'Bentley',
            'user_id' => $user->id,
        ]);
        $family3 = Family::factory()->create([
            'name' => 'Carrington',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get(route('person.edit', ['person' => $person]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Person/PersonDetails')
            ->has('families', 3, fn (Assert $page) => $page
                ->has('id')
                ->has('name')
                ->missing('created_at')
                ->missing('updated_at')
                ->etc()
            )
            ->has('families.0', fn (Assert $page) => $page
                ->where('id', $family1->id)
                ->where('name', $family1->name)
            )
            ->has('families.1', fn (Assert $page) => $page
                ->where('id', $family2->id)
                ->where('name', $family2->name)
            )
            ->has('families.2', fn (Assert $page) => $page
                ->where('id', $family3->id)
                ->where('name', $family3->name)
            )
            ->has('updateMode')
            ->etc()
        );
    }

    /** @test */
    public function update_method_updates_person_and_returns_correct_view()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $person = Person::factory()->create([
            'name' => 'Mark',
            'user_id' => $user->id,
        ]);

        $family = Family::factory()->create([
            'user_id' => $user->id,
        ]);

        $newName = 'John';
        $newFamilyName = 'Smith';

        $response = $this->actingAs($user, 'auth0-session')->put(route('person.update', ['person' => $person]), [
            'name' => $newName,
            'family' => $newFamilyName,
        ])->assertStatus(200);

        $person->refresh();

        $newFamilyModel = Family::where('name', $newFamilyName)->first();

        $this->assertEquals($newName, $person->name);
        $this->assertEquals($newFamilyModel->id, $person->family_id);

        $response->assertInertia(fn ($page) => $page
            ->component('Person/PersonShow')
            ->where('person', function ($responseData) use ($person) {
                return $responseData['id'] === $person->id;
            })
            ->where('videos', [])
            ->where('message', [
                'type' => 'Success',
                'text' => $person->name . ' updated successfully',
            ])
        );
    }

    /** @test */
    public function destroy_method_deletes_person_and_returns_success_response()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $person = Person::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'auth0-session')->delete(route('person.destroy', ['person' => $person]));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('people', ['id' => $person->id]);

        $response->assertJson(['success' => 'Person deleted successfully']);
    }

    /** @test */
    public function test_person_cannot_upload_more_than_avatar_upload_limit()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $person = Person::factory()->create([
            'user_id' => $user->id,
            'avatar_upload_count' => 10,
        ]);

        Storage::fake('s3');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'auth0-session')->postJson(route('avatar-upload', ['person' => $person->id]), [
            'avatar' => $file,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Error: Upload limit reached for this person.',
            ]);

        Storage::disk('s3')->assertMissing("avatars/people/{$person->id} - {$person->name}/{$file->getClientOriginalName()}");
    }
}
