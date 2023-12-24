<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        DB::table('feeds')->insert([
            [
                'title' => 'Laravel News',
                'url' => 'https://feed.laravel-news.com/',
                'type' => 'rss',
            ], [
                'title' => 'Boot.dev',
                'url' => 'https://blog.boot.dev/index.xml',
                'type' => 'rss',
            ], [
                'title' => 'PlanetScale',
                'url' => 'https://planetscale.com/blog/rss.xml',
                'type' => 'rss',
            ], [
                'title' => 'Muhammed Sari',
                'url' => 'https://muhammedsari.me/feed.atom',
                'type' => 'atom',
            ], [
                'title' => 'Frank de Jonge',
                'url' => 'https://blog.frankdejonge.nl/rss/',
                'type' => 'rss',
            ], [
                'title' => 'Jake Archibald',
                'url' => 'https://jakearchibald.com/posts.rss',
                'type' => 'rss',
            ]
        ]);
    }
}
