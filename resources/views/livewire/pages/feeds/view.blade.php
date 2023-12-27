<?php

use Livewire\Volt\Component;
use App\Models\Feed;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component
{
    public Feed $feed;
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <livewire:feeds.create-update-form :feed="$feed" />
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <livewire:feeds.delete-form :feed="$feed" />
            </div>
        </div>
    </div>
</div>
