<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class EndpointList extends Component
{
    use WithPagination;

    #[Rule('required|integer|min:1|max:100')]
    public $perPage = 10;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function getEndpointList()
    {
        return Auth::user()->targetsMonitored()->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.endpoint-list', [
            'endpoints' => $this->getEndpointList(),
        ]);
    }

    public function updating($property, $value)
    {
        if ($property === 'perpage') {
            $this->validate();
            $this->resetPage();
        }
    }

    #[On('endpoint-created')]
    #[On('endpoint-deleted')]
    public function updateEndpointList(int $id)
    {
        $this->resetPage();
    }
}
