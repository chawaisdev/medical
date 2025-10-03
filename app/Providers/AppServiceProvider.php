<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
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
   public function boot()
{
    View::composer('*', function ($view) {
        $permissions = [];

        if (Auth::check()) {
            $user = Auth::user();

            // check if role exists
            if ($user->role) {
                // load permissions safely
                $permissions = $user->role->permissions()->pluck('name')->toArray();
            }
        }

        $view->with('permissions', $permissions);
    });
}
}
