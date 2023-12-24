<?php declare(strict_types=1);

namespace App\Feeds;
use Saloon\XmlWrangler\XmlReader;

interface Parser
{
    /**
     * @return \Illuminate\Support\Collection<array{title: string, url: string, description: string, published_at: \Carbon\CarbonInterface}>
     */
    public function parse(XmlReader $rawFeedData): \Illuminate\Support\Collection;
}