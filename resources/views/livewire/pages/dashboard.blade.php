<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

new #[Layout('layouts.app')] class extends Component
{
    private const LIMIT = 20;

    #[Url]
    public array $selectedTagIds = [];

    #[Url]
    public int $limit = self::LIMIT;
 
    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        $articleQuery = Article::query()
            ->when($this->selectedTagIds,
                fn (Builder $query) => $query->whereHasTags($this->selectedTagIds)
            );

        return [
            'tags' => Tag::query()->orderBy('name')->get(),
            'articleCount' => $articleQuery->count(),
            'articles' => $articleQuery->limit($this->limit)
                ->with('feed')
                ->orderByDesc('published_at')
                ->get()
        ];
    }

    public function clickedTag(int $id)
    {
        if (in_array($id, $this->selectedTagIds)) {
            $this->selectedTagIds = array_filter($this->selectedTagIds, fn ($tagId) => $tagId != $id);
        } else {
            $this->selectedTagIds[] = $id;
        }

        $this->limit = self::LIMIT;
    }

    public function loadMore()
    {
        $this->limit += 10;
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex justify-between">
            <ul class="flex gap-2">
                @foreach ($tags as $tag)
                <li>
                    <a href="#" wire:click.prevent="clickedTag({{ $tag->id }})" class="cursor-pointer {{ in_array($tag->id, $selectedTagIds) ? 'text-gray-900' : 'text-gray-400' }}">
                        {{ $tag->name }}
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="text-gray-400">
                Showing {{ $articles->count() }} articles
            </div>
        </div>

        <div class="space-y-4">
            @foreach ($articles as $article)
                <a class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-4 gap-4" href="{{ $article->url }}">
                    @if ($article->image)
                        <div class="col-span-1">
                            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full" />
                        </div>
                    @endif

                    <div class="col-span-3">
                        <div class="text-gray-900 text-lg mb-2 font-semibold">
                            {{ $article->title }}
                        </div>

                        <div class="text-gray-500 text-sm mb-2">
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

        @if ($articleCount > $limit)
            <div class="flex justify-center">
                <x-primary-button wire:click.prevent="loadMore">{{ __('Load More') }}</x-primary-button>
            </div>
        @endif
    </div>
</div>