<?php

namespace App\Providers;

use App\Services\MenuBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'auth' => [
                'user' => fn () => auth()->user()?->load('roles', 'permissions'),
            ],
            'menu' => fn () => MenuBuilder::build(auth()->user()),
        ]);
    }
}
