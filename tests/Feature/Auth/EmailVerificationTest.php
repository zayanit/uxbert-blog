<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_is_redirected_to_login()
    {
        $this->get('/email/verify')->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_view_the_verification_notice()
    {
        $this->signIn(User::factory()->create(['email_verified_at' => null]));

        $this->get('/email/verify')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_verify_their_email_with_a_valid_signed_url()
    {
        $user = $this->signIn(User::factory()->create(['email_verified_at' => null]));

        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $response = $this->get($url);

        $response->assertRedirect('/home');
    }

    /** @test */
    public function a_user_cannot_verify_their_email_with_an_invalid_signature()
    {
        $user = $this->signIn(User::factory()->create(['email_verified_at' => null]));

        $response = $this->get("/email/verify/{$user->id}/".sha1($user->email));

        $response->assertStatus(403);
    }
}
