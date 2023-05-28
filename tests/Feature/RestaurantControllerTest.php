<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurant;

class RestaurantControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function test_returns_a_list_of_restaurants()
  {
    // Arrange
    $restaurants = Restaurant::factory()->count(3)->create();

    // Act
    $response = $this->getJson('/api/restaurants');

    // Assert
    $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
              '*' => [
                'title',
                'address',
                'menu_type',
                'phone_number',
                'total_points',
                'total_votes',
                'image',
              ]
            ]);
  }

  /** @test */
  public function test_returns_a_single_restaurant_by_id()
  {
    // Arrange
    $restaurant = Restaurant::factory()->create();

    // Act
    $response = $this->getJson("/api/restaurants/{$restaurant->id}");

    // Assert
    $response->assertStatus(200)
            ->assertJson([
              'restaurant' => [
                'id' => $restaurant->id,
                'title' => $restaurant->title,
                'address' => $restaurant->address,
                'menu_type' => $restaurant->menu_type,
                'phone_number' => $restaurant->phone_number,
                'total_points' => $restaurant->total_points,
                'total_votes' => $restaurant->total_votes,
                'image' => $restaurant->image,
              ],
              'reviews' => [],
            ]);
  }

  /** @test */
  public function test_stores_a_new_restaurant()
  {
    $restaurantData = [
      'title' => 'Test Restaurant',
      'address' => '123 Main Street',
      'menu_type' => 'Italian',
      'phone_number' => '555-1234',
      'total_points' => 0,
      'total_votes' => 0,
      'image' => 'test-image.jpg',
    ];

    $response = $this->postJson('/api/restaurants', $restaurantData);

    $response->assertStatus(201)->assertJson($restaurantData);
    $this->assertDatabaseHas('restaurants', $restaurantData);
  }

  /** @test */
  public function test_updates_an_existing_restaurant()
  {
    $restaurant = Restaurant::factory()->create();
    $updateData = [
      'title' => 'Updated Restaurant',
      'address' => '123 Updated Street',
    ];

    $response = $this->putJson("/api/restaurants/{$restaurant->id}", $updateData);

    $response->assertStatus(200)->assertJson($updateData);
    $this->assertDatabaseHas('restaurants', array_merge(['id' => $restaurant->id], $updateData));
  }

  /** @test */
  public function test_deletes_an_existing_restaurant()
  {
    $restaurant = Restaurant::factory()->create();

    $response = $this->deleteJson("/api/restaurants/{$restaurant->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('restaurants', ['id' => $restaurant->id]);
  }
}
