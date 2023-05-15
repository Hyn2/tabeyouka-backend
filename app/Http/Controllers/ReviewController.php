<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Hyn2's part
class ReviewController extends Controller
{
    // 리뷰 추가
    public function addReview(Request $request)
    {
        // request 변수로 입력 정보를 받아서 validate에 정의 된 규칙에 부합한지 판단하고
        // $validate 변수에 저장
        $validated = $request->validate([
            'rating'=>'required',
            'review_text'=>'required|min:10',
            'image_file'=>'required',
        ]);
        
        try {
            // Review 모델의 새로운 인스턴스 생성
            $review = new Review();
            // Auth_::id()는 현재 사용자의 ID를 반환함, 그리고 리뷰의 author_id에 저장
            $review->author_id = Auth::id();
            // 'rating' 필드의 값을 리뷰의 'rating' 속성에 할당
            $review->rating = $validated['rating'];
            // 리뷰의 'review_text' 속성에 할당
            $review->review_text = $validated['review_text'];
            // 파일 업로드 처리
            $file = $request->file('image_file'); // $file에 저장
            $path = $file->store('photos');; // store()메서드는 지정된 경로에 파일을 저장
            $review->image_file = $path; // store 메서드를 활용해 저장한 경로가 $path 변수에 할당됨
            // 새 리뷰를 데이터베이스에 저장
            $review->save();
            // 데이터베이스에 해당 값이 들어있는지 확인
            // 구현해야 함
            // 성공되었을 시에 메시지
            return response()->json(['message' => 'Add review successfully']);
        } catch (\Exception $e) {
            // 에러 발생 시
            return response()->json(['message' => 'Failed to add review'], 500);
        }
    }

    // 리뷰 삭제
    public function deleteReview($id)
    {
    // 1. 데이터베이스에서 해당 리뷰 조회
    // 전달 받은 id값과 일치하는 레코드를 $review에 저장한다.
    $review = Review::find($id);

    // 리뷰가 존재하지 않는 경우 오류 응답 반환
    // find() 메서드는 값이 존재하지 않으면 null 값을 반환하기에 만약 해당하는 레코드가 없으면 에러를 전달
    if (!$review) {
        return response()->json(['message' => 'Review not found'], 404);
    }

    // 2. 리뷰 삭제
    $review->delete();
    // 데이터베이스에 해당 값이 삭제되었는지 확인
    // 3. 성공 응답 반환
    return response()->json(['message' => 'Review deleted successfully']);
    }
}