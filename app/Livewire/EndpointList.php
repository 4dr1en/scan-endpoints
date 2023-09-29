<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class EndpointList extends Component
{
    use WithPagination;

    public Workspace $currentWorkspace;

    #[Rule('required|integer|min:1|max:100')]
    public $perPage = 10;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->currentWorkspace = Auth::user()->workspaces()->first();
    }

    public function getEndpointList()
    {
        // Is the user have the right to access the workspace?
        if (!Auth::user()->workspaces->contains($this->currentWorkspace)) {
            abort(403);
        }
        return $this->currentWorkspace
            ->targetsMonitored()
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
    }

    #[On('endpoint-created')]
    #[On('endpoint-deleted')]
    public function updateEndpointList(int $id)
    {
        $this->resetPage();
    }
}