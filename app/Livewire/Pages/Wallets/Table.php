<?php

namespace App\Livewire\Pages\Wallets;

use App\Services\OrderService;
use App\Services\OrderTypeService;
use App\Services\TagService;
use App\Services\WalletService;
use App\Traits\TypeTags;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public $dt_ini;

    #[Url(history: true)]
    public $dt_end;

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'dt_ini' => ['except' => ''],
            'dt_end' => ['except' => ''],
        ];
    }


    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function mount(){
//        $this->dt_ini = date("Y-m-01");
//        $this->dt_end = date("Y-m-t");
    }

    public function render()
    {
        $walletService = new WalletService();

        $filters = [
            'search' => $this->search,
            'dt_ini' => $this->dt_ini,
            'dt_end' => $this->dt_end,
        ];

        return view('livewire.pages.wallets.table',[
            'data' => $walletService->index($filters)
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
//        $this->dt_ini = date("Y-m-01");
//        $this->dt_end = date("Y-m-t");
        $this->dispatch('resetSelectpicker');
    }
}
