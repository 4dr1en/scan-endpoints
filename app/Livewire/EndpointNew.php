<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Rules\Validtarget;


class EndpointNew extends Component
{
    public string $name = '';
    public string $description = '';
    public string $protocol = 'http';
    public string $path = '';
    public int $port = 80;
    public int $interval = 86400;

    public function render()
    {
        return view('livewire.endpoint-new');
    }

    public function create(){
        // Is user logged in?
        if(!auth()->check()){
            return redirect()->route('login');
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
                new Validtarget(
                    $this->protocol,
                    $this->path,
                    $this->port
                )
            ],
            'port' => 'integer|min:1|max:65535|nullable',
            'interval' => 'integer|min:1|max:1000000',
        ]);

        // Create new endpoint
        $endpoint = auth()->user()->targetsMonitored()->create([
            'name' => $this->name,
            'description' => $this->description,
            'protocol' => $this->protocol,
            'path' => $this->path,
            'port' => $this->port,
            'interval' => $this->interval,
            'status' => 'active',
        ]);

        // Emit event to update endpoints list
        $this->dispatch('endpoint-created', $endpoint->id);
    }
}
