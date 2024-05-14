<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\RolesUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        if (auth()->check()) {
            dd(auth()->user());
            $role = RolesUser::where('user_id', auth()->user()->id)->first();
            $roleName = $role ? Role::find($role->role_id)->name : null;
            View::share('role', $roleName);
        }
    }
}
