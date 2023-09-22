<div class="container">
    <h1>List des endpoints</h1>
    <ul>
        @forelse ($endpoints as $endpoint)
        <livewire:endpoint-item :endpoint="$endpoint" :key="$endpoint->id" />
        @empty
        <p>{{__('No endpoints found')}}</p>
        @endforelse
    </ul>

    <label for="perpage">{{ __('Items per page') }}</label>
    <select name="perPage" id="perpage" wire:model.live="perPage">
        <option value="3">3</option>
        <option value="10" selected>10</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>

    {{ $endpoints->links('paginations/endpoints-pagination') }}

    @teleport('body')
    <dialog x-data="{ open: false, message: '' }" x-show="open" :open="open" @notify.window="open = true; message = $event.detail.message">
        <article @click.outside="open = false">
            <a href="#close" aria-label="Close" class="close" @click="open = false"></a>
            <p x-text="message"></p>
        </article>
    </dialog>
    @endteleport
</div>