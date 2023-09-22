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
        <button wire:click.once="delete({{ $endpoint->id }})" role="button" class="outline">{{__('Delete')}}</button>
    </span>
    <div x-show="$wire.displayEditForm">
        <livewire:endpoint-edit :endpoint="$endpoint" :key="$endpoint->id" x-show="$wire.displayEditForm" />
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('endpoint-deleted', (event) => {
                @this.dispatch('notify', { message: '{{__('Deleted successfully')}}' })
            });

            @this.on('endpoint-updated', (event) => {
                @this.dispatch('notify', { message: '{{__('Updated successfully')}}' })
            });
        });
    </script>
</li>