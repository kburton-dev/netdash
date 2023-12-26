<?php

namespace App\Jobs;

use App\Feeds\FeedItem;
use App\Feeds\ParserFactory;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Saloon\XmlWrangler\XmlReader;

class FetchFeedItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Feed $feed)
    {
    }

    public function handle(ParserFactory $parser): void
    {
        $dataReader = XmlReader::fromString(
            Http::get($this->feed->url)->body()
        );

        $parser->create($this->feed->type)
            ->parse($dataReader)
            ->take(10)
            ->each(fn (FeedItem $item) => $this->saveItem($this->feed->id, $item));

        save_model($this->feed, [
            'last_fetch' => now(),
        ]);
    }

    private function saveItem(int $feedId, FeedItem $item): void
    {
        $article = Article::query()->firstOrNew([
            'feed_id' => $feedId,
            'url' => $item->url,
        ]);

        save_model($article, [
            'title' => $item->title,
            'image' => $this->determineImageUrl($article, $item),
            'content' => $this->removeImagesFromDescription($item->description),
            'published_at' => $item->publishedAt,
        ]);

        logger()->info("Saved article ({$article->id}): ".$article->title);
    }

    private function removeImagesFromDescription(string $description): string
    {
        return preg_replace('/<img[^>]+>/i', '', $description);
    }

    private function determineImageUrl(Article $article, FeedItem $item): ?string
    {
        if ($article->image !== null) {
            return $article->image;
        }

        $matches = [];
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item->description, $matches);

        if (isset($matches['src']) && $matches['src']) {
            return $this->prependHostIfNeeded($matches['src']);
        }

        $responseBody = Http::get($item->url)->body();

        foreach (['og:image', 'twitter:image'] as $tag) {
            $matches = [];
            preg_match("/<meta property=\"{$tag}\" content=\"(?P<image>.+?)\"/i", $responseBody, $matches);

            if (isset($matches['image']) && $matches['image']) {
                return $this->prependHostIfNeeded($matches['image']);
            }
        }

        return null;
    }

    /**
     * Some images are relative to the feed URL, so we need to prepend the host.
     */
    private function prependHostIfNeeded(string $src): string
    {
        $host = parse_url($src, PHP_URL_HOST);

        if ($host !== null) {
            return $src;
        }

        $feedUrl = parse_url($this->feed->url);

        return "{$feedUrl['scheme']}://{$feedUrl['host']}{$src}"; // @phpstan-ignore-line - the URL should certainly have a schema and host at this point.
    }
}
