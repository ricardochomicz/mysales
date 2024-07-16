<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Services\OperatorService;
use App\Services\OpportunityService;
use App\Services\OrderTypeService;
use App\Traits\TypeTags;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    use TypeTags;

    private $opportunityService;
    private $operatorService;
    private $orderTypeService;

    public function __construct()
    {
        $this->opportunityService = new OpportunityService();
        $this->operatorService = new OperatorService();
        $this->orderTypeService = new OrderTypeService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.opportunities.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'operators' => $this->operatorService->toSelect(),
            'order_types' => $this->orderTypeService->toSelect(),
            'funnel' => $this->typeFunnel()
        ];
        return view('pages.opportunities.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->opportunityService->store($request->all());
            notyf()->success('Oportunidade cadastrada com sucesso.');
            return redirect()->route('opportunities.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Opportunity $opportunity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Opportunity::find($id);
        if (!$data) {
            notyf()->error("Ops! Oportunidade não encontrada.");
            return back();
        }
        $view = [
            'operators' => $this->operatorService->toSelect(),
            'order_types' => $this->orderTypeService->toSelect(),
            'funnel' => $this->typeFunnel(),
            'data' => $data,
        ];
        return view('pages.opportunities.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->opportunityService->update($request->all(), $id);
            notyf()->success('Oportunidade atualizada com sucesso.');
            return redirect()->route('opportunities.index');
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
        $opportunity = $this->opportunityService->get($id, true);
        $this->opportunityService->destroy($id);
        if ($opportunity->deleted_at == null) {
            notyf()->success('Oportunidade desativada com sucesso.');
            return redirect()->route('opportunities.index');
        } elseif ($opportunity->deleted_at != null) {
            notyf()->success('Oportunidade reativada com sucesso.');
            return redirect()->route('opportunities.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Oportunidade.');
            return back();
        }
    }

    public function opportunityGain($id)
    {
        $opportunity = $this->opportunityService->get($id);
        $this->opportunityService->opportunityGain($opportunity->id);

        $content = 'Enviado BKO';

        $this->opportunityService->comment($content, $opportunity->id, $opportunity->client_id);

        notyf()->success('Enviado BKO com sucesso.');
        return redirect()->route('opportunities.index');
    }


}
