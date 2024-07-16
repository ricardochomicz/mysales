<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorRequest;
use App\Models\Operator;
use App\Services\BaseService;
use App\Services\OperatorService;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    protected $service;
    public function __construct(protected OperatorService $operatorService)
    {
        $this->service = new BaseService(Operator::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view("pages.operators.index", $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('pages.operators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperatorRequest $request)
    {
        try {
            $this->operatorService->store($request->all());
            notyf()->success('Operadora cadastrada com sucesso.');
            return redirect()->route('operators.index');
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
        $data = $this->operatorService->get($id);
        if(!$data){
            notyf()->error("Ops! Operadora não encontrada.");
            return back();
        }
        $view = [
            'data' => $data,
        ];
        return view('pages.operators.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperatorRequest $request, $id)
    {
        try {
            $this->operatorService->update($request->all(), $id);
            notyf()->success('Operadora atualizada com sucesso.');
            return redirect()->route('operators.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

}
