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
        <button x-on:click="$wire.displayEditForm = !$wire.displayEditForm" >{{__('Edit')}}</button>
        <button wire:click="delete({{ $endpoint->id }})" role="button" class="outline">{{__('Delete')}}</button>
    </span>
    <div x-show="$wire.displayEditForm">
        <livewire:endpoint-edit :endpoint="$endpoint" :key="$endpoint->id" x-show="$wire.displayEditForm"/>
    </div>
</li>
