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

    @if ($workspace->pivot->role === 'owner')
        <button @click.once="
            @this.dispatch('workspace-to-delete', {workspaceId:{{ $workspace->id }}})
        ">
            {{ __('Delete') }}
        </button>
    @endif
</div>
