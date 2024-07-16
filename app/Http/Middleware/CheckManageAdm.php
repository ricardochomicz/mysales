<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckManageAdm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Gate::denies('manage-adm')) {
            // Redirecionar de volta com uma mensagem de erro
            notyf()->error("Você não tem permissão para acessar esta área.");
            return redirect()->back();
        }

        return $next($request);
    }
}
