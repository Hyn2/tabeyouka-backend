<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Response;

class CommunityController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->except('index', 'show');
    // }

    const IMAGE_PATH = 'public/images/communities/';
    const IMAGE_URL = 'http://localhost:8080/storage/images/communities/';

    public function index()
    {
        $posts = Community::get();
        return response()->json(['posts' => $posts]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required',
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // if ($request->hasFile('image')) {
        //     $imageName = $request->image->store(self::IMAGE_PATH); // 파일 저장 및 고유 이름 생성
        //     $imagePath = self::IMAGE_URL . basename($imageName);
        // } else {
        //     $imagePath = null;
        // }

        if ($request->hasFile('image')) {
            $imageName = $request->image->store('public/images/communities'); // 파일 저장 및 고유 이름 생성
            $imagePath = 'http://localhost:8080/storage/images/communities/' . basename($imageName);
        } else {
            $imagePath = null;
        }

        // $post = Community::create([
        //     'author_id' => $request->user()->id,
        //     'title' => $request->title,
        //     'text' => $request->text,
        //     'image' => $this->manageImage($request, $post),
        //     // 'image' => $imagePath,
        // ]);

        // 사용자가 로그인했는지 여부를 판단합니다.
        // $isLoggedIn = $request->session()->has('user_id');

        // if (!$isLoggedIn) {
        //     return response()->json(['message' => 'User not logged in'], Response::HTTP_UNAUTHORIZED);
        // }

        // 로그인한 경우, 로그인 상태 메시지와 함께 사용자 정보를 반환합니다.
        // $userId = $request->session()->get('user_id');
        
        // $user = User::find($userId);

        $post = Community::create([
            'author_id' => $request->author_id,
            'title' => $request->title,
            'text' => $request->text,
            'image' => $imagePath,
        ]);

        return response()->json(['post_id' => $post->id, 'message' => '게시물이 생성되었습니다.']);
    }

    public function show($id)
    {
        $post = Community::with('comments')->findOrFail($id);
        return response()->json(['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Community::findOrFail($id);

        if ($request->hasFile('image')) {
            // 이전 이미지 삭제
            $oldImage = str_replace('http://localhost:8080/storage/', '', $post->image);
            Storage::delete($oldImage);

            $imageName = $request->image->store('public/images/communities'); // 파일 저장 및 고유 이름 생성
            $imagePath = 'http://localhost:8080/storage/images/communities/' . basename($imageName);
        } else {
            $imagePath = $post->image; // 이미지 파일이 없으면 예전 이미지를 유지
        }

        $post = Community::findOrFail($id);
        // $post->image = $this->manageImage($request, $post);
        $post->image = $imagePath;
        $post->title = $request->title;
        $post->text = $request->text;
        $post->update();

        // $post->update([
        //     'title' => $request->title,
        //     'text' => $request->text,
        //     'image' => $imagePath,
        // ]);

        // TODO: 수정
        // return redirect()->route(
        //     'community.show',
        //     ['community' => $post->id]
        // )->with('success', '게시물이 업데이트되었습니다.');
        return response()->json([
            'post_id' => $post->id,
            'message' => '게시물이 업데이트되었습니다.',
            'success' => true,
        ]);
    }

    public function destroy($id)
    {
        $post = Community::findOrFail($id);
        $post->delete();

        // TODO: 수정
        // return redirect()->route('community.index')->with('success', '게시물이 삭제되었습니다.');
        return response()->json([
            'message' => '게시물이 삭제되었습니다.',
            'success' => true,
        ]);
    }

    private function manageImage(Request $request, Community $post)
    {
        if ($request->hasFile('image')) {
            if ($post->image) {
                $oldImage = str_replace(self::IMAGE_URL, '', $post->image);
                Storage::delete($oldImage);
            }
            $imageName = $request->image->store(self::IMAGE_PATH);
            $imagePath = self::IMAGE_URL . basename($imageName);
        } else {
            $imagePath = $post->image;
        }

        return $imagePath;
    }
}
