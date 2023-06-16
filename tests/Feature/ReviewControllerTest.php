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
    $user = User::factory()->create();

    $review = Review::factory()->create();

    // Act
    // actingAs() 메서드를 사용하여 특정사용자로 로그인한 상태를 시뮬레이션 할 수 있음
    $response = $this->actingAs($user)
      ->postJson("/api/review", $review->toArray());

    // Assert
    // 상태코드가 200인지 확인, 응답본문이 같은지 확인
    $response->assertStatus(200)
      ->assertJson(['message' => 'Add review successfully']);
  }

  /** @test */
  public function test_deletes_review_successfully()
  {
    // Arrange
    $user = User::factory()->create();
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
          'nickname' => $review->nickname,
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
    $user = User::factory()->create();
    $review = Review::factory()->create();

    // 테스트할 리뷰 수정 요청
    $response = $this->actingAs($user)
    ->patchJson("/api/review", $review->toArray());


    // 응답 상태 코드 확인
    $response->assertStatus(200)
             ->assertJson(['message' => 'Edit review successfully']);
  }

  public function test_get_restaurant_reviews_successfully()
  {
      // 레스토랑 팩토리 생성
      $restaurant = Restaurant::factory()->create();
  
      // 5개의 리뷰 팩토리 생성
      $reviews = Review::factory()->count(5)->create([
          'restaurant_id' => $restaurant->id,
      ]);
  
      $response = $this->getJson("/api/restaurantreview/{$restaurant->id}");
  
      // Assert that the response has a 200 status code
      $response->assertStatus(200)
               ->assertJson($reviews->toArray());

  }
  
  
}
