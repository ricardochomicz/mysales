<?php

namespace App\Livewire\Pages\Clients;

use App\Models\Client;
use App\Services\BaseService;
use App\Services\ClientService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $trashed = '';

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'trashed' => ['except' => ''],
        ];
    }

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function render()
    {
        $clientService = new ClientService();

        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
        ];

        return view('livewire.pages.clients.table',[
            'data' => $clientService->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->dispatch('resetSelectpicker');
    }
}
