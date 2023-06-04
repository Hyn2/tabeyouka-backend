<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Community;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $community;
    private $comment;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->community = Community::factory()->for($this->user, 'author')->create();
        $this->comment = Comment::factory()->for($this->user, 'author')->for($this->community, 'post')->create();
        $this->comment->refresh();
    }

    public function test_store_comment()
    {
        $this->actingAs($this->user);
        $response = $this->post(route('comment.store', $this->community->id),
            ['text' => 'This is a new comment']);

        $response->assertStatus(201);
    }

    public function test_show_comment()
    {
        $response = $this->getJson(route('comment.index', ['postId' => $this->community->id]));
        $response->assertStatus(200);
        $response->assertJson([
            'comments' => [
                [
                    'id' => $this->comment->id,
                    'text' => $this->comment->text,
                    'author_id' => $this->user->id,
                    'post_id' => $this->community->id
                ]
            ]
        ]);
    }

    public function test_updating_comment()
    {
        $this->actingAs($this->user);
        $response = $this->patchJson(route('comment.update', ['commentId' => $this->comment->id]),
            ['text' => 'This is an updated comment']);

        $response->assertStatus(200);
        $response->assertJson([
            'comment' => [
                'id' => $this->comment->id,
                'text' => 'This is an updated comment',
                'author_id' => $this->user->id,
                'post_id' => $this->community->id
            ]
        ]);
    }

    function test_destroy_comment()
    {
        $this->actingAs($this->user);
        $response = $this->deleteJson(route('comment.destroy', ['commentId' => $this->comment->id]));

        $response->assertStatus(200);
        $response->assertJson([
            'message' => '댓글이 삭제되었습니다.'
        ]);

        $this->assertDatabaseMissing('comments', ['id' => $this->comment->id]);
    }    
}
