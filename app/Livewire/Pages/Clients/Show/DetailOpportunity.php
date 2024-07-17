<?php

namespace App\Livewire\Pages\Clients\Show;

use App\Models\Opportunity;
use App\Services\OpportunityService;
use Livewire\Component;

class DetailOpportunity extends Component
{
    public $orderId = null;
    public $opportunities;
    public $opportunity;

    protected $listeners = ['orderIdSelected'];

    public function mount()
    {
        $this->opportunities = collect(); // Inicializa com uma coleção vazia
    }

    public function orderIdSelected($orderId)
    {
        $this->orderId = $orderId;

        if ($this->orderId) {
            $opportunityService = new OpportunityService();
            $this->opportunity = $opportunityService->get($this->orderId);
            $this->opportunities = $this->opportunity->items_opportunity;
        }
    }


    public function render()
    {
        return view('livewire.pages.clients.show.detail-opportunity', [
            'opportunity' => $this->opportunity,
            'items' => $this->opportunities
        ]);
    }

    public function getOrderTypeName($orderTypeId)
    {
        $order_type = collect($this->order_types)->firstWhere('id', $orderTypeId);
        return $order_type ? $order_type['name'] : '';
    }
}
