<?php

use App\Models\Feed;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    #[Url]
    public array $tagIds = [];

    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        return [
            'tags' => Tag::query()
                ->has('feeds')
                ->orderBy('name')
                ->get(),
            'feeds' => Feed::query()
                ->where('user_id', auth()->id())
                ->when($this->tagIds,
                    fn (Builder $query) => $query->whereHas('tags',
                        fn (Builder $query) => $query->whereIn('id', $this->tagIds)
                    )
                )
                ->with('latestArticle')
                ->orderBy('title')
                ->get(),
        ];
    }

    #[On('clickedTag')]
    public function clickedTag(int $id): void
    {
        $this->tagIds = in_array($id, $this->tagIds)
            ? array_filter($this->tagIds, fn ($tagId) => $tagId != $id)
            : [...$this->tagIds, $id];
    }

    public function addNew(): void
    {
        $this->redirect(route('feeds.add'));
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="flex gap-4 items-center flex-wrap">
            <div class="flex-grow">
                <x-tags.link-filters :tags="$tags" :tagIds="$tagIds" />
            </div>

            <div class="text-gray-400">
                Showing {{ $feeds->count() }} feeds
            </div>

            <x-primary-button wire:click.prevent="addNew">{{ __('Add New') }}</x-primary-button>
        </div>

        <div class="space-y-4">
            @foreach ($feeds as $feed)
                <a class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" href="{{ route('feeds.view', $feed) }}" wire:navigate>
                    <div class="text-gray-900 text-lg mb-2 font-semibold">
                        {{ $feed->title }}
                    </div>

                    <div class="text-gray-500 text-sm mb-2">
                        {{ $feed->latestArticle?->published_at->diffForHumans() ?? 'No posts' }}
                        |
                        {{ $feed->hostname }}
                        |
                        {{ $feed->url }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
