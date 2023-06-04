<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Community;

class CommentController extends Controller
{
    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)->get();

        return response()->json(['comments' => $comments]);
    }

    public function store(Request $request, $postId)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $comment = Comment::create([
            'text' => $request->text,
            'author_id' => auth()->id(),
            'post_id' => $postId,
        ]);

        return response()->json(['comment' => $comment], 201);
    }

    public function update(Request $request, $commentId)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $comment = Comment::findOrFail($commentId);
        
        if (auth()->id() !== $comment->author_id) {
            return response()->json(['error' => 'You are not authorized to update this comment.'], 403);
        }

        $comment->update([
            'text' => $request->text,
        ]);

        return response()->json(['comment' => $comment]);
    }

    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if (auth()->id() !== $comment->author_id) {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => '댓글이 삭제되었습니다.']);
    }
}
