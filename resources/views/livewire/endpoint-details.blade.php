<div x-init="
    if($wire.data.length > 0) {
        window.buildLineChart(
            $refs.historyChart,
            $wire.data,
        );
    }
">
    <div class="status-report">
        <strong>
            {{ $statusReport }}
        </strong>
    </div>
    <hr>
    <p>
        <b>
            {{ __('Name of the endpoint:') }}
        </b>
        <span>
            {{ $endpoint->name ?: __('No name') }}
        </span>
    </p>

    <p>
        <b>
            {{ __('Description:') }}
        </b>
        <br>
        <span>
            {{ $endpoint->description ?: __('No description') }}
        </span>
    </p>

    <p>
        <b>
            {{ __('Check every:') }}
        </b>
        <span>
            @if($endpoint->status != 'active' || !$endpoint->interval )
               {{__('Never')}}
            @else
                {{ $interval }}
            @endif
        </span>
    </p>

    <div>
        <p>{{__('History of the response time')}}</p>

        @if($history->count() > 0)
            <canvas
                id="historyChart"
                x-ref="historyChart"
                wire:ignore
            ></canvas>
            @if($showChartZoomAdvice)
            <p>{!! __(
                'Use :key to select part of the chart.',
                ['key' => '<kbd>ctrl + click</kbd>']
            ) !!}</p>
            @endif
        @endif
    </div>
</div>