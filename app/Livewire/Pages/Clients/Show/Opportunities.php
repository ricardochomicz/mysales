<?php

namespace App\Livewire\Pages\Clients\Show;

use App\Models\Client;
use App\Models\Opportunity;
use App\Services\ClientService;
use Livewire\Component;

class Opportunities extends Component
{
    public $client;
    public $orderId;

    public $comments = [];

    public $opportunity;


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
        $clientService = new ClientService();
        $client = $clientService->get($this->client);
        $orders = $client->orders()->orderBy('created_at', 'desc')->paginate();
        return view('livewire.pages.clients.show.opportunities',[
            'clients' =>$client,
            'orders' => $orders
        ]);
    }

    public function loadComments($id): void
    {
        $this->opportunity = Opportunity::with('client')->find($id);
        $this->comments = $this->opportunity->comments;

        $this->dispatch('openModal');
    }


}
