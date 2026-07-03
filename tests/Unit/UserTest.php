<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(Collection::class, $user->posts);
        $this->assertTrue($user->posts->contains($post));
    }

    /** @test */
    public function it_only_returns_its_own_posts()
    {
        $user = User::factory()->create();
        $ownPost = Post::factory()->create(['owner_id' => $user->id]);
        $othersPost = Post::factory()->create();

        $this->assertTrue($user->posts->contains($ownPost));
        $this->assertFalse($user->posts->contains($othersPost));
    }
}
