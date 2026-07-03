<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_a_path()
    {
        $post = Post::factory()->create();

        $this->assertEquals('/posts/'.$post->id, $post->path());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(User::class, $post->owner);
    }

    /** @test */
    public function deleting_a_post_soft_deletes_it()
    {
        $post = Post::factory()->create();

        $post->delete();

        $this->assertSoftDeleted($post);
    }

    /** @test */
    public function a_soft_deleted_post_is_excluded_from_default_queries()
    {
        $post = Post::factory()->create();

        $post->delete();

        $this->assertNull(Post::find($post->id));
        $this->assertFalse(Post::all()->contains($post));
    }

    /** @test */
    public function a_soft_deleted_post_can_be_retrieved_with_trashed()
    {
        $post = Post::factory()->create();

        $post->delete();

        $this->assertTrue(Post::withTrashed()->find($post->id)->is($post));
    }

    /** @test */
    public function a_soft_deleted_post_can_be_restored()
    {
        $post = Post::factory()->create();

        $post->delete();
        $post->restore();

        $this->assertNotSoftDeleted($post);
        $this->assertTrue(Post::find($post->id)->is($post));
    }
}
