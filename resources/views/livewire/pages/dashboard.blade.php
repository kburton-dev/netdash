<?php

use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Tag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

new #[Layout('layouts.app')] class extends Component
{
    private const LIMIT = 10;

    #[Url]
    public array $selectedTagIds = [];

    public int $limit = self::LIMIT;
 
    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        $articleQuery = Article::when($this->selectedTagIds,
            fn (Builder $query) => $query->whereHasTags($this->selectedTagIds)
        );

        return [
            'tags' => Tag::query()
                ->has('feeds.articles')
                ->orderBy('name')
                ->get(),
            'articlesCount' => $articleQuery->count(),
            'articles' => $articleQuery->limit($this->limit)
                ->with('feed')
                ->orderByDesc('published_at')
                ->get()
        ];
    }

    #[On('clickedTag')]
    public function clickedTag(int $id): void
    {
        $this->limit = self::LIMIT;
        $this->selectedTagIds = in_array($id, $this->selectedTagIds)
            ? array_filter($this->selectedTagIds, fn ($tagId) => $tagId != $id)
            : [...$this->selectedTagIds, $id];
    }

    public function loadMore(): void
    {
        $this->limit += 10;
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex justify-between">
            <x-tags.link-filters :tags="$tags" :selectedTagIds="$selectedTagIds" />

            <div class="text-gray-400">
                Showing {{ $articles->count() }} articles
            </div>
        </div>

        <div class="space-y-4">
            @if ($articles->isEmpty())
                <div class="my-12 text-center">
                    Oops, no results. Try selecting different tags to see articles.
                </div>
            @endif

            @foreach ($articles as $article)
                <a class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid md:grid-cols-4 gap-4" href="{{ $article->url }}">
                    @if ($article->image)
                        <div class="col-span-full md:col-span-1">
                            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full rounded-lg" />
                        </div>
                    @endif

                    <div class="col-span-full md:col-span-3">
                        <div class="text-gray-900 text-lg mb-2 font-semibold">
                            {{ $article->title }}
                        </div>

                        <div class="text-gray-500 text-sm mb-2 flex gap-2 items-center">
                            @if ($article->feed->type->value === 'rss' || $article->feed->type->value === 'atom')
                                <x-icons.rss class="w-4 h-4"/>
                            @endif

                            {{ $article->published_at->diffForHumans() }}
                            |
                            {{ $article->feed->title }}
                            |
                            {{ $article->feed->hostname}}
                        </div>

                        <div class="[&>p]:mb-4 [&>hr]:my-4 line-clamp-4">
                            {!! nl2br(trim(strip_tags($article->content, ['code', 'strong']))) !!}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($articlesCount > $limit)
            <div class="flex justify-center">
                <x-primary-button wire:click.prevent="loadMore">{{ __('Load More') }}</x-primary-button>
            </div>
        @endif
    </div>
</div>
