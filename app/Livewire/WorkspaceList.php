<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceList extends Component
{
    public Collection $workspaces;

    public function render()
    {
        $this->workspaces = auth()->user()->workspaces;
        return view('livewire.workspace-list', [
            'workspaces' => $this->workspaces,
        ]);
    }

    #[On('workspace-created')]
    #[On('workspace-deleted')]
    public function updateEndpointList(int $id)
    {
        $this->reset();
    }
}