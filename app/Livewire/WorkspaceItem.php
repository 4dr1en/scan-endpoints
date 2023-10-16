<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Livewire\Attributes\Rule;

class WorkspaceItem extends Component
{
    public Workspace $workspace;

    // Role of the current user in the workspace
    public string $role;

    #[Rule(['required', 'max:255'])]
    public string $editName;

    #[Rule('max:5000')]
    public string $editDescription;

    public bool $openEdit = false;

    public string $flash = '';

    public function mount(Workspace $workspace)
    {
        $this->workspace = $workspace;
        $this->editName = $workspace->name;
        $this->editDescription = $workspace->description;
    }

    public function render()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (
            !auth()->user()
                ->workspaces()
                ->where('id', $this->workspace->id)
                ->exists()
        ) {
            abort(403);
        }

        return view('livewire.workspace-item');
    }

    public function updateWorkspace()
    {
        if (
            !auth()->user()
                ->workspaces()
                ->wherePivot('role', 'owner')
                ->where('id', $this->workspace->id)
                ->exists()
        ) {
            abort(403);
        }

        $this->validate();

        $this->workspace->update([
            'name' => $this->editName,
            'description' => $this->editDescription,
        ]);

        $this->editName = $this->workspace->name;
        $this->editDescription = $this->workspace->description;
        $this->openEdit = false;

        $this->dispatch('notify', __('Workspace updated!'));
    }
}