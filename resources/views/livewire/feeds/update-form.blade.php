<?php

use App\Models\Feed;
use App\Models\Tag;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Feeds\FeedType;

new class extends Component
{
    public Feed $feed;

    public string $title;

    public FeedType $type;

    public string $url;

    /**
     * @var array<int>
     */
    public array $tagIds;

    public function mount(int $feedId): void
    {
        $this->feed = Feed::query()
            ->whereKey($feedId)
            ->with('tags')
            ->firstOrFail();

        $this->title = $this->feed->title;
        $this->type = $this->feed->type;
        $this->url = $this->feed->url;
        $this->tagIds = $this->feed->tags->pluck('id')->toArray();
    }

    public function with(): array
    {
        return [
            'tags' => Tag::all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'type' => ['required', Rule::enum(FeedType::class)],
            'url' => ['required', 'string', 'url', Rule::unique(Feed::class)->ignore($this->feed->id)],
            'tagIds' => ['array'],
            'tagIds.*' => ['required', 'int', Rule::exists(Tag::class, 'id')],
        ];
    }

    public function updateFeed(): void
    {
        DB::transaction(
            function (): void {
                $validated = $this->validate();

                $this->feed->tags()->sync(
                    Arr::pull($validated, 'tagIds', [])
                );

                save_model($this->feed, $validated);
        });

        $this->dispatch('feed-updated', name: $this->feed->name);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Feed Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update the basic feed information.") }}
        </p>
    </header>

    <form wire:submit="updateFeed" class="mt-6 space-y-6">
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input wire:model="title" id="title" name="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div>
            <x-input-label for="type" :value="__('Type')" />
            <select wire:model="type" id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach (FeedType::cases() as $feedType)
                    <option value="{{ $feedType->value }}">{{ $feedType->value }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />
        </div>

        <div>
            <x-input-label for="url" :value="__('URL')" />
            <x-text-input wire:model="url" id="url" name="url" type="text" class="mt-1 block w-full" required autofocus autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('url')" />
        </div>

        <div>
            <x-input-label for="tagIds" :value="__('Tags')" />
            <select wire:model="tagIds" id="tagIds" name="tagIds" multiple class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($tags as $tag)
                    <option @selected($feed->tags->pluck('id')->contains($tag->id)) value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('tagIds')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="feed-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
