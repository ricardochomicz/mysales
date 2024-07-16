<?php

namespace App\Livewire\Pages\Classifications;

use App\Models\Classification;
use App\Services\BaseService;
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
        $model = new BaseService(Classification::class);

        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
        ];

        return view('livewire.pages.classifications.table',[
            'data' => $model->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->dispatch('resetSelectpicker');
    }
}
