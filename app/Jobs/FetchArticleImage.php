<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class FetchArticleImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Article $article)
    {
        //
    }

    public function handle(): void
    {
        $responseBody = Http::get($this->article->url)->body();

        foreach (['og:image', 'twitter:image'] as $tag) {
            $matches = [];
            preg_match("/<meta property=\"{$tag}\" content=\"(?P<image>.+?)\"/i", $responseBody, $matches);

            if (isset($matches['image']) && $matches['image']) {
                $this->article->update(
                    Arr::only($matches, 'image')
                );

                logger()->debug("Fetched image for article ({$this->article->id}): {$this->article->title}");

                return;
            }
        }
    }
}
