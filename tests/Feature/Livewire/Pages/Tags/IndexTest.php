<?php

use App\Models\Tag;
use Livewire\Volt\Volt;

it('can add update delete', function () {
    Tag::factory()
        ->sequence(
            ['name' => 'phpp'],
            ['name' => 'laravel'],
            ['name' => 'postgres'],
        )
        ->count(3)
        ->create();

    $component = Volt::test('pages.tags.index')
        ->set('tags.0.name', 'php')
        ->call('removeTag', 1)
        ->call('addTag')
        ->set('tags.2.name', 'mysql')
        ->call('updateTags')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('tags', 3);
    $this->assertDatabaseHas('tags', ['name' => 'php']);
    $this->assertDatabaseHas('tags', ['name' => 'mysql']);
    $this->assertDatabaseHas('tags', ['name' => 'postgres']);
    $this->assertDatabaseMissing('tags', ['name' => 'laravel']);
});
