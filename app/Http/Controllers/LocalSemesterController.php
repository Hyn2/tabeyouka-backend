<?php

namespace App\Http\Controllers;

use App\Models\LocalSemester;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LocalSemesterController extends Controller
{
    // 현지학기제 내용 수정
    public function editArticle(Request $request) {
        try {
            $validated = $request->validate(
                ['article' => 'required']
            );
        }
        catch(ValidationException $e) {
            $errMsg = $e->errors();
            return response()->json(['errors' => $errMsg], 422);
        }
        $localSemester = LocalSemester::first();
        if(!$localSemester) {
            return response()->json(['message' => 'article is not found'], 404);
        }
        $localSemester->article=$validated['article'];
        $localSemester->save();
        return response()->json(['message' => 'Edit article successfully']);
    }
    // 현지학기제 내용 불러오기
    public function getArticle() {
        $columns = ['id','article'];
        $localSemester = LocalSemester::select($columns)->first();
        return response()->json($localSemester);
    }
}

