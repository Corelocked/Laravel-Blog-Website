<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'title' => $this->faker->words(8, true),
            'excerpt' => $this->faker->sentence(40),
            'body' => $this->faker->text(2000),
            'image_path' => $this->faker->randomElement(['/images/picture2.jpg', '/images/picture.jpg']),
            'is_published' => true,
            'user_id' => 1,
        ];
    }
}
