<?php

declare(strict_types=1);

namespace App\Feeds;

enum FeedType: string
{
    case RSS = 'rss';
    case ATOM = 'atom';
}
