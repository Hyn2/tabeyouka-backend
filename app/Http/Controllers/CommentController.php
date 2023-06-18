<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Community;

class CommentController extends Controller
{
    public function index($post)
    {
        $comments = Comment::where('post_id', $post)->get();

        return response()->json(['comments' => $comments]);
    }

    public function store(Request $request, $community)
    {
        $request->validate([
            'text' => 'required|string',
            'author_id' => 'required',
            'nickname' => 'required',
        ]);

        $comment = new Comment([
            'text' => $request->text,
            'author_id' => $request->author_id,
            'nickname' => $request->nickname,
            'post_id' => $community,
        ]);

        $comment->save();

        return response()->json(['comment' => $comment], 201);
    }

    public function update(Request $request, $community, $commentId)
    {
        $request->validate([
            'text' => 'required|string',
            'author_id' => 'required',
        ]);

        $comment = Comment::findOrFail($commentId);
        
        if ($request->author_id !== $comment->author_id) {
            return response()->json(['error' => 'You are not authorized to update this comment.'], 403);
        }

        $comment->update([
            'text' => $request->text,
        ]);

        return response()->json(['comment' => $comment]);
    }

    public function destroy(Request $request, $community, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        if ($request->author_id !== $comment->author_id) {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }

        $comment->delete();

        return response()->json(['message' => '댓글이 삭제되었습니다.']);
    }
}
