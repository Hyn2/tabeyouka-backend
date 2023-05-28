<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Community;
use App\Http\Controllers\CommentController;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store_new_comment()
    {
        $user = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $user->id]);

        $commentData = [
            'text' => $this->faker->sentence,
        ];

        $response = $this->actingAs($user)->post(route('comment.store', ['community' => $post->id]), $commentData);

        $response->assertStatus(302);
        $response->assertRedirect(route('community.show', ['community' => $post->id]));
        $response->assertSessionHas('success', '댓글이 작성되었습니다.');
        $this->assertDatabaseHas('comments', $commentData);
    }

    public function test_update_comment()
    {
        $user = User::factory()->create();
        $post = Community::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create([
            'author_id' => $user->id,
            'post_id' => $post->id
        ]);

        $updatedData = [
            'text' => $this->faker->sentence,
        ];

        $response = $this->actingAs($user)->put(route('comment.update', ['community' => $post->id, 'comment' => $comment->id]), $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect(route('community.show', ['community' => $post->id]));
        $response->assertSessionHas('success', '댓글이 업데이트되었습니다.');
        $this->assertDatabaseHas('comments', array_merge($updatedData, ['id' => $comment->id]));
    }

    public function test_delete_comment()
    {
        $user = User::factory()->create();
        $community = Community::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create([
            'author_id' => $user->id,
            'post_id' => $community->id
        ]);

        $response = $this->actingAs($user)->delete(route('comment.destroy', ['community' => $community->id, 'comment' => $comment->id]));

        if ($response) {
            $response->assertStatus(302)
                ->assertRedirect(route('community.show', ['community' => $community->id]))
                ->assertSessionHas('success', '댓글이 삭제되었습니다.');

            $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        } else {
            $this->fail('response 객체 생성 실패');
        }
    }
}
