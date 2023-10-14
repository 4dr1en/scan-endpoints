<div>
    <ul>
        @forelse($workspaces as $workspace)
            <li wire:key="workspace-{{$workspace->id}}">
                <livewire:workspace-item :$workspace wire:key="workspace-item-{{$workspace->id}}"/>
            </li>
        @empty
            <p>{{ __('No workspaces found') }}</p>
        @endforelse
    </ul>

    @teleport('body')
    <dialog x-data="{ open: false, message: '' }" x-show="open" :open="open" @notify.window="open = true; message = $event.detail.message">
        <article @click.outside="open = false">
            <a href="#close" aria-label="Close" class="close" @click="open = false"></a>
            <p x-text="message"></p>
        </article>
    </dialog>
    @endteleport
</div>
