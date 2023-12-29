<?php

use App\Feeds\FeedType;
use App\Feeds\ParserFactory;
use App\Jobs\FetchArticleImage;
use App\Jobs\FetchFeedItems;
use App\Models\Feed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

it('imports feed items', function () {
    $feed = Feed::factory()->create([
        'url' => 'https://blog.jetbrains.com/feed',
        'type' => FeedType::RSS,
        'last_fetch' => now()->subHour(),
    ]);

    Queue::fake();
    Carbon::setTestNow(now()); // This allows us to assert the $last_fetch date of the feed without random failures
    Http::fake([
        $feed->url => Http::response(file_get_contents(__DIR__.'/PhpAnnotatedFeed.xml')),
    ]);

    (new FetchFeedItems($feed))->handle(app(ParserFactory::class));

    expect((string) $feed->last_fetch)->toBe((string) now());
    expect($feed->articles()->count())->toBe(10);

    $articleWithoutImage = $feed->articles()->whereNull('image')->sole();
    Queue::assertPushed(FetchArticleImage::class, fn (FetchArticleImage $job) => $job->article->is($articleWithoutImage));
});
