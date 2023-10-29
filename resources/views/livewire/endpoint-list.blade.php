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
            <div x-data="{ show: false }" x-show="show" wire:key="endpoint-{{ $endpoint->id }}" x-init="setTimeout(() => { show = true }, (60 * {{ $loop->index }}))"
                x-transition.duration.200.ease>
                <livewire:endpoint-item :endpoint="$endpoint" wire:key="endpoint-item-{{ $endpoint->id }}" />
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

    <script>
        document.addEventListener('livewire:initialized', ($wire) => {
            let link = document.querySelector("link[rel~='icon']");
            if (!link) {
                link = document.createElement('link');
                link.rel = 'icon';
                document.head.appendChild(link);
            }

            if (@this.get('haveEndpointDown')) {
                // set a green icon to the favicon
                link.href =
                    'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>❗</text></svg>';
            } else {
                // set a red icon to the favicon
                link.href =
                    'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✅</text></svg>';
            }
        })
    </script>
</div>
