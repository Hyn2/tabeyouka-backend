<?php

namespace App\Http\Controllers;

use App\Models\LocalSemesterComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LocalSemesterCommentsController extends Controller
{
    // 모든 댓글 불러오기
    public function getComments()
    {
        $columns = ['id', 'author_id', 'comment_text'];
        $lsComments = LocalSemesterComments::select($columns)->get();

        return response()->json($lsComments);
    }
    // 댓글 작성
    public function addComment(Request $request)
    {
        try {
            $validated = $request->validate(
                ['comment_text'=>'required']
            );
        } catch (ValidationException $e) {
            $errMsg = $e->errors();
            return response()->json(['errors' => $errMsg], 422);
        }

   
        $lsComments = new LocalSemesterComments();
        $lsComments -> author_id = Auth::id();
        $lsComments -> comment_text = $validated['comment_text'];
        $lsComments -> save();
        return response()->json(['message' => 'Add comment successfully']);
    }
    // 댓글 수정
    public function editComment(Request $request)
    {
        try {
            $validated = $request->validate([
                'id'=>'required',
                'author_id'=>'required',
                'comment_text'=>'required',
            ]);
        }
        catch(ValidationException $e) {
            $errMsg = $e->errors();
            return response()->json(['errors' => $errMsg], 422);
        }


        $lsComments = LocalSemesterComments::find($validated['id']);
        // if(!Auth::id() == $validated['author_id']) {
        //     return response()->json(['message' => 'id is diffrent'], 403);  
        // }
        $lsComments -> comment_text = $validated['comment_text'];
        $lsComments -> save();

        return response()->json(['message' => 'Edit comment successfully']);  
    }
    // 댓글 삭제
    public function deleteComment($id)
    {
        $lsComments = LocalSemesterComments::find($id);

        if(!$lsComments) {
            return response()->json(['message'=> 'Comment was not found'],404);
        }

        if(Auth::id() == $lsComments['author_id']) {
            $lsComments->delete();
        }

        return response()->json(['message' => 'Delete comment successfully']);
    }
}
