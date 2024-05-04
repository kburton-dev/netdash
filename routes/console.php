<?php

use App\Jobs\FetchFeedItems;
use App\Jobs\SqliteBackupJob;
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
        ->each(function (Feed $feed) use ($feedId) {
            $this->comment("Fetching feed with ID {$feed->id}, URL: {$feed->url}");

            $feedId
                ? FetchFeedItems::dispatchSync($feed)
                : FetchFeedItems::dispatch($feed);
        });

    $this->comment('Dispatching feed fetching... Done!');
})->purpose('Fetch feeds');

Schedule::command('app:get-feeds')->hourly();
Schedule::job(SqliteBackupJob::class)->dailyAt('02:00');
