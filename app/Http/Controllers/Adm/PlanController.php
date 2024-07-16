<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Services\PlanService;

class PlanController extends Controller
{

    public function __construct(protected PlanService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('adm.plans.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adm.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Plano cadastrado com sucesso.');
            return redirect()->route('adm.plans.index');
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
        $data = [
            'plan' => $this->service->get($id)
        ];
        return view('adm.plans.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Plano atualizado com sucesso.');
            return redirect()->route('adm.plans.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

}
