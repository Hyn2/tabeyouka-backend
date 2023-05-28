<?php

namespace Database\Factories;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityFactory extends Factory
{
    protected $model = Community::class;

    public function definition()
    {
        return [
            'author_id' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'image' => $this->faker->image('public/storage', 640, 480, null, false),
        ];
    }
}
