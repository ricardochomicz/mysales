<?php

namespace App\Livewire\Adm\Plans\Modules;

use Livewire\Component;
use Livewire\WithPagination;

class Available extends Component
{
    use WithPagination;

    public $plan;
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount($plan): void
    {
        $this->plan = $plan;
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
        ];
        return view('livewire.adm.plans.modules.available',[
            'modules' => $this->plan->modulesAvailable($filters)
        ]);
    }
}
