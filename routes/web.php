<?php

use Illuminate\Support\Facades\Route;

//Route::get('register', \App\Http\Controllers\Auth\RegisterController::class)->name('register');

Route::group(['middleware' => 'auth', 'prefix' => 'app'], function () {

    Route::middleware(['check.super.admin'])->group(function () {
        /**
         * Plans x Modules
         */
        Route::get('plans/{id}/module/{idModule}', [\App\Http\Controllers\Adm\PlanModuleController::class, 'detachModulePlan'])->name('plans.modules.detach');
        Route::post('plans/{id}/modules', [\App\Http\Controllers\Adm\PlanModuleController::class, 'attachModulePlan'])->name('plans.modules.attach');
        Route::get('plans/{id}/modules/create', [\App\Http\Controllers\Adm\PlanModuleController::class, 'modulesAvailable'])->name('plans.modules.available');
        Route::get('plans/{id}/modules', [\App\Http\Controllers\Adm\PlanModuleController::class, 'modules'])->name('plans.modules');

        /**
         * Plans
         */
        Route::get('plans/{id}/edit', [\App\Http\Controllers\Adm\PlanController::class, 'edit'])->name('plans.edit');
        Route::put('plans/{id}/update', [\App\Http\Controllers\Adm\PlanController::class, 'update'])->name('plans.update');
        Route::get('plans/create', [\App\Http\Controllers\Adm\PlanController::class, 'create'])->name('plans.create');
        Route::post('plans', [\App\Http\Controllers\Adm\PlanController::class, 'store'])->name('plans.store');
        Route::get('plans', [\App\Http\Controllers\Adm\PlanController::class, 'index'])->name('plans.index');

        /**
         * Modules
         */
        Route::controller(\App\Http\Controllers\Adm\ModuleController::class)
            ->name('modules.')
            ->prefix('/modules')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/create', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/edit/{id}', 'update')->name('update');
                Route::get('/destroy/{id}', 'destroy')->name('destroy');
            });

    });

    Route::group(['middleware' => 'check.module:tenants'], function () {
        Route::get('tenants/{id}/edit', [\App\Http\Controllers\TenantController::class, 'edit'])->name('tenants.edit')->middleware('check.manage.adm');
        Route::put('tenants/{id}', [\App\Http\Controllers\TenantController::class, 'update'])->name('tenants.update')->middleware('check.manage.adm');
        Route::get('tenants/create', [\App\Http\Controllers\TenantController::class, 'create'])->name('tenants.create')->middleware('check.super.admin');
        Route::post('tenants', [\App\Http\Controllers\TenantController::class, 'store'])->name('tenants.store')->middleware('check.super.admin');
        Route::get('tenants', [\App\Http\Controllers\TenantController::class, 'index'])->name('tenants.index')->middleware('check.manage.adm');;
        Route::get('tenants/{id}', [\App\Http\Controllers\TenantController::class, 'destroy'])->name('tenants.destroy')->middleware('check.super.admin');
    });

    Route::middleware(['check.module:users'])->group(function () {
        Route::get('users/{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::get('users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::group(['middleware' => 'check.manage.adm'], function () {
        Route::get('operators/{id}/edit', [\App\Http\Controllers\OperatorController::class, 'edit'])->name('operators.edit');
        Route::put('operators/{id}', [\App\Http\Controllers\OperatorController::class, 'update'])->name('operators.update');
        Route::get('operators/create', [\App\Http\Controllers\OperatorController::class, 'create'])->name('operators.create');
        Route::post('operators', [\App\Http\Controllers\OperatorController::class, 'store'])->name('operators.store');
        Route::get('operators', [\App\Http\Controllers\OperatorController::class, 'index'])->name('operators.index');
        Route::get('operators/{id}', [\App\Http\Controllers\OperatorController::class, 'destroy'])->name('operators.destroy');
    });

    Route::group(['middleware' => ['check.manage.adm', 'check.module:categories']], function () {
        Route::get('categories/{id}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
        Route::get('categories/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::group(['middleware' => ['check.manage.adm', 'check.module:products']], function () {
        Route::get('products/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::get('products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
        Route::get('products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::group(['middleware' => ['check.manage.adm', 'check.module:teams']], function () {
        Route::get('teams/{id}/edit', [\App\Http\Controllers\TeamController::class, 'edit'])->name('teams.edit');
        Route::put('teams/{id}', [\App\Http\Controllers\TeamController::class, 'update'])->name('teams.update');
        Route::get('teams/create', [\App\Http\Controllers\TeamController::class, 'create'])->name('teams.create');
        Route::post('teams', [\App\Http\Controllers\TeamController::class, 'store'])->name('teams.store');
        Route::get('teams', [\App\Http\Controllers\TeamController::class, 'index'])->name('teams.index');
        Route::get('teams/{id}', [\App\Http\Controllers\TeamController::class, 'destroy'])->name('teams.destroy');
        Route::get('teams/{id}/members', [\App\Http\Controllers\TeamController::class, 'members'])->name('teams.members.index');
        Route::get('teams/{id}/members/edit', [\App\Http\Controllers\TeamController::class, 'editMembers'])->name('teams.members.edit');
    });

    Route::group(['middleware' => ['check.manage.adm', 'check.module:classifications']], function () {
        Route::get('classifications/{id}/edit', [\App\Http\Controllers\ClassificationController::class, 'edit'])->name('classifications.edit');
        Route::put('classifications/{id}', [\App\Http\Controllers\ClassificationController::class, 'update'])->name('classifications.update');
        Route::get('classifications/create', [\App\Http\Controllers\ClassificationController::class, 'create'])->name('classifications.create');
        Route::post('classifications', [\App\Http\Controllers\ClassificationController::class, 'store'])->name('classifications.store');
        Route::get('classifications', [\App\Http\Controllers\ClassificationController::class, 'index'])->name('classifications.index');
        Route::get('classifications/{id}', [\App\Http\Controllers\ClassificationController::class, 'destroy'])->name('classifications.destroy');
    });

    Route::group(['middleware' => ['check.manage.adm', 'check.module:order-types']], function () {
        Route::get('order-types/{id}/edit', [\App\Http\Controllers\OrderTypeController::class, 'edit'])->name('order-types.edit');
        Route::put('order-types/{id}', [\App\Http\Controllers\OrderTypeController::class, 'update'])->name('order-types.update');
        Route::get('order-types/create', [\App\Http\Controllers\OrderTypeController::class, 'create'])->name('order-types.create');
        Route::post('order-types', [\App\Http\Controllers\OrderTypeController::class, 'store'])->name('order-types.store');
        Route::get('order-types', [\App\Http\Controllers\OrderTypeController::class, 'index'])->name('order-types.index');
        Route::get('order-types/{id}', [\App\Http\Controllers\OrderTypeController::class, 'destroy'])->name('order-types.destroy');
    });

    Route::group(['middleware' => ['check.admin', 'check.module:factors']], function () {
        Route::get('factors/{id}/edit', [\App\Http\Controllers\FactorController::class, 'edit'])->name('factors.edit');
        Route::put('factors/{id}', [\App\Http\Controllers\FactorController::class, 'update'])->name('factors.update');
        Route::get('factors/create', [\App\Http\Controllers\FactorController::class, 'create'])->name('factors.create');
        Route::post('factors', [\App\Http\Controllers\FactorController::class, 'store'])->name('factors.store');
        Route::get('factors', [\App\Http\Controllers\FactorController::class, 'index'])->name('factors.index');
        Route::get('factors/{id}', [\App\Http\Controllers\FactorController::class, 'destroy'])->name('factors.destroy');
    });

    Route::group(['middleware' => ['check.admin', 'check.module:tags']], function () {
        Route::get('tags/{id}/edit', [\App\Http\Controllers\TagController::class, 'edit'])->name('tags.edit');
        Route::put('tags/{id}', [\App\Http\Controllers\TagController::class, 'update'])->name('tags.update');
        Route::get('tags/create', [\App\Http\Controllers\TagController::class, 'create'])->name('tags.create');
        Route::post('tags', [\App\Http\Controllers\TagController::class, 'store'])->name('tags.store');
        Route::get('tags', [\App\Http\Controllers\TagController::class, 'index'])->name('tags.index');
        Route::get('tags/{id}', [\App\Http\Controllers\TagController::class, 'destroy'])->name('tags.destroy');
    });

    Route::get('clients/getclient/{id}', [\App\Http\Controllers\ClientController::class, 'getClient'])->name('clients.getclient');
    Route::get('client/{id}/detail', [\App\Http\Controllers\ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{doc}/document', [\App\Http\Controllers\ClientController::class, 'getClientDocument'])->name('clients.document');
    Route::get('clients/{id}/edit', [\App\Http\Controllers\ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{id}', [\App\Http\Controllers\ClientController::class, 'update'])->name('clients.update');
    Route::get('clients/create', [\App\Http\Controllers\ClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [\App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
    Route::get('clients', [\App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/{id}/destroy', [\App\Http\Controllers\ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('clients/autocomplete', [\App\Http\Controllers\ClientController::class, 'autocomplete'])->name('clients.autocomplete');


    /**
     * Route Opportunities
     */
    Route::get('opportunities/{id}/detail', [\App\Http\Controllers\OpportunityController::class, 'show'])->name('opportunities.show');
    Route::get('opportunities/{id}/edit', [\App\Http\Controllers\OpportunityController::class, 'edit'])->name('opportunities.edit');
    Route::put('opportunities/{id}', [\App\Http\Controllers\OpportunityController::class, 'update'])->name('opportunities.update');
    Route::get('opportunities/create', [\App\Http\Controllers\OpportunityController::class, 'create'])->name('opportunities.create');
    Route::post('opportunities', [\App\Http\Controllers\OpportunityController::class, 'store'])->name('opportunities.store');
    Route::get('opportunities/{id}/destroy', [\App\Http\Controllers\OpportunityController::class, 'destroy'])->name('opportunities.destroy');
    Route::get('opportunities', [\App\Http\Controllers\OpportunityController::class, 'index'])->name('opportunities.index');
    Route::put('opportunities/update-kanban/{id}', [\App\Http\Controllers\OpportunityController::class, 'updateKanban'])->name('opportunities.update.kanban');
    Route::get('opportunities/{id}/gain', [\App\Http\Controllers\OpportunityController::class, 'opportunityGain'])->name('opportunities.gain');
    Route::put('update-forecast/{id}', [\App\Http\Controllers\OpportunityController::class, 'updateForecast'])->name('opportunities.update.forecast');
    Route::get('opportunity-proposal/{uuid}', [\App\Http\Controllers\OpportunityController::class, 'getPagePrint'])->name('opportunity-proposal');

    Route::get('orders/{id}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

    Route::get('wallet/{id}/clone', [\App\Http\Controllers\WalletController::class, 'cloneWallet'])->name('wallets.clone');
    Route::get('wallets', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallets.index');

    Route::get('protocols/{id}/edit', [\App\Http\Controllers\ProtocolController::class, 'edit'])->name('protocols.edit');
    Route::put('protocols/{id}', [\App\Http\Controllers\ProtocolController::class, 'update'])->name('protocols.update');
    Route::get('protocols/create', [\App\Http\Controllers\ProtocolController::class, 'create'])->name('protocols.create');
    Route::post('protocols', [\App\Http\Controllers\ProtocolController::class, 'store'])->name('protocols.store');
    Route::get('protocols', [\App\Http\Controllers\ProtocolController::class, 'index'])->name('protocols.index');
    Route::get('protocols/{id}', [\App\Http\Controllers\ProtocolController::class, 'destroy'])->name('protocols.destroy');

    Route::get('commissions', [\App\Http\Controllers\CommissionController::class, 'index'])->name('commissions.index');
});

Route::get('plan/{slug}', [\App\Http\Controllers\SiteController::class, 'plan'])->name('plan.subscription');
Route::get('/mycrm-plans', [\App\Http\Controllers\SiteController::class, 'index'])->name('site');
Route::get('my-protocols/client/{uuid}', [\App\Http\Controllers\ClientController::class, 'myProtocols'])->name('my-protocols');
Route::get('my-proposal/{uuid}', [\App\Http\Controllers\OpportunityController::class, 'myProposal'])->name('proposal');

Route::get('/', function(){
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
