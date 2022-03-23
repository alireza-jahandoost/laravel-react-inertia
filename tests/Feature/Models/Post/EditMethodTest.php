<?php

namespace Tests\Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditMethodTest extends TestCase
{
     use RefreshDatabase;

     protected const POSTS_EDIT = "posts.edit";

    public function test_edit_page_should_be_available_for_user_for_owned_posts()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();
        $response = $this->actingAs($owner)->get(route(self::POSTS_EDIT,['post'=>$post]));

        $response->assertOk();
    }

    public function test_edit_page_should_not_be_available_for_guests()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();
        $response = $this->get(route(self::POSTS_EDIT,['post'=>$post]));

        $response->assertRedirect(route('login'));
    }

    public function test_edit_page_should_not_be_available_for_not_owner_user()
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();
        $response = $this->actingAs(User::factory()->create())->get(route(self::POSTS_EDIT,['post'=>$post]));

        $response->assertForbidden();
    }
}
