<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => 1,
            'nickname' => $this->faker->name(),
            'restaurant_id' => $this->faker->numberBetween(1,2),
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->paragraph,
            'image_file' => UploadedFile::fake()->image('test-image.jpg'),
        ];
    }
}
