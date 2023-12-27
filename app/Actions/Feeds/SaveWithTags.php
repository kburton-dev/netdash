<?php

declare(strict_types=1);

namespace App\Actions\Feeds;

use App\Feeds\FeedType;
use App\Models\Feed;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaveWithTags
{
    /**
     * @param  array{title: string, type: FeedType, url: string, tagIds: list<int>}  $data
     */
    public function __invoke(Feed $feed, array $data): Feed
    {
        return DB::transaction(function () use ($feed, $data): Feed {
            $saved = save_model($feed, $data);

            $feed->tags()->sync(
                Arr::pull($data, 'tagIds', [])
            );

            return $saved;
        });
    }
}
