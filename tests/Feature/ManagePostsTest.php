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

    /** @test */
    public function a_post_requires_a_title()
    {
        $this->signIn();

        $attributes = Post::factory()->raw(['title' => '']);

        $this->post('/posts', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_user_can_update_a_post()
    {
        $post = Post::factory()->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->sentence
        ];

        $this->actingAs($post->owner)
            ->patch($post->path(), $attributes)
            ->assertRedirect($post->path());

        $this->assertDatabaseHas('posts', $attributes);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_posts_of_other()
    {
        $this->signIn();

        $post = Post::factory()->create();

        $this->patch($post->path(), [
            'title' => $this->faker->sentence
        ])->assertStatus(403);
    }
}
