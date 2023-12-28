<?php

namespace Database\Factories;

use App\Feeds\FeedType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feed>
 */
class FeedFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'url' => fake()->url(),
            'last_fetch' => fake()->dateTimeBetween('-1 year'),
            'type' => fake()->randomElement([FeedType::RSS, FeedType::ATOM]),
        ];
    }
}
