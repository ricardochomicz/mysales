<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.super.admin' => \App\Http\Middleware\IsSuperAdmin::class,
            'check.admin' => \App\Http\Middleware\IsAdmin::class,
            'check.module' => \App\Http\Middleware\CheckTenantModule::class,
            'check.manage.adm' => \App\Http\Middleware\CheckManageAdm::class,
            'check.manage.users' => \App\Http\Middleware\CheckManageUsers::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
