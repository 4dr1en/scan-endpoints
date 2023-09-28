<li>
    <span>
        @if($endpointStatus == 'good')
        <span class="material-symbols-outlined" style="color: green" title="{{ __('Online') }}">
            check
        </span>
        @elseif($endpointStatus == 'slow' || $endpointStatus == 'warning')
        <span class="material-symbols-outlined" style="color: orange" title="{{ __('Warning') }}">
            warning
        </span>
        @elseif($endpointStatus == 'down')
        <span class="material-symbols-outlined" style="color: red" title="{{ __('Down') }}">
            dangerous
        </span>
        @elseif($endpointStatus == 'unknown')
        <span class="material-symbols-outlined" style="color: grey" title="{{ __('Unknown') }}">
            question_mark
        </span>
        @endif
    </span>

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
        {{-- The details are called only once, and then toggled in order to avoid
            unnecessary requests to the server. --}}
        @if($detailsDownloaded == false)
            <button wire:click.once="toggleDetails">
                {{ __('Show details') }}
            </button>
        @else
            <button
                @click="$wire.displayDetails = !$wire.displayDetails; $wire.displayEditForm=false"
                x-text="$wire.displayDetails ? '{{__('Hide details')}}' : '{{__('Show details')}}'"
            ></button>
        @endif
        {{-- The edit form is toggled with AlpineJS, there is no need to call
            the server only to toggle a form. Therefor, when the user submits
            the form, we call the server to update the endpoint. --}}
        <button @click="$wire.displayEditForm = !$wire.displayEditForm; $wire.displayDetails=false" x-text="$wire.displayEditForm ? '{{__('Cancel edit')}}' : '{{__('Edit')}}'"></button>
        <button wire:click.once="delete({{ $endpoint->id }})" role="button" class="outline">{{__('Delete')}}</button>
    </span>
    <div x-show="$wire.displayEditForm">
        <livewire:endpoint-edit :$endpoint :key="$endpoint->id"/>
    </div>

    {{-- The details section can be toggle via Livewire and by AlpineJS. --}}
    @if($displayDetails)
    <div x-show="$wire.displayDetails" >
        <livewire:endpoint-details :$endpoint :$endpointStatus :key="'details' . $endpoint->id"/>
    </div>
    @endif

    <script>
        {{--
            When a Target is updated or deleted, we dispatch the local
            event across the frontend to notify the user.
        --}}
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