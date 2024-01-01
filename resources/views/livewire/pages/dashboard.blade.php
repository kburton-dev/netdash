<?php

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    private const LIMIT = 10;

    #[Url]
    public array $tagIds = [];

    #[Url]
    public bool $archived = false;

    #[Url]
    public bool $favourites = false;

    #[Url]
    public string $search = '';

    public int $limit = self::LIMIT;

    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        $articleQuery = Article::query()
            ->forUserId(auth()->id())
            ->when($this->archived, fn (Builder $query) => $query->withTrashed())
            ->when($this->favourites, fn (Builder $query) => $query->whereNotNull('favourited_at'))
            ->when($this->search, fn (Builder $query) => $query->where('title', 'like', "%{$this->search}%"))
            ->when($this->tagIds,
                fn (Builder $query) => $query->whereHasTags($this->tagIds)
            );

        return [
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
        ];
    }

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
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="flex justify-between flex-wrap gap-2">
            <x-tags.link-filters :tags="$tags" :tagIds="$tagIds" />

            <div class="text-gray-400">
                Showing {{ $articles->count() }} articles
            </div>

            <div class="w-full flex gap-2 flex-wrap">
                <div class="flex gap-2 items-center">
                    <x-input-label for="archived" class="text-gray-400" :value="__('Show Archived?')" />
                    <input wire:model.change="archived" id="archived" type="checkbox" />
                </div>
    
                <div class="flex gap-2 items-center">
                    <x-input-label for="favourites" class="text-gray-400" :value="__('Only Favourites?')" />
                    <input wire:model.change="favourites" id="favourites" type="checkbox" />
                </div>

                <div class="flex-grow"></div>

                <div class="flex gap-2 items-center">
                    <x-input-label for="search" class="text-gray-400" :value="__('Search')" />
                    <x-text-input wire:model.live.debounce.500ms="search" id="search" name="search" type="text" class="mt-1 block w-full" autofocus />
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @if ($articles->isEmpty())
                <div class="my-12 text-center">
                    Oops, no results. Try selecting different tags to see articles.
                </div>
            @endif

            @foreach ($articles as $article)
                <livewire:articles.card :$article :key="$article->id" @article-deleted="$refresh" />
            @endforeach
        </div>

        @if ($articlesCount > $limit)
            <div class="flex justify-center">
                <x-primary-button wire:click.prevent="loadMore">{{ __('Load More') }}</x-primary-button>
            </div>
        @endif
    </div>
</div>
