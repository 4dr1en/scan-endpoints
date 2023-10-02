<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;
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

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                // Check if the user is already the owner of too many workspaces
                $maxWorkspaces = 5;
                if (auth()->user()->workspaces()->where('role', 'owner')->count() >= $maxWorkspaces) {
                    $validator->errors()->add(
                        'name',
                        __('You can\'t create more than :maxWorkspaces workspaces.', ['maxWorkspaces' => $maxWorkspaces])
                    );
                };
            });
        });
    }

    public function create()
    {
        $this->flash = '';

        // Do the user is authentified
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // livewire will clear the errors bag when calling validate()
        // so we need to check if there is errors that is fired by the boot method
        if ($this->getErrorBag()->any()) {
            return;
        }

        // Validate form
        $data = $this->validate();

        // Create workspace and define the user as the owner
        $workspace = auth()->user()->workspaces()->create(
            $data,
            ['role' => 'owner']
        );

        $this->flash = 'Workspace created successfully!';

        // Emit event to update workspaces list
        $this->dispatch('workspace-created');
    }
}