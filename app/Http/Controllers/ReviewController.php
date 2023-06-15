<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    // 리뷰 추가
    public function addReview(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required',
            'nickname' => 'required',
            'restaurant_id' => 'required',
            'rating' => 'required',
            'review_text' => 'required',
            'image_file' => 'required',
        ]);
        
        // Review 모델의 새로운 인스턴스 생성
        $review = new Review();
        // Auth::id()는 현재 사용자의 ID를 반환함, 그리고 리뷰의 author_id에 저장
        $review->author_id = $validated['author_id'];
        $review->nickname = $validated['nickname'];
        // 'resturant_id 값을 리뷰에 저장'
        $review->restaurant_id = $validated['restaurant_id'];
        // 'rating' 필드의 값을 리뷰의 'rating' 속성에 할당
        $review->rating = $validated['rating'];
        // 리뷰의 'review_text' 속성에 할당
        $review->review_text = $validated['review_text'];
        // 파일 업로드 처리
        $fileName = $validated['image_file']-> store('public/images/reviews'); // $file에 저장

        $review->image_file = 'http://localhost:8080/storage/images/reviews'.$fileName; // store 메서드를 활용해 저장한 경로가 $path 변수에 할당됨
        // 새 리뷰를 데이터베이스에 저장
        $review->save();
        // 데이터베이스에 해당 값이 들어있는지 확인
        // 구현해야 함
        // 성공되었을 시에 메시지
        return response()->json(['message' => 'Add review successfully']);
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
            return response()->json(['message' => 'Review was not found'], 404);
        }
        // 2. 리뷰 삭제 실행
        $review->delete();

        // 데이터베이스에 해당 값이 삭제되었는지 확인
        // 3. 성공 응답 반환
        return response()->json(['message' => 'Review deleted successfully']);
    }

    // 리뷰 수정을 위해 아이디 값으로 리뷰 반환
    public function getReviewById($id)
    {
        // 1. 데이터베이스에서 해당 리뷰 조회
        // 전달 받은 id값과 일치하는 레코드를 $review에 저장한다.
        $review = Review::find($id);
        // 리뷰가 존재하지 않는 경우 오류 응답 반환
        // find() 메서드는 값이 존재하지 않으면 null 값을 반환하기에 만약 해당하는 레코드가 없으면 에러를 전달
        if (!$review) {
            return response()->json(['message' => 'Review Not Found'], 404);
        }
        // Review:: 는 Review 모델에 대한 정적인 호출을 의미
        // 해당하는 필드의 아이디 값이 일치하는지 확인한 후 첫 번째로 일치하는 식당을 반환 or 404
        $review = Review::select(
            'id',
            'author_id',
            'nickname',
            'restaurant_id',
            'rating',
            'review_text',
            'image_file'
        )
            ->where('id', $id)
            ->firstOrFail();
        // 행의 author_id 값을 받음
        $authorId = $review->author_id;
        // 현재 사용자의 id값을 받아 비교
        if (!($authorId == Auth::id())) {
            return response()->json(['message' => 'id is not correct'], 401);
        }
        // 일치하면 반환
        return response()->json(['review' => $review]);
    }

    // 리뷰 수정
    public function editReview(Request $request)
    {
        // request 변수로 입력 정보를 받아서 validate에 정의 된 규칙에 부합한지 판단하고
        // $validate 변수에 저장
        try {
            $validated = $request->validate([
                'id' => 'required',
                'rating' => 'required',
                'review_text' => 'required|min:10',
                'image_file' => 'required',
            ]);
        } catch (ValidationException $e) {
            $errMsg = $e->errors();
            return response()->json(['errors' => $errMsg], 400);
        }
        $review = Review::find($request['id']);
        // 정보 갱신
        $review->rating = $request['rating'];
        $review->review_text = $request['review_text'];
        $file = $request->file('image_file'); // $file에 저장
        $fileName = $file->store('public/images/reviews'); // store()메서드는 지정된 경로에 파일을 저장, 파일명을 fileName에 저장
        $review->image_file = 'http://localhost:8080/storage/image/reviews'.$fileName; // store 메서드를 활용해 저장한 경로가 $path 변수에 할당됨
        $review->save();
        return response()->json(['message' => 'Edit review successfully']);
    }

    public function getRestaurantReviews($restaurant_id)
    {
        // 해당 $restaurant_id를 가진 가게가 restaurant 테이블에 존재하는지 확인
        $restaurant = Restaurant::WHERE('id', $restaurant_id)->first();

        if(!$restaurant) {
            return response()->json(['error' => 'Restaurant Not Found'],404);
        }

        // 받은 $restaurant_id 기반으로 테이블의 restaurant_id와 일치하는 데이터를 가져옴
         $reviews = Review::WHERE('restaurant_id', $restaurant_id)->get();

        return response()->json($reviews);
    }
}