<div class="workspace">
    <div>
        <h2 class="workspace__title">
            {{ $workspace->name }}
        </h2>

        <div class="workspace__description">
            <b>
                {{ __('Description:') }}
            </b>
            <p>
                {{ $workspace->description ?? __('No description') }}
            </p>
        </div>


        <div class="workspace__date-creation">
            <b>
                {{ __('Created at:') }}
            </b>
            <p>
                {{ $workspace->created_at->format('Y-m-d H:i:s') }}
            </p>
        </div>

        <div class="workspace__authorisation">
            <b>
                {{ __('Authorisation:') }}
            </b>
            <p>
                {{ $workspace->pivot->role }}
            </p>
        </div>
    </div>

    <div class="grid workspace__actions">
        <a href="{{ route('targets-monitored.index', ['workspaceId' => $workspace->id]) }}"
            aria-label="{{ __('Show') }}" title="{{ __('Show') }}" class="workspace__show-btn">
            <span class="material-symbols-outlined">
                visibility
            </span>
        </a>


        @if ($workspace->pivot->role === 'owner')
            <button @click="$wire.openEdit = true" class="workspace__edit-btn" aria-label="{{ __('Edit') }}"
                title="{{ __('Edit') }}">
                <span class="material-symbols-outlined" x-transition.duration.300ms>
                    edit
                </span>
            </button>

            <button aria-label="{{ __('Delete') }}" title="{{ __('Delete') }}" class="workspace__delete-btn"
                @click.once="
                    @this.dispatch('workspace-to-delete', {workspaceId:{{ $workspace->id }}})
            ">
                <span class="material-symbols-outlined">delete_forever</span>
            </button>
        @endif
    </div>

    @if ($workspace->pivot->role === 'owner')
        <dialog x-show="$wire.openEdit" class="workspace-edit" open="">
            <article @click.outside="$wire.openEdit = false">
                <a href="#close" aria-label="{{ __('Cancel') }}" class="close" @click="$wire.openEdit = false"></a>

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
            </article>
        </dialog>

        <script>
            document.addEventListener('livewire:initialized', function() {
                @this.on('workspace-updated', () => {
                    @this.dispatch('notify', {
                        message: '{{ __('New workspace updated successfully') }}'
                    })
                    @this.set('openEdit', false);
                });
            })
        </script>
    @endif
</div>
