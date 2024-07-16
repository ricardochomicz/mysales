<?php

namespace App\Livewire\Pages\Operators;

use App\Services\OperatorService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
        ];
    }

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function render()
    {
        $model = new OperatorService();

        $filters = [
            'search' => $this->search
        ];

        return view('livewire.pages.operators.table', [
            'data' => $model->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->dispatch('resetSelectpicker');
    }
}
