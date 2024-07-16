<?php

namespace App\Livewire\Pages\Orders;

use App\Services\OrderService;
use App\Services\OrderTypeService;
use App\Services\TagService;
use App\Traits\TypeTags;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination, TypeTags;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public $type;

    #[Url(history: true)]
    public $status;

    #[Url(history: true)]
    public $dt_ini;

    #[Url(history: true)]
    public $dt_end;

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'type' => ['except' => ''],
            'dt_ini' => ['except' => ''],
            'dt_end' => ['except' => ''],
        ];
    }


    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function mount(){
        $this->dt_ini = date("Y-m-01");
        $this->dt_end = date("Y-m-t");
    }

    public function render()
    {
        $orderService = new OrderService();
        $order_type = new OrderTypeService();
        $tags = new TagService();

        $filters = [
            'search' => $this->search,
            'type' => $this->type,
            'status' => $this->status,
            'dt_ini' => $this->dt_ini,
            'dt_end' => $this->dt_end,
        ];

        return view('livewire.pages.orders.table',[
            'order_type' => $order_type->toSelect(),
            'tags' => $tags->toSelectOrderStatus(),
            'data' => $orderService->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->type = '';
        $this->status = '';
        $this->dt_ini = date("Y-m-01");
        $this->dt_end = date("Y-m-t");
        $this->dispatch('resetSelectpicker');
    }
}
