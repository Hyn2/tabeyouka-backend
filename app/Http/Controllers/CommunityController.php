<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Community::get();
        return response()->json(['posts' => $posts]);
    }

    public function create()
    {
        return view('community.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $imageName = $request->image_file->store('public'); // 파일 저장 및 고유 이름 생성
            $imagePath = 'http://localhost:8080/storage/images/' . basename($imageName);
        } else {
            $imageName = null;
        }

        $post = Community::create([
            'author_id' => auth()->id(),
            'title' => $request->title,
            'text' => $request->text,
            'image' => $imagePath,
        ]);

        return redirect()->route(
            'community.show',
            ['community' => $post->id]
        )->with('success', '게시물이 생성되었습니다.');
    }

    public function show($id)
    {
        $post = Community::with('comments')->findOrFail($id);
        return response()->json(['post' => $post]);
    }

    public function edit($id)
    {
        $post = Community::findOrFail($id);
        return view('community.edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Community::findOrFail($id);

        if ($request->hasFile('image_file')) {
            $imageName = $request->image_file->store('public'); // 파일 저장 및 고유 이름 생성
            $imagePath = 'http://localhost:8080/storage/images/' . basename($imageName);
        } else {
            $imageName = $post->image; // 이미지 파일이 없으면 예전 이미지를 유지
        }

        $post->update([
            'title' => $request->title,
            'text' => $request->text,
            'image' => $imagePath,
        ]);

        return redirect()->route(
            'community.show',
            ['community' => $post->id]
        )->with('success', '게시물이 업데이트되었습니다.');
    }

    public function destroy($id)
    {
        $post = Community::findOrFail($id);
        $post->delete();

        return redirect()->route('community.index')->with('success', '게시물이 삭제되었습니다.');
    }
}
