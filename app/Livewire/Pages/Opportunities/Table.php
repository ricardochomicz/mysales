<?php

namespace App\Livewire\Pages\Opportunities;

use App\Models\Opportunity;
use App\Services\BaseService;
use App\Services\OpportunityService;
use App\Services\OrderTypeService;
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

    public function mount()
    {
        $this->dt_ini = date("Y-m-01");
        $this->dt_end = date("Y-m-t");
    }

    public function render()
    {
        $model = new OpportunityService();
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

        return view('livewire.pages.opportunities.table', [
            'funnels' => $this->typeFunnel(),
            'order_type' => $order_type->toSelect(),
            'data' => $model->index($filters),
        ]);
    }

    public function updateFunnel($opportunityId, $funnelValue): void
    {
        // Encontre a oportunidade pelo ID e atualize o campo funnel
        $opportunity = Opportunity::find($opportunityId);
        $opportunity->update([
            'funnel' => $funnelValue
        ]);
        $this->funnel[$opportunityId] = $funnelValue;
    }


    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->funnel = '';
        $this->probability = '';
        $this->type = '';
        $this->dt_ini = date("Y-m-01");
        $this->dt_end = date("Y-m-t");
        $this->dispatch('resetSelectpicker');
    }

    public function loadComments($id)
    {
        $this->opportunity = Opportunity::with('client')->find($id);
        $this->comments = Opportunity::find($id)->comments;
    }


}
