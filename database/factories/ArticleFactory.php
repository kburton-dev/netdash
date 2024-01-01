<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'url' => fake()->unique()->url(),
            'content' => fake()->paragraphs(3, true),
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
