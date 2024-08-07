<?php

namespace App\Livewire\Pages\Opportunities\Items;

use App\Models\Factor;
use App\Models\ItemOpportunity;
use App\Models\Opportunity;
use App\Models\Product;
use App\Services\OperatorService;
use App\Services\OpportunityService;
use App\Services\OrderTypeService;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Request;

class ProductModal extends Component
{
    use WithPagination;

    public Collection $products;
    public Collection $operators;
    public Collection $order_types;
    public $items;
    public string $itemId;
    public $opportunity_id;
    public $filteredItems;
    public $operator = '';
    public ?int $order_type_id = null;
    public string $product_id;
    public $price;
    public $qty = 1;
    public $total;
    public $number = '';
    public string $search = '';
    public string $productSearch = '';
    public $editIndex = null;
    public $totalQty;
    public $totalValue;

    public $subtotal;

    public $page = 1;

    public $selectedItems = [];
    public $selectAll = false;
    public $bulkEditNumber;
    public bool $isBulkEdit = false;

    public function mount()
    {
        $operators = new OperatorService();
        $order_types = new OrderTypeService();
        $this->operators = $operators->toSelect();
        $this->order_types = $order_types->toSelect();
        $this->products = collect();

        //        if (session()->has('items')) {
        //            $this->items = session('items');
        //        }

        $opportunity = new OpportunityService();
        if (request()->route('id')) {
            $this->opportunity_id = $opportunity->get(request()->route('id'));

            $this->items = $this->opportunity_id->items_opportunity->toArray();

            $this->filteredItems = $this->items;

            $totals = $this->calculateTotals();
            $this->totalQty = $totals['totalQty'];
            $this->totalValue = $totals['totalValue'];
        } else {
            $this->items = session('items');
            $this->filteredItems = $this->items;
        }
    }

    public function updatedSearch($value): void
    {
        $this->filteredItems = array_filter($this->items, function ($item) use ($value) {
            return stripos($item['number'], $value) !== false;
        });
    }


    public function updatedOperator($value): void
    {
        $this->productSearch = '';
        $this->products = collect();
        $this->dispatch('productsUpdated');
    }


