<?php

use App\Models\Article;
use App\Models\Feed;
use App\Models\Tag;
use Livewire\Volt\Volt;

it('can show articles', function () {
    $tag = Tag::factory()->create();
    $feed = Feed::factory()->create();
    $articleNames = Article::factory()
        ->count(3)
        ->create([
            'feed_id' => $feed->id,
        ])
        ->pluck('title')
        ->toArray();
    $feed->tags()->attach($tag);

    Volt::test('pages.dashboard')
        ->assertSee($articleNames)
        ->assertSee($tag->name);
});
