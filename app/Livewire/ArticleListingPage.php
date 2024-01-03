<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class ArticleListingPage extends Component
{
    private const LIMIT = 10;

    #[Url]
    public array $tagIds = [];

    #[Url]
    public string $search = '';

    public int $limit = self::LIMIT;

    #[On('clickedTag')]
    public function clickedTag(int $id): void
    {
        $this->limit = self::LIMIT;
        $this->tagIds = in_array($id, $this->tagIds)
            ? array_filter($this->tagIds, fn ($tagId) => $tagId != $id)
            : [...$this->tagIds, $id];
    }

    public function loadMore(): void
    {
        $this->limit += 10;
    }

    public function render()
    {
        $articleQuery = Article::query()
            ->forUserId(auth()->id())
            ->when($this->search, fn (Builder $query) => $query->where('title', 'like', "%{$this->search}%"))
            ->when($this->tagIds,
                fn (Builder $query) => $query->whereHasTags($this->tagIds)
            );

        $this->articleQueryModified($articleQuery);

        return view('livewire.article-listing-page', [
            'tags' => Tag::query()
                ->has('feeds.articles')
                ->orderBy('name')
                ->get(),
            'articlesCount' => $articleQuery->count(),
            'articles' => $articleQuery->with('feed')
                ->limit($this->limit)
                ->orderByDesc('published_at')
                ->when(blank($this->search), fn (Builder $query) => $query->useIndex('articles_published_at_index'))
                ->when(filled($this->search), fn (Builder $query) => $query->useIndex('articles_feed_id_title_index'))
                ->get(),
        ]);
    }

    protected function articleQueryModified(Builder $builder): void
    {
    }
}
