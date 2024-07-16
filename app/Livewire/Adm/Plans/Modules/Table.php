<?php

namespace App\Livewire\Adm\Plans\Modules;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    public $plan;
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount($plan): void
    {
        $this->plan = $plan;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $filters = [
            'search' => $this->search,
        ];

        return view('livewire.adm.plans.modules.table', [
            'modules' => $this->plan->modules()->filter($filters)->paginate()
        ]);
    }

    public function clearFilter(): void
    {
        $this->search = '';
    }
}
