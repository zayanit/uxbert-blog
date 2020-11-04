<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManagePostsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_create_a_post()
    {
        $this->signIn();

        $this->get('/posts/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->sentence
        ];

        $response = $this->post('/posts', $attributes);

        $post = Post::where($attributes)->first();

        $response->assertRedirect($post->path());

        $this->get($post->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['content']);
    }
}
