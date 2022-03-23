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
}
