<?php

namespace App\Livewire\Pages\Opportunities\Items;

use App\Models\Factor;
use App\Models\Product;
use App\Services\OperatorService;
use App\Services\OrderTypeService;
use App\Services\ProductService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public int $itemId;

    public array $items = [];
    public $operator_id = null;
    public $order_type_id = null;
    public $number = '';
    public $product_id = null;
    public $price = '';
    public $qty = 1;
    public $prods = [];
    public $products;
    public $order_types = [];
    public $total = '';
    public $search = '';
    public $factor = '';
    public $editIndex = null;

    public bool $showModal = false;

    protected $listeners = [
        'productSelected' => 'updatePrice',
        'refreshSelect',
        'resetModal',

    ];

    protected $rules = [
        'operator_id' => 'required',
    ];

    public function mount()
    {
        $this->prods = $this->getProducts();
        $this->order_types = $this->getOrderTypes();
        if (session()->has('items')) {
            $this->items = session('items');
        }
    }

    private function getProducts()
    {
        $products = new ProductService();
        return $products->toSelect();
    }

    private function getOrderTypes()
    {
        $orderTypes = new OrderTypeService();
        return $orderTypes->toSelect();
    }

    public function updatedOperatorId(int $value)
    {
        $this->updateFactors();
        $prod = new ProductService();
        $this->products = $prod->toSelectWithPrice($value);
    }

    public function updatedProductId($value): void
    {
        $product = collect($this->products)->firstWhere('id', $value);
        $this->price = $product ? $product['price'] : '';
    }


    public function updatePrice($price)
    {
        $this->price = $price;
    }

    public function addItem(): void
    {
        $factor = $this->updateFactors();
        $linesArray = $this->number ? explode(',', $this->number) : [null];

        foreach ($linesArray as $line) {
            $product = collect($this->prods)->firstWhere('id', $this->product_id);
            $price = $product ? $product['price'] : 0;

            $qty = is_numeric($this->qty) ? (int)$this->qty : 1;
            $total = $price * $qty;

            if ($this->editIndex !== null) {
                $this->items[$this->editIndex] = [
                    'operator_id' => $this->operator_id,
                    'number' => trim($line),
                    'product_id' => $this->product_id,
                    'order_type_id' => $this->order_type_id,
                    'price' => $price,
                    'qty' => $qty,
                    'total' => $total,
                    'factor' => $factor->name,
                ];
                $this->editIndex = null;
            } else {
                $this->items[] = [
                    'operator_id' => $this->operator_id,
                    'number' => trim($line),
                    'product_id' => $this->product_id,
                    'order_type_id' => $this->order_type_id,
                    'price' => $price,
                    'qty' => $qty,
                    'total' => $total,
                    'factor' => $factor->name,
                ];
            }
        }

        session(['items' => $this->items]);

        $this->dispatch('closeModal', modalId: '#itemForm');

        $this->reset('operator_id', 'order_type_id', 'number', 'product_id', 'price', 'qty');

        $this->dispatch('refreshSelect');
    }


    public function editItem($index): void
    {
        $this->openModal();
        $item = $this->items[$index];

        $this->operator_id = $item['operator_id'];
        $this->number = $item['number'];
        $this->product_id = (int)$item['product_id'];
        $this->order_type_id = (int)$item['order_type_id'];
        $this->price = $item['price'];
        $this->qty = $item['qty'];
        $this->editIndex = $index;

        $this->dispatch('refreshSelect');

    }

    public function updatedQty($value)
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        foreach ($this->items as $key => $item) {
            $price = is_numeric($item['price']) ? $item['price'] : 0;
            $qty = is_numeric($item['qty']) ? $item['qty'] : 1;

            $this->items[$key]['total'] = $price * $qty;
        }
    }

    public function clearItems(): void
    {
        session()->forget('items');
        $this->items = [];
    }

    public function calculateTotals()
    {
        $totalQty = 0;
        $totalValue = 0;

        foreach ($this->items as $item) {
            $totalQty += $item['qty'];
            $totalValue += $item['total'];
        }

        return [
            'totalQty' => $totalQty,
            'totalValue' => $totalValue,
        ];
    }


    public function render()
    {
        $totals = $this->calculateTotals();
        $operators = new OperatorService();
        $order_types = new OrderTypeService();
        return view('livewire.pages.opportunities.items.form', [
            'operators' => $operators->toSelect(),

            'order_types' => $order_types->toSelect(),
            'totalQty' => $totals['totalQty'],
            'totalValue' => $totals['totalValue'],
        ]);
    }

    public function removeItem($index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexa o array após remover um item

        session(['items' => $this->items]);
    }

    #[On('resetModal')]
    public function resetModal(): void
    {
        $this->reset('operator_id', 'order_type_id', 'number', 'product_id', 'price', 'qty');
    }

    public function getProductName($productId)
    {
        $product = collect($this->prods)->firstWhere('id', $productId);
        dd($product);
        return $product ? $product['name'] : '';
    }

    public function getOrderTypeName($orderTypeId)
    {
        $order_type = collect($this->order_types)->firstWhere('id', $orderTypeId);
        return $order_type ? $order_type['name'] : '';
    }


    public function updatedOrderTypeId($value)
    {
        $this->order_type_id = $value;
        $this->updateFactors();
    }

    public function updateFactors()
    {
        if ($this->operator_id && $this->order_type_id) {
            return Factor::where('operator_id', $this->operator_id)
                ->where('order_type_id', $this->order_type_id)
                ->first();
        }
        return null;
    }


    public function openModal()
    {
        $this->dispatch('openModal', modalId: '#itemForm');

    }

    public function closeModal()
    {
        $this->dispatch('closeModal', modalId: '#itemForm');
        $this->dispatch('refreshSelect');
    }
}
