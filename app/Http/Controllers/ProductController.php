<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Operator;
use App\Models\Product;
use App\Services\BaseService;
use App\Services\CategoryService;
use App\Services\OperatorService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $operatorService;
    private $categoryService;

    public function __construct(protected ProductService $service)
    {
        $this->operatorService = new BaseService(Operator::class);
        $this->categoryService = new BaseService(Category::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.products.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'operators' => $this->operatorService->toSelect(),
            'categories' => $this->categoryService->toSelect(),
        ];
        return view('pages.products.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Produto cadastrado com sucesso.');
            return redirect()->route('products.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->service->get($id);
        if(!$data){
            notyf()->error("Ops! Produto não encontrado.");
            return back();
        }
        $view = [
            'data' => $data,
            'operators' => $this->operatorService->toSelect(),
            'categories' => $this->categoryService->toSelect(),
        ];
        return view('pages.products.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Produto atualizado com sucesso.');
            return redirect()->route('products.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = $this->service->get($id, true);
        $this->service->destroy($id);
        if ($product->deleted_at == null) {
            notyf()->success('Produto desativado com sucesso.');
            return redirect()->route('products.index');
        } elseif ($product->deleted_at != null) {
            notyf()->success('Produto reativado com sucesso.');
            return redirect()->route('products.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Produto.');
            return back();
        }
    }
}
