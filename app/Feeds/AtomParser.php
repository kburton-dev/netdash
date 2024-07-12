<?php

declare(strict_types=1);

namespace App\Feeds;

use Illuminate\Support\Carbon;
use Saloon\XmlWrangler\Data\Element;
use Saloon\XmlWrangler\XmlReader;

class AtomParser implements Parser
{
    public function supports(XmlReader $rawFeedData): bool
    {
        return $rawFeedData->value('feed.entry')->get() !== [];
    }

    /**
     * {@inheritDoc}
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection
    {
        return $rawFeedData->element('feed.entry')->collect()
            ->map(function (Element $item): FeedItem {
                /** @var array<string, Element> $content */
                $content = $item->getContent();

                return new FeedItem(
                    title: (string) $content['title']->getContent(),
                    url: (string) $content['link']->getAttribute('href'),
                    description: $this->parseContentOrSummary(($content['summary'] ?? $content['content'])),
                    publishedAt: Carbon::parse((string) $content['updated']->getContent()),
                );
            });
    }

    /**
     * Sometimes the feed author includes valid HTML in the content or summary field, instead of
     * being wrapped in CDATA, we need to parse these actual elements in that case so we get
     * all the actual content.
     */
    private function parseContentOrSummary(Element $element): string
    {
        $content = $element->getContent();
        if (is_string($content)) {
            return $content;
        }

        $stringParts = array_map(
            array: $content,
            callback: fn (Element $itemElement) => $this->parseContentOrSummary($itemElement),
        );

        return implode(' ', $stringParts);
    }
}
