<?php

declare(strict_types=1);

namespace App\Feeds;

use Carbon\CarbonInterface;

readonly class FeedItem
{
    public function __construct(
        public string $title,
        public string $url,
        public string $description,
        public CarbonInterface $publishedAt,
    ) {
        //
    }
}
