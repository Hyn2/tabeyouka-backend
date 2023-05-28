<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Community;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // not needed, comments should only be displayed with corresponding post
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // not needed, comment form should be included in the community.show view
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $community)
    {
        $request->validate([
            'text' => 'required',
        ]);

        $post = Community::findOrFail($community);

        $comment = Comment::create([
            'post_id' => $post->id,
            'author_id' => auth()->id(),
            'text' => $request->text,
        ]);

        return redirect()->route('community.show', ['community' => $post->id])->with('success', '댓글이 작성되었습니다.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // not needed, comments should only be displayed with corresponding post
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // not needed, use an inline editing system in community.show
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $community, Comment $comment)
    {
        $request->validate([
            'text' => 'required',
        ]);

        $post = Community::findOrFail($community);
        $comment->update([
            'text' => $request->text,
        ]);

        return redirect()->route('community.show', ['community' => $post->id])->with('success', '댓글이 업데이트되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $community, Comment $comment)
    {
        $post = Community::findOrFail($community);
        $comment->delete();

        return redirect()->route('community.show', ['community' => $post->id])->with('success', '댓글이 삭제되었습니다.');
    }
}
