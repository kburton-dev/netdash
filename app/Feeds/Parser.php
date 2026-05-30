<?php

declare(strict_types=1);

namespace App\Feeds;

use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

interface Parser
{
    public function supports(XmlReader $rawFeedData): bool;

    /**
     * @return Collection<array-key, FeedItem>
     */
    public function parse(XmlReader $rawFeedData): Collection;
}
