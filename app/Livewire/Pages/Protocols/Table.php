<?php

namespace App\Livewire\Pages\Protocols;

use App\Mail\NewProtocol;
use App\Models\Client;
use App\Models\User;
use App\Services\ProtocolService;
use App\Services\TagService;
use App\Traits\TypeTags;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination, TypeTags;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public $tag;

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
            'tag' => ['except' => ''],
            'status' => ['except' => ''],
            'dt_ini' => ['except' => ''],
            'dt_end' => ['except' => ''],
        ];
    }


    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function mount()
    {
        $this->dt_ini = date("Y-m-01");
        $this->dt_end = date("Y-m-t");
    }

    public function render()
    {
        $protocolService = new ProtocolService();
        $tags = new TagService();
        $protocolStatus = $this->protocolStatus();

        $filters = [
            'search' => $this->search,
            'tag' => $this->tag,
            'status' => $this->status,
            'dt_ini' => $this->dt_ini,
            'dt_end' => $this->dt_end,
        ];

        return view('livewire.pages.protocols.table', [
            'tags' => $tags->toSelectProtocol(),
            'protocolStatus' => $protocolStatus,
            'data' => $protocolService->index($filters)
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

    public function sendProtocolEmail($id): void
    {
        $protocolService = new ProtocolService;
        $protocol = $protocolService->get($id);
        $client = Client::where('id', $protocol->client_id)->first();

        Mail::to($client->persons[0]->email)
            ->send(new NewProtocol($protocol));
    }

}
