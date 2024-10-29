<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Video;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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

    public function test_dashboard_redirects_if_not_authenticated()
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302)
                ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid()
        ]);

        // auth-session guard needed to authenticate the user properly
        $response = $this->actingAs($user, 'auth0-session')->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }
    public function test_dashboard_shows_user_videos()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid()
        ]);
        $videos = Video::factory()->count(3)->create(['user_id' => $user->id]);

        // Act as the user
        $response = $this->actingAs($user, 'auth0-session')->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($videos->first()->title);
    }

    public function test_protected_video_routes_are_accessible_by_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid()
        ]);
        $response = $this->actingAs($user, 'auth0-session')->get('/video/create');

        $response->assertStatus(200);
    }

    public function test_protected_person_routes_are_accessible_by_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified' => true,
            'auth0' => (string) Str::uuid()
        ]);

        $response = $this->actingAs($user, 'auth0-session')->get('/person/create');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
