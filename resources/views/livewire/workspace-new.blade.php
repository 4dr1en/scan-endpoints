<div>
    <form wire:submit.prevent="create">
        <div class="form-group">
            <label for="name">{{__('Workspace title')}}</label>
            <input type="text" id="name" wire:model="name">
            @error('name') <span>{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">{{__('Workspace description')}}</label>
            <input type="text" id="description" wire:model="description">
            @error('description') <span>{{ $message }}</span> @enderror
        </div>

        <button type="submit">
            {{__('Create')}}
        </button>
    </form>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('workspace-created', (event) => {
                @this.dispatch('notify', { message: '{{__('New workspace added successfully')}}' })
            });
        });
    </script>
</div>
