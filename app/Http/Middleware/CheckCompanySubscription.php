<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanySubscription;
use Carbon\Carbon;

class CheckCompanySubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware for authentication-related routes
        if ($request->is('login*') || $request->is('register*') || $request->is('logout*') ||
            $request->is('forgot-password*') || $request->is('reset-password*') ||
            $request->is('verify-email*') || $request->is('email/verification-notification*') ||
            $request->is('confirm-password*') || $request->is('subscriptions*')) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user is authenticated and has a company_id
        if ($user && $user->company_id) {
            // Get the latest active subscription for the company
            $subscription = CompanySubscription::where('company_id', $user->company_id)
                ->where('status', 'active')
                ->where('end_date', '>=', Carbon::now())
                ->latest('end_date')
                ->first();

            // If no active subscription found, redirect to company_subscriptions page
//            if (!$subscription) {
//                // Don't redirect if already on the company_subscriptions page to avoid redirect loops
//                if (!$request->is('/*')) {
//                    return redirect()->route('home')
//                        ->with('warning', 'Your company subscription has expired. Please renew your subscription to continue.');
//                }
//            }
        }

        return $next($request);
    }
}
