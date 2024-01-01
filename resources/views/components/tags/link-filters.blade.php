<ul class="flex gap-2">
    @foreach ($tags as $tag)
    <li>
        <a
            href="#"
            wire:click.prevent="$dispatch('clickedTag', { id: {{ $tag->id }} })"
            class="cursor-pointer hover:text-gray-900 hover:underline {{ in_array($tag->id, $tagIds) ? 'text-gray-900' : 'text-gray-400' }}"
        >
            {{ $tag->name }}
        </a>
    </li>
    @endforeach
</ul>