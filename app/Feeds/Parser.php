<?php

declare(strict_types=1);

namespace App\Feeds;

use Saloon\XmlWrangler\XmlReader;

interface Parser
{
    /**
     * @return \Illuminate\Support\Collection<array-key, FeedItem>
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection;
}
