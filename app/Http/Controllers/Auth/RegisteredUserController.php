<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/', // At least one uppercase
                'regex:/[0-9]/', // At least one number
                'regex:/[@$!%*?&#^()_+\-=\[\]{};\'\\:"|,.<>\/?]/', // At least one special character
            ],
        ], [
            'password.regex' => 'Password must be at least 8 characters and contain an uppercase letter, a number, and a special character.',
        ]);
        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => 1,
        ]);

        CompanySubscription::create([
            'company_id' => $company->id,
            'subscription_id' => 1,
            'start_date' => now(),
            'status' => 'active',
        ]);

        $user = User::create([
            'name' => $request->name.' Admin',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
        ]);
        $user->assignRole('company admin');

        event(new Registered($user));
        Auth::login($user);

        return to_route('dashboard');
    }
}
