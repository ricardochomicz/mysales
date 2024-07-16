<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderTypeRequest;
use App\Models\OrderType;
use App\Services\BaseService;
use App\Services\OrderTypeService;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{

    private $service;
    private $typeService;
    public function __construct()
    {
        $this->service = new BaseService(OrderType::class);
        $this->typeService = new OrderTypeService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.order_types.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.order_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderTypeRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Tipo Pedido cadastrado com sucesso.');
            return redirect()->route('order-types.index');
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
            notyf()->error("Ops! Tipo Pedido não encontrado.");
            return back();
        }
        $view = [
            'data' => $data,
        ];
        return view('pages.order_types.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderTypeRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Tipo Pedido atualizado com sucesso.');
            return redirect()->route('order-types.index');
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
        $order_type = $this->service->get($id, true);
        $this->typeService->destroy($id);
        if ($order_type->deleted_at == null) {
            notyf()->success('Tipo Pedido desativado com sucesso.');
            return redirect()->route('order-types.index');
        } elseif ($order_type->deleted_at != null) {
            notyf()->success('Tipo Pedido reativado com sucesso.');
            return redirect()->route('order-types.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Tipo Pedido.');
            return back();
        }
    }
}
