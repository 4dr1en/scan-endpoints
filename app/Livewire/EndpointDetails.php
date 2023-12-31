<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\CarbonInterval;
use App\Models\ProcessedTarget;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class EndpointDetails extends Component
{
    public TargetsMonitored $endpoint;
    public ProcessedTarget|null $lastCheck;
    public Collection $history;
    public array $keys = [];
    public array $data = [];
    public string $interval;
    public string $endpointStatus;
    public string $statusReport;
    public bool $showChartZoomAdvice = false;

    public function mount()
    {
        $this->interval = CarbonInterval
            ::create(0, 0, 0, 0, 0, 0, $this->endpoint->interval)
            ->cascade()
            ->forHumans(['short' => false]);
    }

    public function render()
    {
        $this->history = $this->endpoint->processedTargets()->orderBy('created_at')->get();
        $this->history->each(function ($item, $key) {
            $this->data[] = [
                'date' => $item->created_at->format('Y-m-d H:i:s'),
                'ping' => $item->response_time,
            ];
        });

        $this->lastCheck = count($this->history) > 0 ?
            $this->history[
                $this->history->count() - 1
            ] : null;

        if(count($this->history)){
            $firstDate = $this->history[0]->created_at;
            $lastDate = $this->lastCheck->created_at;
            $this->showChartZoomAdvice = $firstDate->diffInDays($lastDate) > 3;
        }

        $this->statusReport = match ($this->endpointStatus) {
            'unknown' => __('The last check was more than :interval ago.', ['interval' => $this->interval]),
            'slow' => __("The endpoint seem to be slow. The last check took :time ms.", ['time' => $this->lastCheck->response_time]),
            'warning' => __('The endpoint is redirecting. (code :code)', ['code' => $this->lastCheck->response_code]),
            'good' => __('The endpoint is up and running.'),
            'down' => __('The endpoint seem to be down. (code :code)', ['code' => $this->lastCheck->response_code]),
            default => __('The endpoint is in an unknown state, an error has occured.'),
        };

        return view('livewire.endpoint-details');
    }
}