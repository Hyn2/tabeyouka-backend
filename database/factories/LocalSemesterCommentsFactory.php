<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocalSemesterComments>
 */
class LocalSemesterCommentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id'=>$this->faker->numberBetween(1, 5),
            'comment_text'=>$this->faker->paragraph(),
        ];
    }
}
