<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('pages.tags.index');

    $component->assertSee('');
});
