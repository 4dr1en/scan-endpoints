<?php

namespace App\Livewire;

use Livewire\Component;
use App\Rules\Validtarget;
use App\Models\TargetsMonitored;

class EndpointEdit extends Component
{
    public TargetsMonitored $endpoint;

    public string $name;
    public string $description;
    public string $protocol;
    public string $path;
    public int $port;
    public int $interval;

    public function mount($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->name = $endpoint->name;
        $this->description = $endpoint->description;
        $this->protocol = $endpoint->protocol;
        $this->path = $endpoint->path;
        $this->port = $endpoint->port;
        $this->interval = $endpoint->interval;
    }

    public function render()
    {
        return view('livewire.endpoint-edit');
    }

    public function update()
    {
        // Is user logged in?
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Is user owner of endpoint?
        if (
            $this->endpoint
                ->workspace()
                ->first()
                ->users()
                ->where('user_id', auth()->user()->id)
                ->count() == 0
        ) {
            throw new \Exception('You are not allowed to edit this endpoint.');
        }

        // Validate input
        $this->validate([
            'name' => 'max:255|min:3|nullable',
            'description' => 'max:5000|nullable',
            'protocol' => 'in:http,https|nullable',
            'path' => [
                'required',
                new Validtarget(
                    $this->protocol,
                    $this->path,
                    $this->port
                )
            ],
            'port' => 'integer|min:1|max:65535|nullable',
            'interval' => 'integer|min:1|max:1000000',
        ]);

        // Update endpoint
        $this->endpoint->update([
            'name' => $this->name,
            'description' => $this->description,
            'protocol' => $this->protocol,
            'path' => $this->path,
            'port' => $this->port,
            'interval' => $this->interval,
        ]);

        $this->dispatch('endpoint-updated', $this->endpoint->id);
    }
}