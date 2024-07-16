<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected RoleService $roleService, protected UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.users.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('manage-users');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return back();
        }
        $view = [
            'roles' => $this->roleService->toSelect()
        ];
        return view('pages.users.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->userService->store($request->all());
            notyf()->success('Usuário cadastrado com sucesso.');
            return redirect()->route('users.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = $this->userService->get($id, true);
        if(!$user){
            notyf()->error("Ops! Usuário não encontrado.");
            return back();
        }
        $view = [
            'roles' => $this->roleService->toSelect(),
            'data' => $user,
        ];
        return view('pages.users.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->userService->update($request->all(), $id);
            notyf()->success('Usuário atualizado com sucesso.');
            return redirect()->route('users.index');
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
        $user = $this->userService->get($id, true);
        $this->userService->destroy($id);
        if ($user->deleted_at == null) {
            notyf()->success('Usuário desativado com sucesso.');
            return redirect()->route('users.index');
        } elseif ($user->deleted_at != null) {
            notyf()->success('Usuário reativado com sucesso.');
            return redirect()->route('users.index');
        } else {
            notyf()->error('Ops! Erro ao atualizar Usuário.');
            return back();
        }
    }
}
