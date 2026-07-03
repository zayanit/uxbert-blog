<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_owner_can_update_their_post()
    {
        $post = Post::factory()->create();

        $this->assertTrue((new PostPolicy)->update($post->owner, $post));
    }

    /** @test */
    public function a_non_owner_cannot_update_the_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->assertFalse((new PostPolicy)->update($user, $post));
    }
}
