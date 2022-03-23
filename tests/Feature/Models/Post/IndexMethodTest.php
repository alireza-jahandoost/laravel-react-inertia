<?php

namespace Tests\Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_INDEX = 'posts.index';
    protected const POSTS_SHOW = 'posts.show';

    public function test_index_posts_endpoint_is_available_for_guests()
    {
        $response = $this->get(route(self::POSTS_INDEX));

        $response->assertOk();
    }

    public function test_index_posts_endpoint_is_available_for_authenticated_users()
    {
        $response = $this->actingAs(User::factory()->create())->get(route(self::POSTS_INDEX));

        $response->assertOk();
    }

    public function test_guest_user_can_see_post_titles()
    {
        $owner = User::factory()->create();
        $posts = Post::factory()->for($owner)->count(5)->create();
        $response = $this->get(route(self::POSTS_INDEX));

        $response->assertOk();

        for($i=0;$i<5;$i++){
            $response->assertSee($posts[$i]->title);
        }
    }

    public function test_authenticated_user_can_see_post_titles()
    {
        $owner = User::factory()->create();
        $posts = Post::factory()->for($owner)->count(5)->create();
        $response = $this->actingAs(User::factory()->create())->get(route(self::POSTS_INDEX));

        $response->assertOk();

        for($i=0;$i<5;$i++){
            $response->assertSee($posts[$i]->title);
        }
    }

    public function test_the_order_of_posts_must_be_desc_by_created_at_time()
    {
        $owner = User::factory()->create();
        Post::factory()->for($owner)->count(5)->create();
        $response = $this->get(route(self::POSTS_INDEX));

        $response->assertOk();

        $response->assertSeeInOrder(Post::orderBy('created_at',"desc")->pluck('title')->toArray());
    }
}
