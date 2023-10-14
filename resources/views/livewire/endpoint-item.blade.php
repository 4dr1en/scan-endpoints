<li class="endpoint">
    <span class="endpoint__status-icon endpoint__status-icon--{{ $endpointStatus }}">
        @if ($endpointStatus == 'good')
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

    <b class="endpoint__name endpoint__name--{{ $endpoint->name ? 'exist' : 'missing' }}">{{ $endpoint->name }}</b>

    <span class="endpoint__target">
        @if ($endpoint->protocol)
            <span class="endpoint__protocol">{{ $endpoint->protocol }}://</span>
        @endif
        <span class="endpoint__path">{{ $endpoint->path }}</span>
        @if ($endpoint->port)
            <span class="endpoint__port">:{{ $endpoint->port }}</span>
        @endif
    </span>

    <span class="endpoint__actions">
        {{-- The details are called only once, and then toggled in order to avoid
            unnecessary requests to the server. --}}
        @if ($detailsDownloaded == false)
            <button wire:click.once="toggleDetails" aria-label="{{ __('Show details') }}" role="switch"
                aria-checked="false" title="{{ __('Show details') }}">
                <span class="material-symbols-outlined">
                    info
                </span>
            </button>
        @else
            <button @click="$wire.displayDetails = !$wire.displayDetails; $wire.displayEditForm=false"
                :aria-label="$wire.displayDetails ? '{{ __('Hide details') }}' : '{{ __('Show details') }}'"
                role="switch" :aria-checked="$wire.displayDetails"
                :title="$wire.displayDetails ? '{{ __('Hide details') }}' : '{{ __('Show details') }}'"
                class="endpoint__display-btn">
                <span class="material-symbols-outlined" x-show="!$wire.displayDetails" x-transition.duration.300ms>
                    info
                </span>
                <span class="material-symbols-outlined" x-show="$wire.displayDetails" x-transition.duration.300ms>
                    cancel
                </span>
            </button>
        @endif
        {{-- The edit form is toggled with AlpineJS, there is no need to call
            the server only to toggle a form. Therefor, when the user submits
            the form, we call the server to update the endpoint. --}}
        <button @click="$wire.displayEditForm = !$wire.displayEditForm; $wire.displayDetails=false"
            :aria-label="$wire.displayEditForm ? '{{ __('Cancel edit') }}' : '{{ __('Edit') }}'" role="switch"
            :aria-checked="$wire.displayEditForm"
            :title="$wire.displayEditForm ? '{{ __('Cancel edit') }}' : '{{ __('Edit') }}'"
            class="endpoint__edite-btn">
            <span class="material-symbols-outlined" x-show="!$wire.displayEditForm" x-transition.duration.300ms>
                edit
            </span>
            <span class="material-symbols-outlined" x-show="$wire.displayEditForm" x-transition.duration.300ms>
                cancel
            </span>
        </button>
        <button wire:click.once="delete({{ $endpoint->id }})" class="endpoint__delete-btn"
            aria-label="{{ __('Delete') }}" title="{{ __('Delete') }}">
            <span class="material-symbols-outlined">delete_forever</span>
        </button>
    </span>
    <div x-show="$wire.displayEditForm" class="endpoint__edite-form" x-transition.duration.200ms>
        <livewire:endpoint-edit :$endpoint :key="$endpoint->id" />
    </div>

    {{-- The details section can be toggle via Livewire and by AlpineJS. --}}
    @if ($displayDetails)
        <div x-show="$wire.displayDetails" class="endpoint__details" x-transition.duration.200ms>
            <livewire:endpoint-details :$endpoint :$endpointStatus :key="'details' . $endpoint->id" />
        </div>
    @endif

    <script>
        {{--
            When a Target is updated or deleted, we dispatch the local
            event across the frontend to notify the user.
        --}}
        document.addEventListener('livewire:initialized', () => {
            @this.on('endpoint-deleted', (event) => {
                @this.dispatch('notify', {
                    message: '{{ __('Deleted successfully') }}'
                })
            });

            @this.on('endpoint-updated', (event) => {
                @this.dispatch('notify', {
                    message: '{{ __('Updated successfully') }}'
                })
            });
        });
    </script>
</li>
