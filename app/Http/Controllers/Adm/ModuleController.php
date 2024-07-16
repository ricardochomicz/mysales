<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Services\ModuleService;

class ModuleController extends Controller
{
    public function __construct(protected ModuleService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('adm.modules.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('adm.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Módulo cadastrado com sucesso.');
            return redirect()->route('modules.index');
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
        $view = [
            'module' => $this->service->get($id)
        ];
        return view('adm.modules.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Módulo atualizado com sucesso.');
            return redirect()->route('modules.index');
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
        $module = $this->service->get($id, true);
        $this->service->destroy($id);
        if ($module->deleted_at === null) {
            notyf()->success('Módulo desativado com sucesso.');
            return redirect()->route('modules.index');
        } elseif ($module->deleted_at != null) {
            notyf()->success('Módulo reativado com sucesso.');
            return redirect()->route('modules.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Módulo.');
            return back();
        }
    }
}
