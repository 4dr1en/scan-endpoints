<?php

namespace App\Livewire;

use App\Models\Workspace;
use App\Rules\NoDuplicateEndpointOnWorkspace;
use App\Rules\Validtarget;
use App\Services\LaunchTargetCheckService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class EndpointNew extends Component
{
    #[Reactive]
    public Workspace $workspace;

    public string $name = '';
    public string $description = '';
    public string $protocol = 'http';
    public string $path = '';
    public int $port = 80; // Default portfor http
    public int $interval = 86400; // 24 hours

    public function render()
    {
        return view('livewire.endpoint-new');
    }

    public function create(LaunchTargetCheckService $launchTargetCheckService)
    {
        // Is user logged in?
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // User is the owner of the workspace?
        if (
            !auth()->user()
                ->workspaces()
                ->wherePivot('role', 'owner')
                ->where('id', $this->workspace->id)
                ->exists()
        ) {
            throw new \Exception('You are not the owner of this workspace');
        }

        // Validate input
        $this->validate([
            'name' => 'max:255|min:3|nullable',
            'description' => 'max:5000|nullable',
            'protocol' => [
                Rule::in(['http', 'https']),
                'nullable'
            ],
            'path' => [
                'required',
                new Validtarget(),
                new NoDuplicateEndpointOnWorkspace()
            ],
            'port' => 'nullable|integer|min:1|max:65535',
            'interval' => 'integer|min:60|max:1000000',
        ]);

        // Create new endpoint
        $this->workspace->targetsMonitoreds()->create([
            'name' => $this->name,
            'description' => $this->description,
            'protocol' => $this->protocol,
            'path' => $this->path,
            'port' => $this->port ?? null,
            'interval' => $this->interval,
            'status' => 'active',
        ]);

        // TODO: Only check the current endpoint
        $launchTargetCheckService->launch();

        $this->dispatch('endpoint-created');
        $this->dispatch('notify', __('Endpoint created successfully!'));
    }
}