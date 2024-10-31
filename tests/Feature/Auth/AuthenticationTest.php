<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_home_page_redirects_if_not_authenticated()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertLocation('/');
    }

    /**
     * @test
     */
    public function dashboard_redirects_if_not_authenticated()
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        // auth-session guard needed to authenticate the user properly
        $response = $this->actingAs($user, 'auth0-session')->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    /**
     * @test
     */
    public function dashboard_shows_user_videos()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $videos = Video::factory()->count(3)->create(['user_id' => $user->id]);

        // Act as the user
        $response = $this->actingAs($user, 'auth0-session')->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($videos->first()->title);
    }

    /**
     * @test
     */
    public function protected_video_routes_are_accessible_by_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);
        $response = $this->actingAs($user, 'auth0-session')->get('/video/create');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function protected_person_routes_are_accessible_by_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid(),
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get('/person/create');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function unauthenticated_user_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
