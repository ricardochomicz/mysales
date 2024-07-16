<?php

namespace App\Livewire\Pages\Clients\Show;

use App\Models\Client;
use Livewire\Component;

class Opportunities extends Component
{
    public $client;
    public $orderId;

    public function mount($client)
    {
        $this->client = $client;
    }

    public function emitOrderId($orderId)
    {
        $this->dispatch('orderIdSelected', $orderId);
    }

    public function render()
    {
        return view('livewire.pages.clients.show.opportunities',[
            'clients' => Client::with(['user', 'operator', 'orders'])
                ->where(['tenant_id' => auth()->user()->tenant->id])->find($this->client)
        ]);
    }


}
