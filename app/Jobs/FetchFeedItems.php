<?php

namespace App\Jobs;

use App\Feeds\ParserFactory;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
            ->each(fn (array $item) => $this->saveItem($this->feed->id, $item));

        $this->feed->last_fetch = now();
        $this->feed->save();
    }

    /**
     * @param array{title: string, url: string, description: string, published_at: \Carbon\CarbonInterface} $item
     */
    private function saveItem(int $feedId, array $item): void
    {
        $article = Article::query()->firstOrNew([
            'feed_id' => $feedId,
            'url' => $item['url'],
        ]);

        $article->fill([
            'title' => $item['title'],
            'image' => $this->getImageUrl($item),
            'content' => $this->removeImagesFromDescription($item['description']),
            'published_at' => $item['published_at'],
        ]);

        throw_unless($article->save(), new \LogicException('Failed to save article'));

        logger()->info("Saved article ({$article->id}): " . $article->title);
    }

    private function removeImagesFromDescription(string $description): string
    {
        return preg_replace('/<img[^>]+>/i', '', $description);
    }

    /**
     * @param array{title: string, url: string, description: string, published_at: \Carbon\CarbonInterface} $item
     */
    private function getImageUrl(array $item): ?string
    {
        $matches = [];
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item['description'], $matches);

        if (isset($matches['src']) && $matches['src']) {
            return $matches['src'];
        }

        $responseBody = Http::get($item['url'])->body();

        foreach (['og:image', 'twitter:image'] as $tag) {
            $matches = [];
            preg_match("/<meta property=\"{$tag}\" content=\"(?P<image>.+?)\"/i", $responseBody, $matches);

            if (isset($matches['image']) && $matches['image']) {
                return $matches['image'];
            }
        }

        return null;
    }
}
