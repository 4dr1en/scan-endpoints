<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceList extends Component
{
    public Collection $workspaces;

    public function render()
    {
        $this->workspaces = auth()->user()->workspaces()->get();
        return view('livewire.workspace-list');
    }

    #[On('workspace-created')]
    public function updateWorkspaceList()
    {
        $this->reset();
    }

    #[On('workspace-to-delete')]
    public function deleteWorkspace($workspaceId)
    {
        $workspace = auth()->user()->workspaces()->wherePivot('role', 'owner')->findOrFail($workspaceId);

        if ($workspace) {
            $workspace->delete();
            $this->dispatch('workspace-deleted');
            $this->reset();
        }
    }
}