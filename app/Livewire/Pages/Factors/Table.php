<?php

namespace App\Livewire\Pages\Factors;

use App\Models\Factor;
use App\Models\Operator;
use App\Models\OrderType;
use App\Services\BaseService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $trashed = '';

    #[Url(history: true)]
    public string $operator_id = '';

    #[Url(history: true)]
    public string $order_type_id = '';

    protected function queryString(): array
    {
        return [
            'trashed' => ['except' => ''],
            'operator_id' => ['except' => ''],
            'order_type_id' => ['except' => ''],
        ];
    }

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function render()
    {
        $model = new BaseService(Factor::class);
        $operators = new BaseService(Operator::class);
        $orders = new BaseService(OrderType::class);

        $filters = [
            'trashed' => $this->trashed,
            'operator_id' => $this->operator_id,
            'order_type_id' => $this->order_type_id,
        ];

        return view('livewire.pages.factors.table',[
            'data' => $model->index($filters),
            'operators' => $operators->toSelect(),
            'orders' => $orders->toSelect(),
        ]);
    }

    public function clearFilter()
    {
        $this->trashed = '';
        $this->operator_id = '';
        $this->order_type_id = '';
        $this->dispatch('resetSelectpicker');
    }
}
