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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Saloon\XmlWrangler\XmlReader;

class FetchFeedItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Feed $feed)
    {
    }

    public function handle(ParserFactory $parserFactory): void
    {
        $dataReader = XmlReader::fromString(
            Http::get($this->feed->url)->body()
        );

        $parserFactory->parse($dataReader)
            ->sortByDesc('publishedAt')
            ->take(10)
            ->each(fn (FeedItem $item) => $this->saveItem($this->feed->id, $item));

        save_model($this->feed, [
            'last_fetch' => now(),
        ]);
    }

    private function saveItem(int $feedId, FeedItem $item): void
    {
        $article = Article::query()
            ->withTrashed()
            ->firstOrNew([
                'feed_id' => $feedId,
                'url' => $item->url,
            ], [
                'published_at' => $item->publishedAt,
            ]);

        if ($article->exists && $article->created_at->lessThan(now()->subDays(3))) {
            return; // We don't want to update articles older than 3 days
        }

        DB::transaction(function () use ($article, $item): void {
            save_model($article, [
                'title' => $item->title,
                'image' => $this->determineImageUrl($article, $item),
                'content' => $this->removeImagesFromDescription($item->description),
            ]);
        });

        // logger()->info("Saved article ({$article->id}): ".$article->title);
    }

    private function removeImagesFromDescription(string $description): string
    {
        return preg_replace('/<img[^>]+>/i', '', $description);
    }

    private function determineImageUrl(Article $article, FeedItem $item): ?string
    {
        if ($article->exists) {
            return $article->image;
        }

        if ($item->featuredImage !== null) {
            return $item->featuredImage;
        }

        $matches = [];
        preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $item->description, $matches);

        if (isset($matches['src']) && $matches['src']) {
            return $matches['src'];
        }

        DB::afterCommit(function () use ($article) { // Done after commit to ensure the article has an ID by the time it is serialized, and title for logging.
            FetchArticleImage::dispatch($article);

            logger()->debug("Asynchronously fetching image for article ({$article->id}): {$article->title}");
        });

        return null;
    }
}
