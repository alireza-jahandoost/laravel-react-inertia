<?php

namespace Tests\Feature\Models\Post;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_CREATE = 'posts.create';

    public function test_create_post_page_should_be_available_for_authenticated_users()
    {
        $response = $this->actingAs(User::factory()->create())->get(route(self::POSTS_CREATE));

        $response->assertOk();
    }

    public function test_create_post_page_should_not_be_available_for_guest_users()
    {
        $response = $this->get(route(self::POSTS_CREATE));

        $response->assertRedirect(route('login'));
    }
}
