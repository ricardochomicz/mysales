<?php

namespace App\Livewire\Adm\Plans;

use App\Services\PlanService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public string $search = '';

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
        ];
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $plans = new PlanService();
        $filters = [
            'search' => $this->search,
        ];
        return view('livewire.adm.plans.table', [
            'data' => $plans->index($filters),
        ]);
    }

    public function clearFilter(): void
    {
        $this->search = '';
        $this->dispatch('resetSelectpicker');
    }
}
