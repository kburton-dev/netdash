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
            ], [
                'id' => 2,
                'title' => 'Boot.dev',
                'url' => 'https://blog.boot.dev/index.xml',
            ], [
                'id' => 3,
                'title' => 'PlanetScale',
                'url' => 'https://planetscale.com/blog/rss.xml',
            ], [
                'id' => 4,
                'title' => 'Muhammed Sari',
                'url' => 'https://muhammedsari.me/feed.atom',
            ], [
                'id' => 5,
                'title' => 'Frank de Jonge',
                'url' => 'https://blog.frankdejonge.nl/rss/',
            ], [
                'id' => 6,
                'title' => 'Jake Archibald',
                'url' => 'https://jakearchibald.com/posts.rss',
            ], [
                'id' => 7,
                'title' => 'Freek Van der Herten',
                'url' => 'https://freek.dev/feed',
            ], [
                'id' => 8,
                'title' => 'Spatie',
                'url' => 'https://spatie.be/feed',
            ], [
                'id' => 9,
                'title' => 'Mohammed Said',
                'url' => 'https://themsaid.com/feed',
            ], [
                'id' => 10,
                'title' => 'Tailwind CSS',
                'url' => 'https://tailwindcss.com/feeds/feed.xml',
            ], [
                'id' => 11,
                'title' => 'PHP Annotated',
                'url' => 'https://blog.jetbrains.com/phpstorm/feed/',
            ], [
                'id' => 12,
                'title' => 'Stitcher.IO',
                'url' => 'https://stitcher.io/rss',
            ], [
                'id' => 13,
                'title' => 'Robb Knight',
                'url' => 'https://rknight.me/feed.xml',
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
