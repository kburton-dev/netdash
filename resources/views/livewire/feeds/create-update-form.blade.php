<?php

use App\Actions\Feeds\SaveWithTags;
use App\Models\Feed;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public Feed $feed;

    public string $title;

    public string $url;

    /**
     * @var array<int>
     */
    public array $tagIds;

    public function mount(Feed $feed): void
    {
        $this->feed = $feed;

        $this->title = $this->feed->title;
        $this->url = $this->feed->url;
        $this->tagIds = $this->feed->tags->pluck('id')->toArray();
    }

    public function with(): array
    {
        return [
            'tags' => Tag::all(),
        ];
    }

    public function hydrate(): void
    {
        if (! $this->feed->exists) {
            $this->feed->user_id = auth()->id();
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'url' => ['required', 'string', 'url', Rule::unique(Feed::class)->ignore($this->feed->id)->whereNull('deleted_at')->where('user_id', auth()->id())],
            'tagIds' => ['array'],
            'tagIds.*' => ['required', 'int', Rule::exists(Tag::class, 'id')],
        ];
    }

    public function updateFeed(SaveWithTags $saveWithTags): void
    {
        $saveWithTags($this->feed, $this->validate());

        $this->dispatch('feed-saved', name: $this->feed->name);
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
            <x-input-label for="url" :value="__('URL')" />
            <x-text-input wire:model="url" id="url" name="url" type="text" class="mt-1 block w-full" required autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('url')" />
        </div>

        <div>
            <x-input-label for="tagIds" :value="__('Tags')" />
            <select wire:model="tagIds" id="tagIds" name="tagIds" multiple class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($tags as $tag)
                    <option @selected(in_array($tag->id, $tagIds)) value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('tagIds')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="feed-saved">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
