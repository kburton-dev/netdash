<?php

declare(strict_types=1);

namespace App\Feeds;

use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\XmlReader;

class AtomParser implements Parser
{
    /**
     * {@inheritDoc}
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection
    {
        return $rawFeedData->value('feed.entry')->collect()
            ->map(function (array $item): FeedItem {
                return new FeedItem(
                    title: (string) $item['title'],
                    url: (string) $item['id'],
                    description: (string) $item['summary'],
                    publishedAt: Carbon::parse((string) $item['updated']),
                );
            });
    }
}
