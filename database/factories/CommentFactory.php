<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'post_id' => Community::factory(), // or replace with a valid post id
            'author_id' => User::factory(),   // or replace with a valid author id
            'text' => $this->faker->sentence,
        ];
    }
}
