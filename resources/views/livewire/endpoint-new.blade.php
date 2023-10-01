<div class="container">
    <h2>{{__('Create a new entry')}} {{$workspace->id}}</h2>
    <form
        wire:submit.prevent="create"
    >
        <label for="name">{{__('Name')}}</label>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <input
            wire:model="name"
            type="text"
            id="name"
            placeholder="{{__('Homepage')}}"/>
        <label for="description">{{__('Description')}}</label>
        @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <textarea
            wire:model="description"
            id="description"
            placeholder="{{__('Description')}}"
        ></textarea>
        <div class="grid">
            <div>
                <label for="protocol">{{__('Protocol')}}</label>
                @error('protocol')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <select
                    wire:model="protocol"
                    id="protocol"
                    @change="
                        if ($wire.protocol === 'https')
                            $wire.port = 443;
                        else if ($wire.protocol === 'http')
                            $wire.port = 80;
                    "
                >
                    <option
                        value="http"
                        @if ($protocol === 'http')
                            selected
                        @endif
                    >HTTP</option>
                    <option
                        value="https"
                        @if ($protocol === 'https')
                            selected
                        @endif
                    >HTTPS</option>
                </select>
            </div>
            <div style="grid-column: span 3">
                <label for="path">{{__('Path')}}</label>
                @error('path')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input
                    wire:model="path"
                    type="text"
                    id="path"
                    placeholder="www.wikipedia.org"
                />
            </div>
            <div>
                <label for="port">{{__('Port')}}</label>
                @error('port')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input
                    wire:model="port"
                    type="number"
                    id="port"
                    placeholder="Port"
                    min="1"
                    max="65535"
                    step="1"
                />
            </div>
        </div>
        <label for="interval">{{__('Interval')}}</label>
        @error('interval')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <select
            wire:model="interval"
            id="interval"
        >
            <option value="1800">{{__('Every 30 minute')}}</option>
            <option value="3600">{{__('Every 1 hour')}}</option>
            <option value="86400" selected>{{__('Every 1 day')}}</option>
            <option value="259200">{{__('Every 3 days')}}</option>
            <option value="604800">{{__('Every 1 week')}}</option>
        </select>
        <br>
        <button type="submit">{{__('Create')}}</button>
    </form>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('endpoint-created', (event) => {
                @this.dispatch('notify', { message: '{{__('New taget added successfully')}}' })
            });
        });
    </script>
</div>
