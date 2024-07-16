<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\BaseService;
use App\Services\TeamService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private $service;
    private $teamService;
    public function __construct()
    {
        $this->service = new BaseService(Team::class);
        $this->teamService = new TeamService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.teams.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->service->store($request->all());
            notyf()->success('Equipe cadastrada com sucesso.');
            return redirect()->route('teams.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $teams)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->service->get($id);
        if(!$data){
            notyf()->error("Ops! Equipe não encontrada.");
            return back();
        }
        $view = [
            'data' => $data,
        ];
        return view('pages.teams.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Equipe atualizada com sucesso.');
            return redirect()->route('teams.index');
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
        $team = $this->service->get($id, true);
        $this->teamService->destroy($id);
        if ($team->deleted_at == null) {
            notyf()->success('Equipe desativada com sucesso.');
            return redirect()->route('teams.index');
        } elseif ($team->deleted_at != null) {
            notyf()->success('Equipe reativada com sucesso.');
            return redirect()->route('teams.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Equipe.');
            return back();
        }
    }

    public function members($id)
    {
        $team = $this->teamService->get($id);
        $view = [
            'data' => $team,
        ];
        return view('pages.teams.members.index', $view);
    }

    public function editMembers($id)
    {
        $team = $this->teamService->get($id);
        $members = $team->members;

        $view = [
            'data' => $team,
            'members' => $members
        ];
        return view('pages.teams.members.index', $view);
    }
}
