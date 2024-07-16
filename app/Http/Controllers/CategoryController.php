<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\BaseService;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    private $service;
    public function __construct(protected CategoryService $categoryService)
    {
        $this->service = new BaseService(Category::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.categories.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Categoria cadastrada com sucesso.');
            return redirect()->route('categories.index');
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
            notyf()->error("Ops! Categoria não encontrada.");
            return back();
        }
        $view = [
            'data' => $data,
        ];
        return view('pages.categories.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Categoria atualizada com sucesso.');
            return redirect()->route('categories.index');
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
        $category = $this->service->get($id, true);
        $this->categoryService->destroy($id);
        if ($category->deleted_at == null) {
            notyf()->success('Categoria desativada com sucesso.');
            return redirect()->route('categories.index');
        } elseif ($category->deleted_at != null) {
            notyf()->success('Categoria reativada com sucesso.');
            return redirect()->route('categories.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Categoria.');
            return back();
        }
    }
}
