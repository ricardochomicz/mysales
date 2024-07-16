<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Operator;
use App\Models\Opportunity;
use App\Models\Protocol;
use App\Services\BaseService;
use App\Services\ClassificationService;
use App\Services\ClientService;
use App\Services\OperatorService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $service;
    private $clientService;
    private $operatorService;
    private $classificationService;
    private $userService;

    public function __construct()
    {
        $this->service = new BaseService(Client::class);
        $this->clientService = new ClientService();
        $this->operatorService = new OperatorService();
        $this->classificationService = new ClassificationService();
        $this->userService = new UserService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.clients.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'operators' => $this->operatorService->toSelect()
        ];
        return view('pages.clients.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        try {
            $this->clientService->store($request->all());
            notyf()->success('Cliente cadastrado com sucesso.');
            return to_route('opportunities.create');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::with(['user', 'operator', 'orders'])
            ->where(['tenant_id' => auth()->user()->tenant->id])->find($id);
        if (!$client) {
            notyf()->error('Registro não encontrado.');
            return back();
        }

        $view = [
            'client' => $client,
        ];

        return view('pages.clients.show', $view);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->service->get($id);
        if (!$data) {
            notyf()->error("Ops! Cliente não encontrado.");
            return back();
        }
        $view = [
            'data' => $data,
            'users' => $this->userService->toSelectUserClient(),
            'operators' => $this->operatorService->toSelect(),
            'classifications' => $this->classificationService->toSelect()
        ];
        return view('pages.clients.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, $id)
    {
        try {
            $this->clientService->update($request->all(), $id);
            notyf()->success('Cliente atualizado com sucesso.');
            return redirect()->route('clients.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    public function autocomplete(Request $request)
    {
        $params = $request->input('query');
        $filterResult = Client::with('user')->where('tenant_id', auth()->user()->tenant->id)->where(function ($query) use ($params) {
            $query->where('document', 'LIKE', '%' . $params . '%')
                ->orWhere('name', 'LIKE', '%' . $params . '%');
        })->get();

        return response()->json($filterResult);
    }

    public function myProtocols($uuid)
    {
        $client = Client::where('uuid', $uuid)->first();

        $view = [
            'client' => $client,
            'protocols' => Protocol::orderBy('created_at', 'desc')->where('client_id', $client->id)->paginate()
        ];

        return view('pages.clients.my-protocols', $view);
    }

    public function getClientDocument($doc)
    {
        return Client::where('tenant_id', auth()->user()->tenant->id)->where('document', $doc)->first();
    }

    public function getClient($id)
    {
        $client = Client::with('user', 'persons')
            ->where('tenant_id', auth()->user()->tenant->id)
            ->where('id', $id)
            ->first();
        if (!$client) {
            flash()->addError('Registro não encontrado.');
            return back();
        }

        return response()->json($client);
    }
}
