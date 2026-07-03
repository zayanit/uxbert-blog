<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_is_redirected_to_login()
    {
        $this->get('/password/confirm')->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_view_the_confirm_password_form()
    {
        $this->signIn();

        $this->get('/password/confirm')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_confirm_their_password()
    {
        $this->signIn(User::factory()->create([
            'password' => bcrypt('password'),
        ]));

        $response = $this->post('/password/confirm', ['password' => 'password']);

        $response->assertRedirect('/home');
    }

    /** @test */
    public function an_incorrect_password_is_rejected()
    {
        $this->signIn(User::factory()->create([
            'password' => bcrypt('password'),
        ]));

        $response = $this->post('/password/confirm', ['password' => 'wrong-password']);

        $response->assertSessionHasErrors('password');
    }
}
