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

    /** @test */
    public function a_guest_can_view_all_posts()
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get('/posts')->assertStatus(200);

        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    /** @test */
    public function the_posts_list_is_paginated()
    {
        Post::factory()->count(21)->create();

        $response = $this->get('/posts');

        $response->assertViewHas('posts', function ($posts) {
            return $posts->total() === 21 && $posts->count() === 20;
        });
    }

    /** @test */
    public function the_posts_list_truncates_long_content()
    {
        $post = Post::factory()->create([
            'content' => str_repeat('a', 200),
        ]);

        $response = $this->get('/posts');

        $response->assertSee(str_repeat('a', 150) . '...', false);
        $response->assertDontSee(str_repeat('a', 200));
    }

    /** @test */
    public function a_guest_can_view_a_single_post()
    {
        $post = Post::factory()->create();

        $this->get($post->path())
            ->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee($post->content);
    }

    /** @test */
    public function the_owner_sees_an_update_link_when_viewing_their_post()
    {
        $post = Post::factory()->create();

        $this->actingAs($post->owner)
            ->get($post->path())
            ->assertSee('Update Post');
    }

    /** @test */
    public function a_non_owner_does_not_see_an_update_link_when_viewing_a_post()
    {
        $this->signIn();

        $post = Post::factory()->create();

        $this->get($post->path())
            ->assertDontSee('Update Post');
    }
}
