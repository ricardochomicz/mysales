<?php

namespace App\Livewire\Pages\Clients\Show;

use App\Models\Opportunity;
use Livewire\Component;

class DetailOpportunity extends Component
{
    public $orderId;

    protected $listeners = ['orderIdSelected'];

    public function orderIdSelected($orderId)
    {
        $this->orderId = $orderId;
        // Aqui você pode fazer qualquer coisa com $orderId, como carregar dados relacionados ao pedido
    }


    public function render()
    {
        return view('livewire.pages.clients.show.detail-opportunity',[
            'items' => Opportunity::with(['items_opportunity', 'ordem', 'operadora', 'client'])->find($this->orderId)
        ]);
    }

    public function getOrderTypeName($orderTypeId)
    {
        $order_type = collect($this->order_types)->firstWhere('id', $orderTypeId);
        return $order_type ? $order_type['name'] : '';
    }
}
