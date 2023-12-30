<?php

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    /**
     * @var list<array{id: int, name: string}>
     */
    public array $tags = [];

    /**
     * @var list<int>
     */
    public array $deleteTagIds = [];

    public function mount(): void
    {
        $this->tags = Tag::query()
            ->orderBy('name')
            ->get()
            ->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])
            ->toArray();
    }

    public function addTag(): void
    {
        $this->tags[] = [
            'id' => null,
            'name' => '',
        ];
    }

    public function removeTag(int $index): void
    {
        $removed = array_splice($this->tags, $index, 1);

        if (isset($removed[0]['id'])) {
            $this->deleteTagIds[] = $removed[0]['id'];
        }
    }

    public function rules()
    {
        return [
            'tags.*.name' => ['required', 'string', 'max:255'],
        ];
    }

    public function updateTags(): void
    {
        $this->validate();

        foreach ($this->tags as $tag) {
            isset($tag['id'])
                ? DB::table('tags')->where('id', $tag['id'])->update(['name' => $tag['name']])
                : DB::table('tags')->insert(['name' => $tag['name']]);
        }

        DB::table('tags')->whereIn('id', $this->deleteTagIds)->delete();

        $this->dispatch('tags-saved');
    }
}; ?>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="space-y-4">
            <form wire:submit="updateTags" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <x-input-label :value="__('Tags')" />

                @foreach ($tags as $i => $tag)
                    <div class="mb-4 flex gap-2 items-center">
                        <div class="flex-grow">
                            <x-text-input wire:model="tags.{{ $i }}.name" name="tags[{{$i}}][name]" type="text" class="mt-1 block w-full" required autofocus autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->get('tags[{{$i}}][name]')" />
                        </div>

                        <button wire:click="removeTag({{ $i }})" type="button">
                            <x-icons.x-mark class="w-5 h-5" />
                        </button>
                    </div>
                @endforeach

                <x-primary-button wire:click="addTag" type="button" class="mb-4">{{ __('+ Add Tag') }}</x-primary-button>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>

                    <x-action-message class="me-3" on="tags-saved">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>
    </div>
</div>
