<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_guest_is_redirected_to_login()
    {
        $this->get('/home')->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_view_the_home_page()
    {
        $user = $this->signIn();

        $this->get('/home')->assertStatus(200);
    }

    /** @test */
    public function the_home_page_only_shows_the_authenticated_users_posts()
    {
        $user = $this->signIn();

        $ownPost = Post::factory()->create(['owner_id' => $user->id]);
        $othersPost = Post::factory()->create();

        $response = $this->get('/home');

        $response->assertSee($ownPost->title);
        $response->assertDontSee($othersPost->title);
    }
}
