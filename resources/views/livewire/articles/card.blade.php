<?php

use App\Models\Article;
use Livewire\Volt\Component;

new class extends Component
{
    public Article $article;

    public function favourite(): void
    {
        save_model($this->article, [
            'favourited_at' => $this->article->favourited_at ? null : now(),
        ]);
    }

    public function archive(): void
    {
        delete_model($this->article);

        $this->dispatch('article-deleted');
    }
}

?>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid md:grid-cols-4 gap-4">
    @if ($article->image)
        <a href="{{ $article->url }}" class="bloc kcol-span-full md:col-span-1">
            <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full rounded-lg" />
        </a>
    @endif

    <div class="col-span-full {{ $article->image ? 'md:col-span-3' : '' }}">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ $article->url }}" class="block flex-grow text-gray-900 text-lg font-semibold">
                {{ $article->title }}
            </a>

            <div class="flex gap-2">
                <button wire:click.prevent="favourite">
                    @if ($article->favourited_at)
                        <x-icons.star-filled class="w-5 h-5 text-yellow-500" />
                    @else
                        <x-icons.star class="w-5 h-5 text-yellow-500" />
                    @endif
                </button>
                <button wire:click.prevent="archive">
                    <x-icons.trash class="w-5 h-5 text-gray-400" />
                </button>
            </div>
        </div>

        <a class="text-gray-500 text-sm mb-2 flex gap-2 items-center">
            <x-icons.rss class="w-4 h-4"/>

            {{ $article->published_at->diffForHumans() }}
            |
            {{ $article->feed->title }}
            |
            {{ $article->feed->hostname}}
        </a>

        <a href="{{ $article->url }}" class="[&>p]:mb-4 [&>hr]:my-4 line-clamp-4">
            {!! nl2br(trim(strip_tags($article->content, ['code', 'strong']))) !!}
        </a>
    </div>
</div>