<?php

use App\Actions\Feeds\SaveWithTags;
use App\Feeds\FeedType;
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
        'type' => FeedType::RSS,
        'url' => 'https://example.com',
        'tagIds' => $tagIds,
    ]);

    $feed = $feed->fresh();

    expect($feed->tags->pluck('id')->toArray())->toBe($tagIds);
    expect($feed->title)->toBe('title');
    expect($feed->type)->toBe(FeedType::RSS);
    expect($feed->url)->toBe('https://example.com');
    Queue::assertPushed(FetchFeedItems::class, fn (FetchFeedItems $job) => $job->feed->is($feed));
});
