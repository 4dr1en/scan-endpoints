<div class="container">
    <h1>List des endpoints ({{$page}})</h1>
    <ul>
        @forelse ($endpoints as $endpoint)
            <livewire:endpoint-item :endpoint="$endpoint" :key="$endpoint->id" />
        @empty
            <p>{{__('No endpoints found')}}</p>
        @endforelse
    </ul>
</div>
