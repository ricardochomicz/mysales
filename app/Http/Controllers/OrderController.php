<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Services\TagService;
use App\Traits\TypeTags;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService, protected TagService $tagService)
    {
    }

    public function index()
    {
        $view = [];
        return view('pages.orders.index', $view);
    }

    public function edit($id)
    {
        $opportunity = $this->orderService->get($id);
        $view = [
            'status' => $this->tagService->toSelectOrderStatus(),
            'data' => $opportunity
        ];
        return view('pages.orders.edit', $view);
    }

    public function update(OrderRequest $request, $id)
    {
        try {
            $this->orderService->update($request->all(), $id);
            notyf()->success('Pedido atualizado com sucesso.');
            return redirect()->route('orders.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }
}
