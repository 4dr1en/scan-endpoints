<div class="workspace-dashboard">
    <ul class="workspace-list">
        @forelse($workspaces as $workspace)
            <li wire:key="workspace-{{ $workspace->id }}" class="workspace-item">
                <livewire:workspace-item :$workspace :role="$workspace->pivot->role" wire:key="workspace-item-{{ $workspace->id }}" />
            </li>
        @empty
            <p>{{ __('No workspaces found') }}</p>
        @endforelse
    </ul>
</div>
