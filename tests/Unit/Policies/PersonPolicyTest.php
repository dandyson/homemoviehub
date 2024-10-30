<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\User;
use App\Policies\PersonPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected PersonPolicy $personPolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->personPolicy = new PersonPolicy();
    }

    public function test_user_can_show_own_person()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $canShow = $this->personPolicy->show($user, $person);

        $this->assertTrue($canShow);
    }

    public function test_user_cannot_show_another_users_person()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $anotherUser->id]);

        $canShow = $this->personPolicy->show($user, $person);

        $this->assertFalse($canShow);
    }

    public function test_user_can_edit_own_person()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $canEdit = $this->personPolicy->edit($user, $person);

        $this->assertTrue($canEdit);
    }

    public function test_user_cannot_edit_another_users_person()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $anotherUser->id]);

        $canEdit = $this->personPolicy->edit($user, $person);

        $this->assertFalse($canEdit);
    }

    public function test_user_can_update_own_person()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $canUpdate = $this->personPolicy->update($user, $person);

        $this->assertTrue($canUpdate);
    }

    public function test_user_cannot_update_another_users_person()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $anotherUser->id]);

        $canUpdate = $this->personPolicy->update($user, $person);

        $this->assertFalse($canUpdate);
    }

    public function test_user_can_destroy_own_person()
    {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);

        $canDestroy = $this->personPolicy->destroy($user, $person);

        $this->assertTrue($canDestroy);
    }

    public function test_user_cannot_destroy_another_users_person()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $anotherUser->id]);

        $canDestroy = $this->personPolicy->destroy($user, $person);

        $this->assertFalse($canDestroy);
    }
}
