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
}
