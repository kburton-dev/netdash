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
            ->map(function (array $item): array {
                return [
                    'title' => (string) $item['title'],
                    'url' => (string) $item['id'],
                    'description' => (string) $item['summary'],
                    'published_at' => Carbon::parse((string) $item['updated']),
                ];
            });
    }
}
