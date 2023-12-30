<?php

declare(strict_types=1);

namespace App\Feeds;

use Saloon\XmlWrangler\XmlReader;

interface Parser
{
    public function supports(XmlReader $rawFeedData): bool;

    /**
     * @return \Illuminate\Support\Collection<array-key, FeedItem>
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection;
}
