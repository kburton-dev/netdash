<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Seeder;

class BulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        Feed::factory()
            ->count(100)
            ->has(Article::factory()->count(1500))
            ->create([
                'user_id' => $user->id,
            ]);
    }
}
