<?php

namespace App\Providers;

use App\Models\CompanySubscription;
use App\Services\MenuBuilder;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;   // ⬅️ add
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 🔒 Queue rate limit used by your jobs via: new RateLimited('adtraction-api')
        RateLimiter::for('adtraction-api', function ($job) {
            return [ Limit::perMinute(30)->by('adtraction-global') ];
        });

        // Inertia shared props (your existing code)
        Inertia::share([
            'auth' => [
                'user' => fn () => auth()->user()?->load('roles', 'permissions'),
            ],
            'menu' => fn () => MenuBuilder::build(auth()->user()),
            'subscription_warning' => function () {
                $user = Auth::user();

                if ($user && $user->company_id) {
                    $active = CompanySubscription::where('company_id', $user->company_id)
                        ->where('status', 'active')
                        ->where('end_date', '>=', Carbon::now())
                        ->exists();

                    if (!$active) {
                        return 'Your company subscription has expired. Please renew your subscription.';
                    }
                }

                return null;
            },
        ]);
    }
}
