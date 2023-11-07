<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EndpointList extends Component
{
    use WithPagination;

    public Workspace $workspace;

    #[Url(keep: true)]
    public int $workspaceId;

    public bool $haveEndpointDown = false;

    #[Rule('required|integer|min:1|max:100')]
    #[Url]
    public int $perPage = 10;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        else if(
            Auth::user()
                ->workspaces()
                ->doesntExist()
        ) {
            return $this->redirectRoute('workspace.index');
        }
    }

    public function getEndpointList()
    {
        // Is the user have the right to access the workspace?
        if (!Auth::user()->workspaces->contains($this->workspace)) {
            abort(403);
        }
        return $this->workspace
            ->targetsMonitoreds()
            ->paginate($this->perPage);
    }

    public function render()
    {
        if(isset($this->workspaceId)) {
            $this->workspace =
                Auth::user()
                    ->workspaces()
                    ->findOrFail($this->workspaceId);
        } else {
            $this->workspace = Auth::user()->workspaces()->first();
            $this->workspaceId = $this->workspace->id;
        }


        $this->workspace->targetsMonitoreds->each(function ($target) {
            $lastCheck = $target->processedTargets()->latest()->first();

            if (
                $lastCheck && 
                !preg_match('/^2|3/', (string) $lastCheck->response_code)
            ) {
                $this->haveEndpointDown = true;
            }
        });

        return view('livewire.endpoint-list', [
            'endpoints' => $this->getEndpointList(),
        ]);
    }

    public function updating($property, $value)
    {
        if ($property === 'perpage') {
            $this->validate();
            $this->resetPage();
        }

        if ($property === 'workspaceId') {
            $this->resetPage();
            $this->workspace =
                Auth::user()
                    ->workspaces()
                    ->findOrFail($value);
        }
    }

    #[On('endpoint-created')]
    #[On('endpoint-deleted')]
    public function updateEndpointList()
    {
        $this->resetPage();
    }
}