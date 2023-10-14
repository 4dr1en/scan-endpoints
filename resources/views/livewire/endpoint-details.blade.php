<div x-init="if ($wire.data.length > 0) {
    window.buildLineChart(
        $refs.historyChart,
        $wire.data,
    );
}" class="endpoint-details">
    <div class="endpoint-details__status-report endpoint-details__status-report--{{ $endpointStatus }}">
        <p>
            {{ $statusReport }}
        </p>
    </div>
    <hr>
    <p class="endpoint-details__name">
        <b>
            {{ __('Name of the endpoint:') }}
        </b>
        <span>
            {{ $endpoint->name ?: __('No name') }}
        </span>
    </p>

    <p class="endpoint-details__interval">
        <b>
            {{ __('Check every:') }}
        </b>
        <span>
            @if ($endpoint->status != 'active' || !$endpoint->interval)
                {{ __('Never') }}
            @else
                {{ $interval }}
            @endif
        </span>
    </p>

    <div class="endpoint-details__description">
        <b>
            {{ __('Description:') }}
        </b>
        <p>
            {{ $endpoint->description ?: __('No description have been provided for this endpoint.') }}
        </p>
    </div>

    <hr>

    <div class="endpoint-details__graph">
        <p class="endpoint-details__graph-title">{{ __('History of the response time') }}</p>

        @if ($history->count() > 0)
            <canvas id="historyChart" x-ref="historyChart" wire:ignore></canvas>
            @if ($showChartZoomAdvice)
                <p>{!! __('Use :key to select part of the chart.', ['key' => '<kbd>ctrl + click</kbd>']) !!}</p>
            @endif
        @endif
    </div>
    <hr class="endpoint-details__bottom-separator">
</div>
