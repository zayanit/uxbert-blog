<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_requesting_a_protected_page_is_redirected_to_login()
    {
        $this->get('/home')->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_making_a_json_request_to_a_protected_route_gets_a_401_instead_of_a_redirect()
    {
        $response = $this->getJson('/home');

        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_access_a_protected_page()
    {
        $this->signIn();

        $this->get('/home')->assertStatus(200);
    }
}
