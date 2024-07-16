<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactorRequest;
use App\Models\Factor;
use App\Models\Operator;
use App\Models\OrderType;
use App\Services\BaseService;
use App\Services\FactorService;
use Illuminate\Http\Request;

class FactorController extends Controller
{
    private $service;
    private $orderService;
    private $operatorService;
    private $factorService;
    public function __construct()
    {
        $this->service = new BaseService(Factor::class);
        $this->orderService = new BaseService(OrderType::class);
        $this->operatorService = new BaseService(Operator::class);
        $this->factorService = new FactorService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.factors.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'order_types' => $this->orderService->toSelect(),
            'operators' => $this->operatorService->toSelect()
        ];
        return view('pages.factors.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FactorRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Fator Comissão cadastrado com sucesso.');
            return redirect()->route('factors.index');
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
            notyf()->error("Ops! Fator não encontrado.");
            return back();
        }
        $view = [
            'data' => $data,
            'order_types' => $this->orderService->toSelect(),
            'operators' => $this->operatorService->toSelect()
        ];
        return view('pages.factors.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FactorRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Fator Comissão atualizado com sucesso.');
            return redirect()->route('factors.index');
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
        $factor = $this->service->get($id, true);
        $this->factorService->destroy($id);
        if ($factor->deleted_at == null) {
            notyf()->success('Fator Comissão desativad com sucesso.');
            return redirect()->route('factors.index');
        } elseif ($factor->deleted_at != null) {
            notyf()->success('Fator Comissão reativado com sucesso.');
            return redirect()->route('factors.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Fator.');
            return back();
        }
    }
}
