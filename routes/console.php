<?php

use App\Feeds\FeedType;
use App\Jobs\FetchFeedItems;
use App\Models\Feed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:get-feeds {feedId?}', function () {
    $this->comment('Dispatching feed fetching...');

    Feed::query()
        ->when($feedId = $this->argument('feedId'), fn (Builder $query) => $query->where('id', $feedId))
        ->each(function (Feed $feed) {
            $this->comment("Fetching feed with ID {$feed->id}, URL: {$feed->url}, type: {$feed->type->value}");

            dispatch(new FetchFeedItems($feed));
        });

    $this->comment('Dispatching feed fetching... Done!');
})->purpose('Fetch feeds');

Artisan::command('app:add-feed {url} {type}', function (string $url, string $type) {
    $this->comment("Adding feed with URL: {$url}, type: {$type}");

    $title = $this->ask('What is the title of the feed?');

    $created = Feed::query()->create([
        'url' => $url,
        'title' => $title,
        'type' => FeedType::from($type),
    ]);

    throw_unless($created, new \LogicException('Failed to create feed'));

    $this->comment('Adding feed... Done!');
})->purpose('Add feed');
