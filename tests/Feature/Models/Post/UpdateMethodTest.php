<?php

namespace Feature\Models\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class UpdateMethodTest extends TestCase
{
    use RefreshDatabase;

    protected const POSTS_EDIT = 'posts.edit';
    protected const POSTS_UPDATE = 'posts.update';
    protected const POSTS_SHOW = 'posts.show';

    public function test_user_can_update_content_and_title_of_their_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newTitle = Str::random(40);
        $newContent = Str::random(400);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'title'=>$newTitle,
            'content'=>$newContent,
        ]);

        $request->assertRedirect(route(self::POSTS_SHOW,['post'=>$posts[2]]));

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseHas(Post::class, ['title'=>$newTitle,'content'=>$newContent]);
    }

    public function test_user_can_update_just_title_of_their_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newTitle = Str::random(40);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'title'=>$newTitle,
        ]);

        $request->assertRedirect(route(self::POSTS_SHOW,['post'=>$posts[2]]));

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseHas(Post::class, ['title'=>$newTitle]);
    }

    public function test_user_can_update_just_content_of_their_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newContent = Str::random(400);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'content'=>$newContent,
        ]);

        $request->assertRedirect(route(self::POSTS_SHOW,['post'=>$posts[2]]));

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseHas(Post::class, ['content'=>$newContent]);
    }

    public function test_guest_user_can_not_update_any_post()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newTitle = Str::random(40);
        $newContent = Str::random(400);

        $request = $this->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'title'=>$newTitle,
            'content'=>$newContent,
        ]);

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseMissing(Post::class, ['title'=>$newTitle,'content'=>$newContent]);
    }

    public function test_user_can_not_update_title_with_length_more_than_50()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newTitle = Str::random(60);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'title'=>$newTitle,
        ]);

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseMissing(Post::class, ['title'=>$newTitle]);
    }

    public function test_user_can_not_update_posts_with_content_longer_than_500()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->for($user)->count(5)->create();

        $newContent = Str::random(600);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'content'=>$newContent,
        ]);

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseMissing(Post::class, ['content'=>$newContent]);
    }

    public function test_user_can_not_update_another_users_post()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $posts = Post::factory()->for($owner)->count(5)->create();

        $newTitle = Str::random(40);
        $newContent = Str::random(400);

        $request = $this->actingAs($user)->put(route(self::POSTS_UPDATE, ['post' => $posts[2]]), [
            'title'=>$newTitle,
            'content'=>$newContent,
        ]);

        $request->assertForbidden();

        $this->assertDatabaseCount(Post::class, 5);
        $this->assertDatabaseMissing(Post::class, ['title'=>$newTitle,'content'=>$newContent]);
    }


}
