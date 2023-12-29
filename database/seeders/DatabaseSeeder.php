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
                'id' => 1,
                'title' => 'Laravel News',
                'url' => 'https://feed.laravel-news.com/',
                'type' => 'rss',
            ], [
                'id' => 2,
                'title' => 'Boot.dev',
                'url' => 'https://blog.boot.dev/index.xml',
                'type' => 'rss',
            ], [
                'id' => 3,
                'title' => 'PlanetScale',
                'url' => 'https://planetscale.com/blog/rss.xml',
                'type' => 'rss',
            ], [
                'id' => 4,
                'title' => 'Muhammed Sari',
                'url' => 'https://muhammedsari.me/feed.atom',
                'type' => 'atom',
            ], [
                'id' => 5,
                'title' => 'Frank de Jonge',
                'url' => 'https://blog.frankdejonge.nl/rss/',
                'type' => 'rss',
            ], [
                'id' => 6,
                'title' => 'Jake Archibald',
                'url' => 'https://jakearchibald.com/posts.rss',
                'type' => 'rss',
            ], [
                'id' => 7,
                'title' => 'Freek Van der Herten',
                'url' => 'https://freek.dev/feed',
                'type' => 'atom',
            ], [
                'id' => 8,
                'title' => 'Spatie',
                'url' => 'https://spatie.be/feed',
                'type' => 'atom',
            ], [
                'id' => 9,
                'title' => 'Mohammed Said',
                'url' => 'https://themsaid.com/feed',
                'type' => 'atom',
            ], [
                'id' => 10,
                'title' => 'Tailwind CSS',
                'url' => 'https://tailwindcss.com/feeds/feed.xml',
                'type' => 'atom',
            ], [
                'id' => 11,
                'title' => 'PHP Annotated',
                'url' => 'https://blog.jetbrains.com/feed',
                'type' => 'rss',
            ], [
                'id' => 12,
                'title' => 'Stitcher.IO',
                'url' => 'https://stitcher.io/rss',
                'type' => 'rss',
            ],
        ]);

        DB::table('tags')->insert([
            [
                'id' => 1,
                'name' => 'php',
            ], [
                'id' => 2,
                'name' => 'laravel',
            ], [
                'id' => 3,
                'name' => 'web',
            ], [
                'id' => 4,
                'name' => 'sql',
            ],
        ]);

        DB::table('taggables')->insert([
            [
                'tag_id' => 1,
                'taggable_id' => 1,
                'taggable_type' => 'App\Models\Feed',
            ], [
                'tag_id' => 2,
                'taggable_id' => 1,
                'taggable_type' => 'App\Models\Feed',
            ], [
                'tag_id' => 4,
                'taggable_id' => 3,
                'taggable_type' => 'App\Models\Feed',
            ],
        ]);
    }
}
