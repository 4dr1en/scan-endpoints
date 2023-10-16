<div class="container endpoint-create">
    <h2 class="endpoint-create__title">{{ __('Create a new entry') }}</h2>
    <form wire:submit.prevent="create" class="endpoint-form">
        <div class="endpoint-form__name">
            <label for="name">{{ __('Name') }}</label>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input wire:model="name" type="text" id="name" placeholder="{{ __('Homepage') }}" />
        </div>
        <div class="endpoint-form__description">
            <label for="description">{{ __('Description') }}</label>
            @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <textarea wire:model="description" id="description" placeholder="{{ __('Description') }}"></textarea>
        </div>
        <div class="endpoint-form__target">
            <div class="endpoint-form__target-protocol">
                <label for="protocol">{{ __('Protocol') }}</label>
                @error('protocol')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <select wire:model="protocol" id="protocol"
                    @change="
                        if ($wire.protocol === 'https')
                            $wire.port = 443;
                        else if ($wire.protocol === 'http')
                            $wire.port = 80;
                    ">
                    <option value="http" @if ($protocol === 'http') selected @endif>HTTP</option>
                    <option value="https" @if ($protocol === 'https') selected @endif>HTTPS</option>
                </select>
            </div>
            <div class="endpoint-form__target-path">
                <label for="path">{{ __('Path') }}</label>
                @error('path')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input wire:model="path" type="text" id="path" placeholder="www.wikipedia.org" />
            </div>
            <div class="endpoint-form__target-port">
                <label for="port">{{ __('Port') }}</label>
                @error('port')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <input wire:model="port" type="number" id="port" placeholder="Port" min="1" max="65535"
                    step="1" />
            </div>
        </div>
        <div class="endpoint-form__interval">
            <label for="interval">{{ __('Ping Check Interval') }}</label>
            @error('interval')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <select wire:model="interval" id="interval">
                <option value="1800">{{ __('Every 30 minutes') }}</option>
                <option value="3600">{{ __('Every 1 hour') }}</option>
                <option value="86400" selected>{{ __('Every 1 day') }}</option>
                <option value="259200">{{ __('Every 3 days') }}</option>
                <option value="604800">{{ __('Every 1 week') }}</option>
            </select>
        </div>
        <br>
        <button type="submit">{{ __('Create') }}</button>
    </form>
</div>
