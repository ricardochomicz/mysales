<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProtocolRequest;
use App\Models\Client;
use App\Models\Operator;
use App\Models\Tag;
use App\Services\ProtocolService;
use App\Services\TagService;
use App\Traits\TypeTags;
use Illuminate\Http\Request;

class ProtocolController extends Controller
{
    USE TypeTags;
    public function __construct(protected ProtocolService $protocolService, protected TagService $tagService)
    {
    }

    public function index()
    {
        $view = [];
        return view('pages.protocols.index', $view);
    }


    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $view = [
            'status' => $this->protocolStatus(),
            'operators' => Operator::all(),
            'tags' => $this->tagService->toSelectProtocol()
        ];
        return view('pages.protocols.create', $view);
    }


    public function store(ProtocolRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->protocolService->store($request->all());
            notyf()->success('Protocolo cadastrado com sucesso.');
            return to_route('protocols.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }


    public function edit($id)
    {
        $protocol = $this->protocolService->get($id);

        if (!$protocol) {
            notify()->addError('Registro não encontrado.');
            return back();
        }
        $view = [
            'data' => $protocol,
            'status' => $this->protocolStatus(),
            'operators' => Operator::all(),
            'tags' => $this->tagService->toSelectProtocol()
        ];
        return view('pages.protocols.edit', $view);
    }


    public function update(ProtocolRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->protocolService->update($request->all(), $id);
            notyf()->success('Protocolo atualizado com sucesso.');
            return to_route('protocols.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

    public function destroy($id)
    {
        $protocol = $this->protocolService->get($id, true);
        $this->protocolService->destroy($id);
        if ($protocol->deleted_at == null) {
            notyf()->success('Protocolo desativado com sucesso.');
            return redirect()->route('protocols.index');
        } elseif ($protocol->deleted_at != null) {
            notyf()->success('Protocolo reativado com sucesso.');
            return redirect()->route('protocols.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Protocolo.');
            return back();
        }
    }
}
