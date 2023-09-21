<li>
    <b>{{ $endpoint->name }}</b>
    -
    <span>
        @if($endpoint->protocol)
        {{ $endpoint->protocol }}://
        @endif
        {{ $endpoint->path }}
        @if($endpoint->port)
        <em>:{{ $endpoint->port }}</em>
        @endif
    </span>

    <span class="actions grid">
        <button @click="$wire.displayEditForm = !$wire.displayEditForm" x-text="$wire.displayEditForm ? '{{__('Cancel edit')}}' : '{{__('Edit')}}'"></button>
        <button wire:click="delete({{ $endpoint->id }})" role="button" class="outline">{{__('Delete')}}</button>
    </span>
    <div x-show="$wire.displayEditForm">
        <livewire:endpoint-edit :endpoint="$endpoint" :key="$endpoint->id" x-show="$wire.displayEditForm" />
    </div>

    @if($flash)
    @teleport('body')
    <dialog x-data="{ open: true }" x-show="open" :open="open" @keydown.escape="open = false">
        <article>
            <a href="#close" aria-label="Close" class="close" @click="open = false"></a>
            <p>
                {{ $flash }}
            </p>
        </article>
    </dialog>
    @endteleport
    @endif
</li>