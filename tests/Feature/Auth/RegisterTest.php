<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_guest_can_view_the_registration_form()
    {
        $this->get('/register')->assertStatus(200);
    }

    /** @test */
    public function an_authenticated_user_is_redirected_away_from_the_registration_form()
    {
        $this->signIn();

        $this->get('/register')->assertRedirect('/home');
    }

    /** @test */
    public function a_user_can_register_with_valid_details()
    {
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->assertAuthenticatedAs(User::where('email', 'jane@example.com')->first());
    }

    /** @test */
    public function registration_requires_a_name_email_and_password()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function registration_requires_a_unique_email()
    {
        $existing = User::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => $existing->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function registration_requires_the_password_confirmation_to_match()
    {
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'not-matching',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
