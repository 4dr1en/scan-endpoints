<div class="workspace-create">
    <form wire:submit.prevent="create" class="workspace-form">
        <div class="workspace-form__name">
            <label for="name">{{ __('Workspace title') }}</label>
            <input type="text" id="name" wire:model="name">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div class="workspace-form__description">
            <label for="description">{{ __('Workspace description') }}</label>
            <input type="text" id="description" wire:model="description">
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">
            {{ __('Create') }}
        </button>
    </form>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('workspace-created', (event) => {
                @this.dispatch('notify', {
                    message: '{{ __('New workspace added successfully') }}'
                })
            });
        });
    </script>
</div>
