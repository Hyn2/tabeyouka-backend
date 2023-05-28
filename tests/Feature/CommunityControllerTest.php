<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Community;

class CommunityControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_displays_posts()
    {
        $user = User::factory()->create();
        $posts = Community::factory()->count(5)->create(['author_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('community.index'));

        $response->assertStatus(200);
        $response->assertViewIs('community.index');
        $response->assertViewHas('posts');
    }

    public function test_create_post_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('community.create'));

        $response->assertStatus(200);
        $response->assertViewIs('community.create');
    }

    public function test_store_new_post()
    {
        $user = User::factory()->create();

        $postData = [
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'image' => UploadedFile::fake()->create('image.jpg', 0),
        ];

        $response = $this->actingAs($user)->post(route('community.store'), $postData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', '게시물이 생성되었습니다.');
        $this->assertDatabaseHas('communities', [
            'title' => $postData['title'],
            'text' => $postData['text'],
            'author_id' => $user->id,
        ]);
    }

    public function test_show_post_page()
    {
        $user = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('community.posts.show', $post->id));

        $response->assertStatus(200);
        $response->assertViewIs('community.posts.show'); // 변경된 뷰 이름
        $response->assertViewHas('post', $post);
    }

    public function test_edit_post_page()
    {
        $author = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $author->id]);

        $response = $this->actingAs($author)->get(route('community.edit', $post->id));

        $response->assertStatus(200);
        $response->assertViewIs('community.edit');
        $response->assertViewHas('post', $post);
    }

    public function test_update_post()
    {
        $author = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $author->id]);

        $updatedData = [
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'image' => UploadedFile::fake()->create('image.jpg', 0),
        ];

        $response = $this->actingAs($author)->put(route('community.update', $post->id), $updatedData);

        $response->assertStatus(302);
        $response->assertSessionHas('success', '게시물이 업데이트되었습니다.');
        $this->assertDatabaseHas('communities', [
            'title' => $updatedData['title'],
            'text' => $updatedData['text'],
            'author_id' => $author->id,
        ]);
    }

    public function test_delete_post()
    {
        $author = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $author->id]);

        $response = $this->actingAs($author)->delete(route('community.destroy', $post->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success', '게시물이 삭제되었습니다.');
        $this->assertDatabaseMissing('communities', ['id' => $post->id]);
    }
}
