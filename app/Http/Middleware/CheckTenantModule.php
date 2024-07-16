<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        $user = $request->user(); // Supondo que você tenha uma relação entre usuário e tenant
        if (!$user) {
            abort(403, 'Usuário não autenticado.');
        }

        $tenant = $user->tenant; // Acesso ao tenant do usuário
        if (!$tenant) {
            abort(403, 'Empresa não encontrada.');
        }
        $tenantModules = $tenant->plan->modules->pluck('module')->toArray();

        if (!in_array($module, $tenantModules)) {
//            abort(403, 'Accesso negado.');
             return response()->view('access_denied');
        }

        return $next($request);
    }
}
