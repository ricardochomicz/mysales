<?php

namespace App\Livewire\Pages\Tenants;

use App\Services\TenantService;
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
        $tenants = new TenantService();
        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
        ];
        return view('livewire.pages.tenants.table',[
            'data' => $tenants->index($filters)
        ]);
    }

    public function clearFilter(): void
    {
        $this->search = '';
        $this->trashed = '';
        $this->dispatch('resetSelectpicker');
    }
}
