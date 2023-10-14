<div class="endpoints container">

    <div class="endpoints__workspace-selection">
        <label for="workspaces">
            {{ __('Current workspace :') }}
        </label>
        <select name="workspaces" id="workspaces" wire:model.live="workspaceId">
            @foreach (Auth::user()->workspaces as $item)
                <option value="{{ $item->id }}" @if ($item->id === $workspaceId) selected @endif>{{ $item->name }}
                </option>
            @endforeach
        </select>
    </div>
    <h1 class="endpoints__title">
        {{ __('Endpoints') }}
    </h1>

    <ul class="endpoints__list">
        @forelse ($endpoints as $endpoint)
            <div x-data="{ show: false }" x-show="show" x-init="setTimeout(() => { show = true }, (30 * {{ $loop->index }}))" x-transition.duration.100>
                <livewire:endpoint-item :endpoint="$endpoint" wire:key="workspace-{{ $endpoint->id }}" />
            </div>
        @empty
            <p>{{ __('No endpoints found') }}</p>
        @endforelse
    </ul>

    <div class="endpoints__perpage">
        <select name="perPage" id="perpage" wire:model.live="perPage">
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <label for="perpage">{{ __('Items per page') }}</label>
    </div>

    {{ $endpoints->links('paginations/endpoints-pagination') }}

    <livewire:endpoint-new :$workspace />

    @teleport('body')
        <dialog x-data="{ open: false, message: '' }" x-show="open" :open="open"
            @notify.window="open = true; message = $event.detail.message">
            <article @click.outside="open = false">
                <a href="#close" aria-label="Close" class="close" @click="open = false"></a>
                <p x-text="message"></p>
            </article>
        </dialog>
    @endteleport
</div>
