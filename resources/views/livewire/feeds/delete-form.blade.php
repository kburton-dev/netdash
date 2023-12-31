<?php

use Livewire\Volt\Component;
use App\Models\Feed;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    public Feed $feed;

    public function deleteFeed(): void
    {
        DB::transaction(fn () => delete_model($this->feed));

        $this->redirect('/feeds', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Feed') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Deleting a feed will mark it as soft-deleted in the database.') }}
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-feed-deletion')">
        {{ __('Delete Feed') }}
    </x-danger-button>

    <x-modal name="confirm-feed-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteFeed" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Deleting a feed will mark it as soft-deleted in the database, it will be recoverable.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Feed') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
