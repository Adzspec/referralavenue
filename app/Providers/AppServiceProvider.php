<?php

namespace App\Providers;

use App\Models\CompanySubscription;
use App\Services\MenuBuilder;
use Carbon\Carbon;
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

            'subscription_warning' => function () {
                $user = Auth::user();

                if ($user && $user->company_id) {
                    $activeSubscription = CompanySubscription::where('company_id', $user->company_id)
                        ->where('status', 'active')
                        ->where('end_date', '>=', Carbon::now())
                        ->exists();

                    if (!$activeSubscription) {
                        return 'Your company subscription has expired. Please renew your subscription.';
                    }
                }

                return null;
            },
        ]);

    }
}
