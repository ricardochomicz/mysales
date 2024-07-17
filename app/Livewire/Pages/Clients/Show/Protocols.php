<?php

namespace App\Livewire\Pages\Clients\Show;

use App\Models\Client;
use App\Services\ClientService;
use Livewire\Component;
use Livewire\WithPagination;

class Protocols extends Component
{
    use WithPagination;
    public $client;

    public function mount($client)
    {
        $this->client = $client;
    }

    public function render()
    {
        $clientService = new ClientService();
        $client = $clientService->get($this->client);
        $protocols = $client->protocols()->orderBy('updated_at')->paginate();
        return view('livewire.pages.clients.show.protocols',[
            'clients' =>$client,
            'protocols' => $protocols
        ]);
    }

    public function getNameStatus($status)
    {
        switch ($status) {
            case 1:
                return '<span class="badge bg-secondary">EM TRATAMENTO</span>';
            case 2:
                return '<span class="badge bg-danger">CANCELADO</span>';
            case 3:
                return '<span class="badge bg-success">FINALIZADO</span>';
            default:
                return '<span class="badge bg-secondary">DESCONHECIDO</span>';
        }
    }
}
