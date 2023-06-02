<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\UploadedFile;


class ReviewControllerTest extends TestCase
{

  use RefreshDatabase;

  /** @test */
  public function test_adds_review_successfully()
  {
    // Arrange
    // 유저 생성
    $user = User::create([
      'email' => 'test@example.com',
      'nickname' => 'test-nickname',
      'password' => bcrypt('password'),
    ]);

    $data = [
      'restaurant_id' => 1,
      'rating' => 4,
      'review_text' => 'This is a great restaurant.',
      // UploadedFile::fake() : 파일 업로드
      // image(test-image.jpg) : 이미지 파일
      'image_file' => UploadedFile::fake()->image('test-image.jpg'),
    ];

    // Act
    // actingAs() 메서드를 사용하여 특정사용자로 로그인한 상태를 시뮬레이션 할 수 있음
    $response = $this->actingAs($user)
      ->postJson("/api/review", $data);

    // Assert
    // 상태코드가 200인지 확인, 응답본문이 같은지 확인
    $response->assertStatus(200)
      ->assertJson(['message' => 'Add review successfully']);
  }

  /** @test */
  public function test_deletes_review_successfully()
  {
    // Arrange
    $user = User::create([
      'email' => 'test@example.com',
      'nickname' => 'test-nickname',
      'password' => bcrypt('password'),
    ]);
    $review = Review::factory()->create();

    // Act
    $response = $this->actingAs($user)
      ->deleteJson("/api/review/{$review->id}");

    // Assert
    $response->assertStatus(200)
      ->assertJson(['message' => 'Review deleted successfully']);
  }
  /** @test */
  public function test_get_review_successfully()
  {
    // Arrange
    $review = Review::factory()->create();
    // Act
    $response = $this->getJson("/api/review/{$review->id}");
    // Assert
    $response->assertStatus(200)
      ->assertJson([
        'review' => [
          'id' => $review->id,
          'author_id' => $review->author_id,
          'restaurant_id' => $review->restaurant_id,
          'rating' => $review->rating,
          'review_text' => $review->review_text,
          'image_file' => $review->image_file,
        ],
      ]);
  }

  public function test_edit_review_successfully()
  {
    // Arrange
    $user = User::create([
      'email' => 'test@example.com',
      'nickname' => 'test-nickname',
      'password' => bcrypt('password'),
    ]);
    
    $review = Review::factory()->create();
    $data = [
      'id' => $review->id,
      'rating' => 1,
      'review_text' => 'veryveryveryvery delicious',
      // UploadedFile::fake() : 파일 업로드
      // image(test-image.jpg) : 이미지 파일
      'image_file' => UploadedFile::fake()->image('test-image.jpg'),
    ];

    // 테스트할 리뷰 수정 요청
    $response = $this->actingAs($user)
    ->putJson("/api/review", $data);


    // 응답 상태 코드 확인
    $response->assertStatus(200);

    // 리뷰 정보가 실제로 수정되었는지 확인
    $updatedReview = Review::find($review->id);
    $this->assertEquals(1, $updatedReview->rating);
    $this->assertEquals('veryveryveryvery delicious', $updatedReview->review_text);
  }

  public function test_get_restaurant_reviews_successfully()
  {
      // 레스토랑 팩토리 생성
      $restaurant = Restaurant::factory()->create();
  
      // 5개의 리뷰 팩토리 생성
      $reviews = Review::factory()->count(5)->create([
          'restaurant_id' => $restaurant->id,
      ]);
  
      $response = $this->getJson("/api/reviews/{$restaurant->id}");
  
      // Assert that the response has a 200 status code
      $response->assertStatus(200)
               ->assertJson($reviews->toArray());

  }
  
  
}
