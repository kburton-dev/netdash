<?php

use App\Actions\Feeds\SaveWithTags;
use App\Jobs\FetchFeedItems;
use App\Models\Feed;
use App\Models\Tag;
use Illuminate\Support\Facades\Queue;

it('can save a new feed', function () {
    Queue::fake();

    $feed = new Feed;
    $tagIds = Tag::factory()
        ->count(3)
        ->create()
        ->take(-2)
        ->pluck('id')
        ->toArray();

    $saveWithTags = app()->make(SaveWithTags::class);
    $saveWithTags($feed, [
        'title' => 'title',
        'url' => 'https://example.com',
        'tagIds' => $tagIds,
    ]);

    $feed = $feed->fresh();

    expect($feed->tags->pluck('id')->toArray())->toBe($tagIds);
    expect($feed->title)->toBe('title');
    expect($feed->url)->toBe('https://example.com');
    Queue::assertPushed(FetchFeedItems::class, fn (FetchFeedItems $job) => $job->feed->is($feed));
});
