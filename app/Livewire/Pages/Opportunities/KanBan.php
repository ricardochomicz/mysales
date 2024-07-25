<?php

namespace App\Livewire\Pages\Opportunities;

use App\Models\Opportunity;
use App\Services\OpportunityService;
use App\Services\OrderTypeService;
use App\Traits\TypeTags;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class KanBan extends Component
{
    public $prospect;
    public $negotiation;
    public $closure;
    public $correction;
    public $order;

    use WithPagination, TypeTags;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $trashed = '';

    #[Url(history: true)]
    public string $funnel = '';

    #[Url(history: true)]
    public $probability;

    #[Url(history: true)]
    public $type;

    #[Url(history: true)]
    public $dt_ini;

    #[Url(history: true)]
    public $dt_end;

    public $comments = [];

    public $opportunity;

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'trashed' => ['except' => ''],
            'funnel' => ['except' => ''],
            'probability' => ['except' => ''],
            'type' => ['except' => ''],
            'dt_ini' => ['except' => ''],
            'dt_end' => ['except' => ''],
        ];
    }


    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function updateOpportunityStatus($itemId, $newStatus): void
    {
        $statusMap = [
            'prospect' => 'Prospect',
            'negotiation' => 'Negociação',
            'closure' => 'Fechamento',
            'correction' => 'Para Correção',
        ];

        $nameStatus = $statusMap[$newStatus] ?? 'Desconhecido';

        $opportunity = Opportunity::where('tenant_id', auth()->user()->tenant->id)->find($itemId);

        if ($opportunity) {
            $opportunity->update([
                'funnel' => $newStatus,
            ]);
            notyf()->success("Oportunidade movida para: $nameStatus");
            $this->dispatch('refreshKanban');
        }else{
            notyf()->error('Erro ao atualizar oportunidade.');
        }
    }


    public function render()
    {
        $opportunityService = new OpportunityService();
        $order_type = new OrderTypeService();

        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
            'funnel' => $this->funnel,
            'probability' => $this->probability,
            'type' => $this->type,
            'dt_ini' => $this->dt_ini,
            'dt_end' => $this->dt_end,
        ];

        return view('livewire.pages.opportunities.kan-ban',[
            'funnels' => $this->typeFunnel(),
            'order_type' => $order_type->toSelect(),
            'data' => $opportunityService->index($filters),
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->funnel = '';
        $this->probability = '';
        $this->type = '';
        $this->dt_ini = '';
        $this->dt_end = '';
        $this->dispatch('resetSelectpicker');
    }

    public function loadCommentsKanban($id)
    {
        $this->opportunity = Opportunity::with('client')->find($id);
        $this->comments = $this->opportunity->comments;

        $this->dispatch('openModal');
    }
}