<?php

declare(strict_types=1);

namespace App\Feeds;

use App\Exceptions\CompatibleFeedParserNotFoundException;
use Saloon\XmlWrangler\XmlReader;

class ParserFactory
{
    /**
     * @throws CompatibleFeedParserNotFoundException
     */
    public static function create(XmlReader $reader): Parser
    {
        /** @var \App\Feeds\Parser[] $parsers */
        $parsers = app()->tagged('feedParsers');

        foreach ($parsers as $parser) {
            if ($parser->supports($reader)) {
                return $parser;
            }
        }

        throw new CompatibleFeedParserNotFoundException($reader);
    }

    /**
     * @return \Illuminate\Support\Collection<array-key, FeedItem>
     */
    public static function parse(XmlReader $reader): \Illuminate\Support\Collection
    {
        return self::create($reader)->parse($reader);
    }
}
