<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\ProcessedTarget;
use App\Models\TargetsMonitored;

class EndpointItem extends Component
{
    public TargetsMonitored | null $endpoint;
    public ProcessedTarget | null $lastProcess;
    public string $flash;
    public bool $displayEditForm = false;
    public bool $displayDetails = false;
    public bool $detailsDownloaded = false;
    public string $endpointStatus = '';

    public function render()
    {
        if (!$this->endpoint) {
            return '';
        }

        $this->lastProcess = $this->endpoint->processedTargets->sortBy('created_at')->last();

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

        // Is user owner of endpoint workspace?
        if (
            !auth()->user()
                ->workspaces()
                ->wherePivot('role', 'owner')
                ->where('id', $this->endpoint->workspace_id)
                ->exists()
        ) {
            throw new \Exception('You are not allowed to delete this endpoint.');
        }

        // Delete endpoint
        $this->endpoint->delete();
        $this->endpoint = null;
        
        $this->dispatch('endpoint-deleted');
        $this->skipRender();
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
            $idWorkspace =  $this->endpoint->workspace_id;
            $this->reset();

            $this->endpoint = 
                TargetsMonitored::whereHas('workspace',
                    function ($query) use ($idWorkspace) {
                        $query->where('id', $idWorkspace)
                            ->whereHas('users', function ($innerQuery) {
                                $innerQuery->where('id', auth()->id());
                            });
                    }
                )->where('id', $id)
                ->first();

            $this->displayEditForm = false;
            $this->flash = __('Endpoint updated successfully.');
        }
    }
}