<?php

namespace Tests\Feature\Middleware;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrimStringsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function leading_and_trailing_whitespace_is_trimmed_from_input()
    {
        $this->signIn();

        $this->post('/posts', [
            'title' => '  My Post Title  ',
            'content' => '  Some content.  ',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'My Post Title',
            'content' => 'Some content.',
        ]);
    }

    /** @test */
    public function the_password_field_is_excluded_from_trimming()
    {
        $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => ' secret123',
            'password_confirmation' => ' secret123',
        ]);

        $this->post('/logout');

        $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => ' secret123',
        ])->assertRedirect('/home');
        $this->assertAuthenticated();
    }
}
