<?php

declare(strict_types=1);

namespace App\Feeds;

class ParserFactory
{
    public static function create(FeedType $feedType): Parser
    {
        return match ($feedType) {
            FeedType::RSS => new RssParser,
            FeedType::ATOM => new AtomParser,
        };
    }
}