    public function updatedProductSearch($value): void
    {
        if ($this->operator) {
            $this->products = Product::where('operator_id', $this->operator)
                ->where('visible', true)
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')
                        ->orWhere('price', 'like', '%' . $value . '%');
                })
                ->orderBy('name')
                ->get(['id', 'name', 'price']);
        } else {
            $this->products = collect();
        }
        $this->dispatch('productSearchUpdated');
    }

    public function selectProduct($productId): void
    {
        $prod = new ProductService();
        $product = $prod->get($productId);
        if ($product) {
            $this->product_id = $product->id;
            $this->productSearch = $product->name;
            $this->price = $product->price;
            $this->products = collect();
            $this->dispatch('productSelected', ['productName' => $product->name]);
        }
    }

    //    public function addItem(): void
    //    {
    //
    //        $this->validate([
    //            'product_id' => 'required',
    //            'order_type_id' => 'required',
    //            'price' => 'required|numeric|min:0',
    //            'qty' => 'required|integer|min:1'
    //        ], [
    //            'order_type_id.required' => 'Selecione o Tipo',
    //            'product_id.required' => 'O produto é obrigatório.',
    //            'price.required' => 'O preço é obrigatório.',
    //            'price.numeric' => 'O preço deve ser um valor numérico.',
    //            'price.min' => 'O preço deve ser um valor positivo.',
    //            'qty.required' => 'A quantidade é obrigatória.',
    //            'qty.integer' => 'A quantidade deve ser um número inteiro.',
    //            'qty.min' => 'A quantidade deve ser pelo menos 1.'
    //        ]);
    //
    //        $factor = $this->updateFactors();
    //        $linesArray = $this->number ? explode(',', $this->number) : [null];
    //        $total = 0;
    //
    //        foreach ($linesArray as $line) {
    //            $qty = is_numeric($this->qty) ? (int)$this->qty : 1;
    //            $subtotal = $this->price * $qty;
    //
    //            if ($this->editIndex !== null) {
    //                $this->items[$this->editIndex] = [
    //                    'id' => $this->itemId ?? '',
    //                    'opportunity_id' => $this->opportunity_id,
    //                    'operator' => $this->operator,
    //                    'number' => trim($line),
    //                    'product_id' => $this->product_id,
    //                    'order_type_id' => $this->order_type_id,
    //                    'price' => $this->price ?? 0,
    //                    'qty' => $qty,
    //                    'subtotal' => $subtotal,
    //                    'factor' => $factor->name ?? 0
    //                ];
    //                $this->editIndex = null;
    //            } else {
    //                $this->items[] = [
    //                    'operator' => $this->operator,
    //                    'number' => trim($line),
    //                    'product_id' => $this->product_id,
    //                    'order_type_id' => $this->order_type_id,
    //                    'price' => $this->price ?? 0,
    //                    'qty' => $qty,
    //                    'subtotal' => $subtotal,
    //                    'factor' => $factor->name ?? 0
    //                ];
    //            }
    //
    //        }
    //
    //
    //        session(['items' => $this->items]);
    //
    //        $this->calculateTotals();
    //
    //        $this->dispatch('closeModal', modalId: '#itemForm');
    //
    //        $this->reset('operator', 'order_type_id', 'number', 'product_id', 'price', 'qty');
    //
    //        $this->filteredItems = $this->items;
    //
    //        $this->updateOpportunityTotal();
    //
    //    }
    public function addItem(): void
    {
        $this->validate([
            'product_id' => 'required',
            'order_type_id' => 'required',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1'
        ], [
            'order_type_id.required' => 'Solicitação é obrigatória.',
            'product_id.required' => 'O produto é obrigatório.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um valor numérico.',
            'price.min' => 'O preço deve ser um valor positivo.',
            'qty.required' => 'A quantidade é obrigatória.',
            'qty.integer' => 'A quantidade deve ser um número inteiro.',
            'qty.min' => 'A quantidade deve ser pelo menos 1.'
        ]);

        $factor = $this->updateFactors();
        $linesArray = $this->number ? explode(',', $this->number) : [null];
        $total = 0;

        foreach ($linesArray as $line) {
            $qty = is_numeric($this->qty) ? (int)$this->qty : 1;
            $subtotal = $this->price * $qty;

            $itemData = [
                'operator' => $this->operator,
                'number' => trim($line),
                'product_id' => $this->product_id,
                'order_type_id' => $this->order_type_id,
                'price' => $this->price ?? 0,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'factor' => $factor->name ?? 0
            ];

            if ($this->editIndex !== null) {
                $this->items[$this->editIndex] = array_merge(['id' => $this->itemId ?? '', 'opportunity_id' => $this->opportunity_id], $itemData);
                $this->editIndex = null;
            } else {
                $this->items[] = $itemData;
            }
        }

        session(['items' => $this->items]);

        $this->calculateTotals();

        $this->dispatch('closeModal', modalId: '#itemForm');

        $this->reset('operator', 'order_type_id', 'number', 'product_id', 'price', 'qty');

        $this->filteredItems = $this->items;

        $this->updateOpportunityTotal();
    }

    public function getSessionItems()
    {
        return session('items');
    }

    public function editItem($index): void
    {
        $this->calculateTotals();
        $this->openModal();
        $prod = new ProductService();
        $item = $this->items[$index];
        $product = $prod->get($item['product_id']);

        $this->itemId = $item['id'];
        $this->operator = $item['operator'] ?? '';
        $this->number = $item['number'];
        $this->product_id = (int)$item['product_id'];
        $this->order_type_id = (int)$item['order_type_id'];
        $this->price = $item['price'];
        $this->qty = $item['qty'];
        $this->editIndex = $index;
        $this->productSearch = $product ? $product->name : '';
    }

    public function removeItem($index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexa o array após remover um item
        session(['items' => $this->items]);
        $this->filteredItems = $this->items;

        $totals = $this->calculateTotals();
        $this->totalQty = $totals['totalQty'];
        $this->totalValue = $totals['totalValue'];
        $this->updateOpportunityTotal();
    }

    public function updateOpportunityTotal(): void
    {
        if ($this->opportunity_id) {
            $totals = $this->calculateTotals();
            $this->totalQty = $totals['totalQty'];
            $this->totalValue = $totals['totalValue'];

            $opportunity = Opportunity::where('id', $this->opportunity_id)->first();
            if ($opportunity) {
                $opportunity->total = $this->totalValue;
                $opportunity->save();
            }
        }
    }


    public function clearItems(): void
    {
        $this->items = [];
        $this->filteredItems = [];
        session()->forget('items');
    }

    public function updateFactors()
    {
        if ($this->operator && $this->order_type_id) {
            return Factor::where('operator_id', $this->operator)
                ->where('order_type_id', $this->order_type_id)
                ->first();
        }
        return null;
    }


    public function calculateTotals()
    {
        $totalQty = 0;
        $totalValue = 0;

        if (is_array($this->items) || is_object($this->items)) {
            foreach ($this->items as $item) {
                $totalQty += $item['qty'];
                $totalValue += $item['price'] * $item['qty'];
            }
        }

        $this->totalQty = $totalQty;
        $this->totalValue = $totalValue;

        return [
            'totalQty' => $totalQty,
            'totalValue' => $totalValue,
        ];
    }

    public function getProductName($productId)
    {
        $prod = new ProductService();
        $product = $prod->get($productId);
        return $product ? $product['name'] : '';
    }

    public function getOrderTypeName($orderTypeId)
    {
        $order_type = collect($this->order_types)->firstWhere('id', $orderTypeId);
        return $order_type ? $order_type['name'] : '';
    }


    private function paginate($items, $perPage = 20, $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $items = $items instanceof Collection ? $items : Collection::make($items);

        $path = request()->url();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            array_merge(['path' => $path, 'pageName' => 'page'], $options)
        );
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = array_keys($this->items);
        } else {
            $this->selectedItems = [];
        }
    }

    public function openBulkEditModal()
    {
        if (is_array($this->selectedItems) && count($this->selectedItems) > 1) {

            $this->dispatch('openBulkEditModal', modalId: '#bulkEditModal');
        }
    }


    public function updateSelectedItems()
    {
        foreach ($this->selectedItems as $index) {
            $qty = is_numeric($this->qty) ? (int)$this->qty : 1;
            $subtotal = $this->price * $qty;
            $this->items[$index]['product_id'] = $this->product_id;
            $this->items[$index]['operator'] = $this->operator;
            $this->items[$index]['price'] = $this->price ?? 0;
        }

        $this->calculateTotals();

        $this->filteredItems = $this->items;

        session(['items' => $this->items]);

        $this->dispatch('closeModal', modalId: '#bulkEditModal');
    }


    public function render()
    {
        $totals = $this->calculateTotals();

        return view('livewire.pages.opportunities.items.product-modal', [
            'totalQty' => $totals['totalQty'],
            'totalValue' => $totals['totalValue'],
            'data' => $this->paginate($this->filteredItems),
        ]);
    }

    public function openModal(): void
    {
        $this->reset('operator', 'order_type_id', 'number', 'product_id', 'price', 'qty', 'productSearch');
        $this->dispatch('openModal', modalId: '#itemForm');
    }

    public function closeModal(): void
    {
        $this->dispatch('closeModal', modalId: '#itemForm');
    }
}
