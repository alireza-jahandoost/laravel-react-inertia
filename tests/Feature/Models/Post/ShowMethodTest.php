<?php

namespace Tests\Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_SHOW = 'posts.show';

    public function test_post_show_endpoint_must_be_available_for_guests()
    {
        $owner = User::factory()->create();

        $post = Post::factory()->for($owner)->create();

        $response = $this->get(route(self::POSTS_SHOW, ['post'=>$post]));

        $response->assertOk();
    }

    public function test_post_show_endpoint_must_be_available_for_authenticated_users()
    {
        $owner = User::factory()->create();

        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs(User::factory()->create())->get(route(self::POSTS_SHOW, ['post'=>$post]));

        $response->assertOk();
    }
}
