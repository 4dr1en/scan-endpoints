<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class EndpointList extends Component
{
    public $page = 1;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function getEndpointList()
    {
        return Auth::user()->targetsMonitored()->paginate(10, ['*'], 'page', $this->page);
    }

    public function render()
    {
        return view('livewire.endpoint-list', [
            'endpoints' => $this->getEndpointList(),
            'page' => $this->page,
        ]);
    }

    #[On('endpoint-created')]
    #[On('endpoint-deleted')]
    public function updateEndpointList(int $id)
    {
        $this->page = 1;
    }
}
