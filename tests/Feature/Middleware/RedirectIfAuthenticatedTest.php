<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_is_redirected_away_from_guest_only_routes()
    {
        $this->signIn();

        $this->get('/login')->assertRedirect('/home');
        $this->get('/register')->assertRedirect('/home');
    }

    /** @test */
    public function a_guest_can_access_guest_only_routes()
    {
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }
}
