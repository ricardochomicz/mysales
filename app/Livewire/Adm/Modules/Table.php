<?php

namespace App\Livewire\Adm\Modules;

use App\Services\ModuleService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $trashed = '';

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'trashed' => ['except' => ''],
        ];
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $plans = new ModuleService();
        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed
        ];
        return view('livewire.adm.modules.table', [
            'data' => $plans->index($filters),
        ]);
    }

    public function clearFilter(): void
    {
        $this->search = '';
        $this->trashed = '';
        $this->dispatch('resetSelectpicker');
    }
}
