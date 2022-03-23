<?php

namespace Tests\Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_DESTROY = 'posts.destroy';
    protected const POSTS_INDEX = 'posts.index';

    public function test_user_can_delete_his_post()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($owner)->delete(route(self::POSTS_DESTROY,['post'=>$post]));

        $response->assertRedirect(route(self::POSTS_INDEX));

        $this->assertDatabaseCount(Post::class,0);
    }

    public function test_user_can_not_delete_another_users_post()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs(User::factory()->create())->delete(route(self::POSTS_DESTROY,['post'=>$post]));

        $response->assertForbidden();

        $this->assertDatabaseCount(Post::class,1);
    }

    public function test_guest_user_can_not_delete_any_post()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->delete(route(self::POSTS_DESTROY,['post'=>$post]));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount(Post::class,1);
    }
}
