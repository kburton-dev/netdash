<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use App\Models\Feed;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * @return array<string, mixed>
     */
    public function with(): array
    {
        return [
            'feeds' => Feed::query()
                ->with('latestArticle')
                ->orderBy('title')
                ->get(),
        ];
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
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
