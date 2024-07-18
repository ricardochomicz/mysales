<?php

namespace App\Livewire\Pages\Commissions;

use App\Models\Operator;
use App\Models\Opportunity;
use App\Traits\TypeTags;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    #[Url(history: true)]
    public $search = '';
    #[Url(history: true)]
    public $month = '';
    #[Url(history: true)]
    public $operator = '';
    public $groupBy = 'groupBy';
    public $commissions = [];
    public $items = [];
    public $totalAmount = 0;

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'month' => ['except' => ''],
            'operator' => ['except' => ''],
        ];
    }
    public function mount()
    {
        $this->loadCommissions();
    }

    public function updated($property)
    {
        $this->loadCommissions();
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->month = '';
        $this->operator = '';
        $this->dispatch('resetSelectpicker');
        $this->loadCommissions();
    }

    public function loadCommissions()
    {
        $filters = [
            'search' => $this->search,
            'month' => $this->month,
            'operator' => $this->operator,
        ];
        $items = [];
        $groupBy = $this->groupBy;
        $now = Carbon::now();

        $query = Opportunity::with(['client', 'operadora', 'items_opportunity.product', 'items_opportunity.type'])
            ->where(['tenant_id' => auth()->user()->tenant->id, 'send_order' => 1, 'renew' => 0])
            ->whereIn('status_id', [3, 4])
            ->whereIn('type', ['order', 'wallet'])
            ->whereNull('deleted_at')
            ->filterCommission($filters);

        $commissions = $query->get()
            ->map(function ($commission) use (&$items, $groupBy) {
                $totalAmount = 0;

                if ($groupBy === 'detail') {
                    $commissionItems = $commission->items_opportunity->flatMap(function ($item) use (&$totalAmount) {
                        $qty = $item->qty;
                        $factor = $item->factor;
                        $price = $item->price;
                        $amount = $qty * $factor * $price;
                        $totalAmount += $amount;

                        return [
                            'id' => $item->id,
                            'identify' => $item->opportunity,
                            'client' => $item->client,
                            'product' => $item->product->name,
                            'type' => $item->type->name,

                            'qty' => 1,
                            'price' => $price,
                            'factor' => $factor,
                            'number' => $item->number,
                            'amount' => $amount,
                        ];
                    })->toArray();
                } else {
                    $commissionItems = $commission->items_opportunity->groupBy('product_id')
                        ->map(function ($items) use (&$totalAmount, $commission) {
                            $groupedItems = [];

                            $itemsByOrderType = $items->groupBy('order_type_id');
                            foreach ($itemsByOrderType as $orderTypeItems) {


                            $totalQty = 0;

                            foreach ($orderTypeItems as $item) {
                                $client = $commission->client;
                                $operator = $commission->operadora;
                                $qty = $item->qty;
                                $totalQty += $qty;
                            }

                            $firstItem = $orderTypeItems->first();

                            $groupedItems[] = [
                                'product_id' => $firstItem->product_id,
                                'opportunity_id' => $firstItem->opportunity_id,
                                'identify' => $firstItem->opportunity->identify,
                                'client' => $client->name,
                                'product' => $firstItem->product->name,
                                'operator' => $operator->name,
                                'type' => $firstItem->type->name,
                                'qty' => $totalQty,
                                'price' => $firstItem->price,
                                'factor' => $firstItem->factor,
                                'amount' => $totalQty * $firstItem->factor * $firstItem->price,
                            ];

                                $totalAmount += $groupedItems[count($groupedItems) - 1]['amount'];

                            }

                            return $groupedItems;
                        })->toArray();
                }

                $items = array_merge($items, $commissionItems);

                return [
                    'id' => $commission->id,
                    'client' => $commission->client,
                    'operator' => $commission->operator,
                    'items_opportunity' => $commission->items_opportunity,
                    'order_type' => $commission->order_type,
                    'qty' => $commission->lines,
//                    'factor' => $commission->order_type->factor,
                    'total_amount' => $totalAmount
                ];
            });

        $this->commissions = $commissions;
        $this->items = $items;
        $this->totalAmount = $commissions->sum('total_amount');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('livewire.pages.commissions.table', [
            'operators' => Operator::get(),
        ]);
    }
}
