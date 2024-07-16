<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('isSuperAdmin', function (User $user) {
            if (in_array(1, $user->roles->pluck('id')->toArray())) //super admin
                return true;
        });
        Gate::define('isAdmin', function (User $user) {
            if (in_array(2, $user->roles->pluck('id')->toArray()))
                return true;
        });

        Gate::define('manage-adm', function (User $user) {
            $roles = $user->roles->pluck('id')->toArray();
            // Admin, Manage, Administrative
             if(in_array(2, $roles) || in_array(3, $roles) || in_array(5, $roles))
                 return true;
        });

        Gate::define('manage-users', function (User $user) {
            $roles = $user->roles->pluck('id')->toArray();
            if(in_array(2, $roles) || in_array(3, $roles) || in_array(4, $roles))
                return true;
        });

        Gate::define('isAdm', function (User $user) {
            if (in_array(5, $user->roles->pluck('id')->toArray()))
                return true;
        });


        Gate::before(function (User $user) {
            if (in_array(1, $user->roles->pluck('id')->toArray())) //super admin
                return true;
        });


        /**
         * 1 - SuperAdmin
         * 2 - Admin
         * 3 - Manager (Gerente)
         * 4 - Supervisor
         * 5 - Administrativo
         * 6 - Usuário
         */
    }
}
