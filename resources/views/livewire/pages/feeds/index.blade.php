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
    public array $selectedTagIds = [];

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
                ->when($this->selectedTagIds,
                    fn (Builder $query) => $query->whereHas('tags',
                        fn (Builder $query) => $query->whereIn('id', $this->selectedTagIds)
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
        $this->selectedTagIds = in_array($id, $this->selectedTagIds)
            ? array_filter($this->selectedTagIds, fn ($tagId) => $tagId != $id)
            : [...$this->selectedTagIds, $id];
    }

    public function addNew(): void
    {
        $this->redirect(route('feeds.add'));
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="flex gap-4 items-center">
            <div class="flex-grow">
                <x-tags.link-filters :tags="$tags" :selectedTagIds="$selectedTagIds" />
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
