<ul class="flex gap-2">
    @foreach ($tags as $tag)
    <li>
        <a
            href="#"
            wire:click.prevent="$dispatch('clickedTag', { id: {{ $tag->id }} })"
            class="cursor-pointer text-sm inline-block py-1 px-2 rounded-full text-pink-600 uppercase last:mr-0 mr-1 border {{ in_array($tag->id, $tagIds) ? 'bg-pink-200' : 'border-pink-200' }}"
        >
            {{ $tag->name }}
        </a>
    </li>
    @endforeach
</ul>
