<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Plan;
use App\Services\ModuleService;
use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanModuleController extends Controller
{
    public function __construct(protected PlanService $planService, protected ModuleService $moduleService)
    {
    }

    public function modules($idPlan)
    {
        if (!$plan = $this->planService->get($idPlan)) {
            notyf()->warning('Plano não encontrado.');
            return back();
        }

        $modules = $plan->modules()->paginate();

        return view('adm.plans.modules.index', compact('plan', 'modules'));
    }

    public function modulesAvailable($idPlan)
    {
        if (!$plan = $this->planService->get($idPlan)) {
            notyf()->warning('Plano não encontrado.');
            return back();
        }

        $modules = $plan->modulesAvailable();

        return view('adm.plans.modules.available', compact('plan', 'modules'));
    }

    public function create($idPlan)
    {
        if (!$plan = $this->planService->get($idPlan)) {
            notyf()->warning('Plano não encontrado.');
            return back();
        }
        return view('adm.plans.modules.create', compact('plan'));
    }

    public function attachModulePlan(Request $request, $idPlan)
    {
        if (!$plan = $this->planService->get($idPlan)) {
            notyf()->warning('Plano não encontrado.');
            return back();
        }

        if (!$request->modules || count($request->modules) == 0) {
            notyf()->warning('Você precisa selecionar ao menos um módulo para concluir.');
            return back();
        }

        $plan->modules()->attach($request->modules);

        notyf()->success('Módulo vinculado com sucesso.');

        return redirect()->route('plans.modules', ['id' => $idPlan]);
    }

    public function detachModulePlan($idPlan, $idModule)
    {
        $plan = $this->planService->get($idPlan);
        $module = $this->moduleService->get($idModule);

        if (!$plan || !$module) {
            notyf()->warning('Nenhum registro encontrado.');
            return back();
        }

        $detach = $plan->modules()->detach($module);

        notyf()->success('Módulo desvinculado com sucesso.');

        return redirect()->route('plans.modules', ['id' => $idPlan]);
    }
}
