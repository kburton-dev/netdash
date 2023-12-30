<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Saloon\XmlWrangler\XmlReader;

class CompatibleFeedParserNotFoundException extends Exception
{
    public function __construct(public XmlReader $reader)
    {
        parent::__construct('No compatible parser found for this feed.');
    }
}
