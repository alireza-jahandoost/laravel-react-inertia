<?php

namespace Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class StoreMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_INDEX = 'posts.index';
    protected const POSTS_CREATE = 'posts.create';
    protected const POSTS_STORE = 'posts.store';
    protected const POSTS_EDIT = 'posts.edit';
    protected const POSTS_SHOW = 'posts.show';

    public function test_user_can_create_post_if_authenticated()
    {
        $title = Str::random(40);
        $content = Str::random(200);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $response->assertRedirect(route(self::POSTS_SHOW, ['post' => 1]));

        $this->assertDatabaseCount(Post::class, 1);
        $this->assertDatabaseHas(Post::class, ['title' => $title,
            'content' => $content]);
    }

    public function test_user_can_not_create_post_if_is_not_authenticated()
    {
        $title = Str::random(40);
        $content = Str::random(200);
        $response = $this->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount(Post::class, 0);
        $this->assertDatabaseMissing(Post::class, ['title' => $title,
            'content' => $content]);
    }

    public function test_user_can_not_create_post_without_title()
    {
        $title = "";
        $content = Str::random(200);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $this->assertDatabaseCount(Post::class, 0);
        $this->assertDatabaseMissing(Post::class, ['title' => $title,
            'content' => $content]);
    }

    public function test_user_can_not_create_post_without_content()
    {
        $title = Str::random(40);
        $content = "";
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $this->assertDatabaseCount(Post::class, 0);
        $this->assertDatabaseMissing(Post::class, ['title' => $title,
            'content' => $content]);
    }

    public function test_user_can_not_create_post_with_title_longer_than_50_characters()
    {
        $title = Str::random(60);
        $content = Str::random(500);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $this->assertDatabaseCount(Post::class, 0);
        $this->assertDatabaseMissing(Post::class, ['title' => $title,
            'content' => $content]);
    }

    public function test_user_can_not_create_post_with_content_longer_than_500_characters()
    {
        $title = Str::random(50);
        $content = Str::random(501);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route(self::POSTS_STORE), [
            'title' => $title,
            'content' => $content,
        ]);

        $this->assertDatabaseCount(Post::class, 0);
        $this->assertDatabaseMissing(Post::class, ['title' => $title,
            'content' => $content]);
    }
}
