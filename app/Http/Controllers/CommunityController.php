<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Community::with('author')->orderBy('created_at', 'desc')->paginate(10);
        return view('community.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('community.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = $request->image->store('public'); // 파일 저장 및 고유 이름 생성
        } else {
            $imageName = null;
        }

        $post = Community::create([
            'author_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
            'image' => $imageName,
        ]);

        return redirect()->route(
            'community.show',
            ['community' => $post->id]
        )->with('success', '게시물이 생성되었습니다.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Community::findOrFail($id);
        return view('community.posts.show', ['post' => $post]); // 수정된 뷰 이름
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Community::findOrFail($id);
        return view('community.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Community::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = $request->image->store('public'); // 파일 저장 및 고유 이름 생성
        } else {
            $imageName = $post->image; // 이미지 파일이 없으면 예전 이미지를 유지
        }

        $post->update([
            'title' => $request->title,
            'text' => $request->text,
            'image' => $imageName,
        ]);

        return redirect()->route(
            'community.show',
            ['community' => $post->id]
        )->with('success', '게시물이 업데이트되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Community::findOrFail($id);
        $post->delete();

        return redirect()->route('community.index')->with('success', '게시물이 삭제되었습니다.');
    }
}
