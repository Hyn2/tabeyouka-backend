<?php

namespace Database\Factories;

use App\Models\Teammate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teammate>
 */
class TeammateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 
     * @var string
     */
    protected $model = Teammate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_id' => $this->faker->unique()->numberBetween(100000, 999999),
            'name' => $this->faker->name(),
            'profile_image' => $this->faker->imageUrl(),
            'part' => $this->faker->word,
            'description' => $this->faker->sentence,
            'github_link' => $this->faker->url,
        ];
    }
}
