<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_view_the_forgot_password_form()
    {
        $this->get('/password/reset')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_request_a_password_reset_link()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/password/email', ['email' => $user->email]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function requesting_a_reset_link_requires_a_known_email()
    {
        Notification::fake();

        $response = $this->post('/password/email', ['email' => 'unknown@example.com']);

        $response->assertSessionHasErrors('email');
        Notification::assertNothingSent();
    }
}
