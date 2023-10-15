<div class="workspace-create">
    <h2 class="workspace-create__title">{{ __('Create a new workspace') }}</h2>
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
            <textarea type="text" id="description" wire:model="description">
            </textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <br>
        <button type="submit">
            {{ __('Create') }}
        </button>
    </form>
</div>
