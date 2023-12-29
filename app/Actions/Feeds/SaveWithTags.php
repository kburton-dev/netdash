<?php

declare(strict_types=1);

namespace App\Actions\Feeds;

use App\Feeds\FeedType;
use App\Jobs\FetchFeedItems;
use App\Models\Feed;
use Carbon\CarbonInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaveWithTags
{
    /**
     * @param  array{title?: string, type?: FeedType, url?: string, last_fetch?: CarbonInterface, tagIds?: list<int>}  $data
     */
    public function __invoke(Feed $feed, array $data): Feed
    {
        return DB::transaction(function () use ($feed, $data): Feed {
            $saved = save_model($feed, Arr::except($data, 'tagIds'));

            if (array_key_exists('tagIds', $data)) {
                $feed->tags()->sync($data['tagIds']);
            }

            if ($feed->wasRecentlyCreated || $feed->wasChanged('url')) {
                FetchFeedItems::dispatch($feed)->afterCommit();
            }

            return $saved;
        });
    }
}
