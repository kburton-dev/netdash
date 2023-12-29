<?php

use App\Jobs\FetchArticleImage;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Support\Facades\Http;

it('fetches an article image', function () {
    $feed = Feed::factory()->create();
    $article = Article::factory()->create([
        'feed_id' => $feed->id,
        'image' => null,
        'url' => 'https://blog.jetbrains.com/pycharm/2023/12/django-vs-fastapi-which-is-the-best-python-web-framework',
    ]);

    Http::fake([
        $article->url => Http::response(file_get_contents(__DIR__.'/Article.html')),
    ]);

    (new FetchArticleImage($article))->handle();

    expect($article->image)->toBe('https://blog.jetbrains.com/wp-content/uploads/2019/01/PyCharm_featured.png');
});
