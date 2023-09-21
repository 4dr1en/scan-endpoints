<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class EndpointItem extends Component
{
    public $endpoint;
    public $flash;
    public $displayEditForm = false;

    public function render()
    {
        return view('livewire.endpoint-item');
    }

    public function delete()
    {
        // Is user logged in?
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Is user owner of endpoint?
        if (auth()->user()->id !== $this->endpoint->user_id) {
            throw new \Exception('You are not allowed to delete this endpoint.');
        }

        // Delete endpoint
        auth()->user()->targetsMonitored()->where('id', $this->endpoint->id)->delete();

        $this->dispatch('endpoint-deleted', $this->endpoint->id);
    }

    #[On('endpoint-updated')]
    public function updateEndpoint(int $id)
    {
        if ($this->endpoint->id === $id) {
            $this->reset();
            $this->endpoint = auth()->user()->targetsMonitored()->where('id', $id)->first();
            $this->displayEditForm = false;
            $this->flash = __('Endpoint updated successfully.');
        }
    }
}
