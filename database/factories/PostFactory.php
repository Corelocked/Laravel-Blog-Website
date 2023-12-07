<?php

namespace Database\Factories;

use Illuminate\Support\Str;
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
        $title = $this->faker->words(8, true);
        return [
            'title' => $title,
            'excerpt' => $this->faker->sentence(40),
            'body' => $this->faker->text(2000),
            'image_path' => $this->faker->randomElement(['/images/picture2.jpg', '/images/picture.jpg']),
            'slug' => Str::slug($title),
            'is_published' => true,
            'user_id' => 1,
        ];
    }
}
