<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EndpointItem extends Component
{
    public $endpoint;
    public $flash;
    public $displayEditForm = false;
    public $displayDetails = false;
    public $detailsDownloaded = false;
    public $endpointStatus = '';
    public $lastProcess;

    public function render()
    {
        $this->lastProcess = $this->endpoint->processedTargets->sortByDesc('created_at')->first();

        if (
            !$this->lastProcess ||
            // Checks if the last process is older than the date when the next process should have been run,
            // we add 20 minutes to the next process date to give some leeway for the process to run
            $this->lastProcess->created_at->add($this->endpoint->interval, 'second')->addMinutes(20) < Carbon::now()

        ) {
            $this->endpointStatus = 'unknown';
        } else if (
            str_starts_with((string) $this->lastProcess->response_code, '3')
        ) {
            $this->endpointStatus = 'warning';
        } else if (
            str_starts_with((string) $this->lastProcess->response_code, '2') &&
            $this->lastProcess->response_time > 1000
        ) {
            $this->endpointStatus = 'slow';
        } else if (
            str_starts_with((string) $this->lastProcess->response_code, '2')
        ) {
            $this->endpointStatus = 'good';
        } else {
            $this->endpointStatus = 'down';
        }

        return view('livewire.endpoint-item');
    }

    public function delete()
    {
        // Is user logged in?
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Is user owner of endpoint?
        if (auth()->user()->id !== $this->endpoint->user_id) {
            throw new \Exception('You are not allowed to delete this endpoint.');
        }

        // Delete endpoint
        auth()->user()->targetsMonitored()->where('id', $this->endpoint->id)->delete();

        $this->dispatch('endpoint-deleted', $this->endpoint->id);
    }

    public function toggleDetails()
    {
        $this->detailsDownloaded = true;
        $this->displayDetails = !$this->displayDetails;
        $this->displayEditForm = false;
    }

    #[On('endpoint-updated')]
    public function updateEndpoint(int $id)
    {
        if ($this->endpoint->id === $id) {
            $this->reset();
            $this->endpoint = auth()->user()->targetsMonitored()->where('id', $id)->first();
            $this->displayEditForm = false;
            $this->flash = __('Endpoint updated successfully.');
        }
    }
}