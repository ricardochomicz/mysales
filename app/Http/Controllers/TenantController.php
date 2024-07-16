<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use App\Services\PlanService;
use App\Services\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{

    public function __construct(protected PlanService $planService, protected TenantService $tenantService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.tenants.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'plans' => $this->planService->toSelect()
        ];
        return view('pages.tenants.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantRequest $request)
    {
        try {
            $this->tenantService->store($request->all());
            notyf()->success('Empresa cadastrada com sucesso.');
            return redirect()->route('tenants.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tenant = $this->tenantService->get($id, true);
        if(!$tenant){
            notyf()->error("Ops! Empresa não encontrada.");
            return back();
        }
        $view = [
            'plans' => $this->planService->toSelect(),
            'data' => $tenant,
        ];
        return view('pages.tenants.edit', $view);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->tenantService->update($request->all(), $id);
            notyf()->success('Empresa atualizada com sucesso.');
            return redirect()->route('tenants.index');
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
        $tenant = $this->tenantService->get($id, true);
        $this->tenantService->destroy($id);
        if ($tenant->deleted_at == null) {
            notyf()->success('Empresa desativada com sucesso.');
            return redirect()->route('tenants.index');
        } elseif ($tenant->deleted_at != null) {
            notyf()->success('Empresa reativada com sucesso.');
            return redirect()->route('tenants.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Empresa.');
            return back();
        }
    }

}
