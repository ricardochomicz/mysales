<?php

namespace App\Livewire\Pages\Products;

use App\Models\Category;
use App\Models\Operator;
use App\Models\Product;
use App\Services\BaseService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $trashed = '';

    #[Url(history: true)]
    public string $category = '';

    #[Url(history: true)]
    public string $operator = '';

    public $orderBy = ['visible', 'name']; // campos de ordenação
    public $orderDirection = 'desc'; // direções de ordenação

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'trashed' => ['except' => ''],
            'category' => ['except' => ''],
            'operator' => ['except' => ''],
        ];
    }

    protected $listeners = ['resetSelectpicker' => '$refresh'];

    public function render()
    {
        $model = new BaseService(Product::class);
        $operators = new BaseService(Operator::class);
        $categories = new BaseService(Category::class);

        $filters = [
            'search' => $this->search,
            'trashed' => $this->trashed,
            'category' => $this->category,
            'operator' => $this->operator,
        ];

        return view('livewire.pages.products.table', [
            'data' => $model->index($filters, $this->orderBy, $this->orderDirection),
            'operators' => $operators->toSelect(),
            'categories' => $categories->toSelect(),
        ]);
    }

    public function clearFilter()
    {
        $this->search = '';
        $this->trashed = '';
        $this->category = '';
        $this->operator = '';
        $this->dispatch('resetSelectpicker');
    }

    public function visible($id)
    {
        $product = Product::where('tenant_id', auth()->user()->tenant_id)->find($id);
        if ($product) {
            $product->update(['visible' => !$product->visible]);
        }
    }
}
