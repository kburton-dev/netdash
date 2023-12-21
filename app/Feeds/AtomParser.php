<?php declare(strict_types=1);

namespace App\Feeds;
use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\XmlReader;

class AtomParser implements Parser
{
    /**
     * @inheritDoc
     */
    public function parse(XmlReader $rawFeedData): array
    {
        return $rawFeedData->value('feed.entry')->collect()
            ->map(function (array $item): array {
                return [
                    'title' => $item['title'],
                    'url' => $item['id'],
                    'description' => $item['summary'],
                    'published_at' => Carbon::parse($item['updated']),
                ];
            })
            ->all();
    }
}