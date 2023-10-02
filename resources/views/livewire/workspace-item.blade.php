<div>
    <h2>
        {{ $workspace->name }}
    </h2>

    <div>
        <p>
            {{ __('Description:') }}
        </p>
        <p>
            {{ $workspace->description ?? __('No description') }}
        </p>
    </div>


    <div>
        {{ __('Created at:') }}
        {{ $workspace->created_at->format('Y-m-d H:i:s') }}
    </div>

    <div>
        {{ __('Authorisation:') }}
        {{ $workspace->pivot->role }}
    </div>

    <a href="{{ route('targets-monitored.index', ['workspaceId' => $workspace->id]) }}">
        {{ __('Show') }}
    </a>

    @if ($workspace->pivot->role === 'owner')
        <div class="grid">
            <button @click.once="
                @this.dispatch('workspace-to-delete', {workspaceId:{{ $workspace->id }}})
            ">
                {{ __('Delete') }}
            </button>
            <button @click="$wire.openEdit = !$wire.openEdit">
                {{ __('Edit') }}
            </button>
        </div>

        <div x-show="$wire.openEdit">
            <form wire:submit.prevent="update">
                <label for="name">
                    {{ __('Name:') }}
                </label>
                <input type="text" id="name" wire:model="editName">

                <label for="description">
                    {{ __('Description:') }}
                </label>
                <textarea id="description" wire:model="editDescription"></textarea>

                <button type="submit">
                    {{ __('Update') }}
                </button>
            </form>
        </div>
        <script>
            document.addEventListener('livewire:initialized', function () {
                @this.on('workspace-updated', () => {
                    @this.dispatch('notify', { message: '{{__('New workspace updated successfully')}}' })
                });
            })
        </script>
    @endif
</div>
