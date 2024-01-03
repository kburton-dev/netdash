<?php

use App\Livewire\Dashboard;
use App\Models\Article;
use App\Models\Feed;
use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

it('can show articles', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create();
    $feed = Feed::factory()->create([
        'user_id' => $user->id,
    ]);
    $articleNames = Article::factory()
        ->count(3)
        ->create([
            'feed_id' => $feed->id,
        ])
        ->pluck('title')
        ->toArray();
    $feed->tags()->attach($tag);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertSee($articleNames)
        ->assertSee($tag->name);
});
