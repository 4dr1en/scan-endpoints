<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Rules\WorkspaceNameUniqueForUser;

class WorkspaceNew extends Component
{
    #[Rule(['required', 'max:255', new WorkspaceNameUniqueForUser])]
    public string $name = '';

    #[Rule('max:5000')]
    public string $description = '';

    // Validate message after workspace creation
    public string $flash = '';

    public function render()
    {
        // do the user is authentified
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        return view('livewire.workspace-new');
    }

    public function create()
    {
        $this->flash = '';

        // Do the user is authentified
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validate form
        $this->validate();

        // Create workspace and define the user as the owner
        $workspace = auth()->user()->workspaces()->create(
            [
                'name' => $this->name,
                'description' => $this->description,
            ],
            ['role' => 'owner']
        );

        $this->flash = 'Workspace created successfully!';

        // Emit event to update workspaces list
        $this->dispatch('workspace-created', $workspace->id);
    }
}