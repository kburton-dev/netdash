<?php declare(strict_types=1);

namespace App\Feeds;
use Saloon\XmlWrangler\XmlReader;

interface Parser
{
    /**
     * @return list<array{title: string, url: string, description: string, published_at: \Carbon\CarbonInterface}>
     */
    public function parse(XmlReader $rawFeedData): array;
}