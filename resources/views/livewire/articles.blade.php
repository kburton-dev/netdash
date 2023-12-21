<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Article;
use Illuminate\Support\Collection;

new class extends Component
{
    /** @var Collection<Article> */
    public Collection $articles;
 
    public function mount()
    {
        $this->articles = Article::query()
            ->orderByDesc('published_at')
            ->limit(30)
            ->get();
    }
}; ?>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
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
                    </div>

                    <div class="[&>p]:mb-4 [&>hr]:my-4 line-clamp-4">
                        {!! nl2br(trim(strip_tags($article->content, ['code', 'strong']))) !!}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
