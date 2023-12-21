<?php declare(strict_types=1);

namespace App\Feeds;
use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\XmlReader;

class RssParser implements Parser
{
    /**
     * @inheritDoc
     */
    public function parse(XmlReader $rawFeedData): array
    {
        return $rawFeedData->value('rss.channel.item')->collect()
            ->map(function (array $item): array {
                return [
                    'title' => $item['title'],
                    'url' => $item['link'],
                    'description' => $item['description'],
                    'published_at' => Carbon::parse($item['pubDate']),
                ];
            })
            ->all();
    }
}