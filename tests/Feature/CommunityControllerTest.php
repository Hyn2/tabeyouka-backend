<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Community;

class CommunityTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_index_displays_posts()
    {
        $user = User::factory()->create();
        $posts = Community::factory()->count(5)->create(['author_id' => $user->id]);

        $response = $this->get(route('community.index'));

        foreach ($posts as $post) {
            $response->assertSee($post->title);
            $response->assertSee($post->content);
        }
    }

    public function test_show_displays_post()
    {
        $user = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $user->id]);

        $response = $this->get(route('community.show', $post->id));

        $response->assertSee($post->title);
        $response->assertSee($post->content);
    }

    public function test_create_displays_form_for_new_post()
    {
        $user = User::factory()->create();

        $response = $this->get(route('community.create'));

        $response->assertStatus(200);
    }

    public function test_store_adds_new_post()
    {
        $user = User::factory()->create();
        $post = Community::factory()->make(['author_id' => $user->id]);

        $response = $this->post(route('community.store'), $post->toArray());

        $response->assertRedirect(route('community.index'));
        $this->assertDatabaseHas('communities', [
            'title' => $post->title,
            'content' => $post->content,
        ]);
    }
}