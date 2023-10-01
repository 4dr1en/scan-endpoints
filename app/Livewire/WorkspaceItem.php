<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Illuminate\Support\Facades\Log;

class WorkspaceItem extends Component
{
    public Workspace $workspace;

    public function render()
    {
        return view('livewire.workspace-item');
    }
}