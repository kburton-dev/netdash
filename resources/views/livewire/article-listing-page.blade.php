<div class="py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between flex-wrap gap-2 items-center">
            <x-tags.link-filters :tags="$tags" :tagIds="$tagIds" />

            <div class="flex gap-2 items-center w-full sm:w-auto">
                <x-input-label for="search" class="text-gray-400" :value="__('Search')" />
                <x-text-input wire:model.live.debounce.500ms="search" id="search" name="search" type="search" class="mt-1 block w-full" autofocus autocomplete="off" />
            </div>
        </div>

        <div class="text-gray-400 mb-4">
            Showing {{ $articlesCount }} articles
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
