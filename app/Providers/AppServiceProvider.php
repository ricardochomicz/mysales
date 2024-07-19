<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Opportunity;
use App\Models\Plan;
use App\Models\Tenant;
use App\Observers\ClientObserver;
use App\Observers\OpportunityObserver;
use App\Observers\PlanObserver;
use App\Observers\TenantObserver;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale(config('app.locale'));
        Plan::observe(PlanObserver::class);
        Tenant::observe(TenantObserver::class);
        Client::observe(ClientObserver::class);
        Opportunity::observe(OpportunityObserver::class);
    }
}
