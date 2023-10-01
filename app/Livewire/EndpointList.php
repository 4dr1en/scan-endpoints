<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EndpointList extends Component
{
    use WithPagination;

    public Workspace $workspace;

    #[Url]
    public $workspaceId;

    #[Rule('required|integer|min:1|max:100')]
    public $perPage = 10;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if($this->workspaceId) {
            $this->workspace =
                Auth::user()
                    ->workspaces()
                    ->findOrFail($this->workspaceId);
        } else {
            $this->workspace = Auth::user()->workspaces()->first();
            $this->workspaceId = $this->workspace->id;
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
            Log::alert("workspaceId update");
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