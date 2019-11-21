<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // blade directive to check if user has specific role
        Blade::if('role', function ($expression) {
            $user = Auth::user();
            return $user->hasRole($expression);
        });

        // check if User was trashed (soft deleted)
        Blade::if('wastrashed', function ($expression) {
            return User::withTrashed()->find($expression)->trashed();
        });
    }
}
