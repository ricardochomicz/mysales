<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassificationRequest;
use App\Models\Classification;
use App\Services\BaseService;
use App\Services\ClassificationService;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    private $service;
    public function __construct(protected ClassificationService $classificationService)
    {
        $this->service = new BaseService(Classification::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.classifications.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.classifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassificationRequest $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Classificação cadastrada com sucesso.');
            return redirect()->route('classifications.index');
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
            notyf()->error("Ops! Classificação não encontrada.");
            return back();
        }
        $view = [
            'data' => $data,
        ];
        return view('pages.classifications.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassificationRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Classificação atualizada com sucesso.');
            return redirect()->route('classifications.index');
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
        $classification = $this->service->get($id, true);
        $this->classificationService->destroy($id);
        if ($classification->deleted_at == null) {
            notyf()->success('Classificação desativada com sucesso.');
            return redirect()->route('classifications.index');
        } elseif ($classification->deleted_at != null) {
            notyf()->success('Classificação reativada com sucesso.');
            return redirect()->route('classifications.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Classificação.');
            return back();
        }
    }
}
