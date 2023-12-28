<?php

declare(strict_types=1);

namespace App\Feeds;

use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\Data\Element;
use Saloon\XmlWrangler\XmlReader;

class AtomParser implements Parser
{
    /**
     * {@inheritDoc}
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection
    {
        return $rawFeedData->element('feed.entry')->collect()
            ->map(function (Element $item): FeedItem {
                /** @var array<string, \Saloon\XmlWrangler\Data\Element> $content */
                $content = $item->getContent();

                return new FeedItem(
                    title: (string) $content['title']->getContent(),
                    url: (string) $content['link']->getAttribute('href'),
                    description: (string) $content['summary']->getContent(),
                    publishedAt: Carbon::parse((string) $content['updated']->getContent()),
                );
            });
    }
}
