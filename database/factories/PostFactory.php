<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'user_id' => User::inRandomOrder()->first()->id,
            'body' => $this->faker->text(50),
            'cover_image' => $this->faker->imageUrl,
            'pinned' => $this->faker->boolean,
        ];
    }
}
