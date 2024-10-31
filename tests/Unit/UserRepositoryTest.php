<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository;
        // Clear cache before each test to ensure fromSession and fromAccessToken methods are executed.
        Cache::flush();
    }

    /**
     * @test
     */
    public function from_access_token_retrieves_user_directly()
    {
        $user = User::factory()->create(['auth0' => 'auth0|123456']);

        $result = $this->userRepository->fromAccessToken(['sub' => 'auth0|123456']);

        $this->assertEquals($user->id, $result->id);
    }

    /**
     * @test
     */
    public function from_access_token_returns_null_when_identifier_is_missing()
    {
        $result = $this->userRepository->fromAccessToken([]);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function from_session_creates_user_directly()
    {
        $userData = [
            'sub' => 'auth0|123456',
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'email_verified' => true,
        ];

        $result = $this->userRepository->fromSession($userData);

        $this->assertDatabaseHas('users', ['auth0' => 'auth0|123456']);
        $this->assertEquals($userData['email'], $result->email);
    }

    /**
     * @test
     */
    public function from_session_updates_existing_user()
    {
        $existingUser = User::factory()->create([
            'auth0' => 'auth0|123456',
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'email_verified' => false,
        ]);

        $userData = [
            'sub' => 'auth0|123456',
            'name' => 'New Name',
            'email' => 'new@example.com',
            'email_verified' => true,
        ];

        $result = $this->userRepository->fromSession($userData);

        $this->assertEquals($existingUser->id, $result->id);
        $this->assertDatabaseHas('users', ['auth0' => 'auth0|123456', 'name' => 'New Name']);
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
        $this->assertDatabaseHas('users', ['email_verified' => true]);
    }

    /**
     * @test
     */
    public function from_session_returns_cached_user()
    {
        $userData = [
            'sub' => 'auth0|123456',
            'name' => 'Cached User',
            'email' => 'cached@example.com',
            'email_verified' => true,
        ];

        // Create and cache the user
        $user = $this->userRepository->fromSession($userData);
        $cachedUser = Cache::get('auth0_user_auth0|123456');

        // Directly call fromSession again
        $result = $this->userRepository->fromSession($userData);

        $this->assertEquals($cachedUser->id, $result->id);
        $this->assertEquals('Cached User', $result->name);
    }

    /**
     * @test
     */
    public function from_session_creates_user_when_none_found()
    {
        $userData = [
            'sub' => 'auth0|654321',
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'email_verified' => false,
        ];

        $result = $this->userRepository->fromSession($userData);

        $this->assertDatabaseHas('users', ['auth0' => 'auth0|654321']);
        $this->assertEquals($userData['email'], $result->email);
    }

    /**
     * @test
     */
    public function cache_behavior_directly()
    {
        $userData = [
            'sub' => 'auth0|123456',
            'name' => 'Austin Lynch',
            'email' => 'mfritsch@example.net',
            'email_verified' => true,
        ];

        // Call the fromSession method to ensure caching behavior is covered
        $this->userRepository->fromSession($userData);

        // Check that the user is cached
        $cachedUser = Cache::get('auth0_user_auth0|123456');
        $this->assertNotNull($cachedUser);
        $this->assertEquals('auth0|123456', $cachedUser->auth0);
    }
}
