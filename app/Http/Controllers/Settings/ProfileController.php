<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user()->load('company.profile');

        // Check if the user is a company admin and has a company_id
        $canUpdateCompanyProfile = $user->hasRole('company admin') && $user->company_id;

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'companyProfile' => $canUpdateCompanyProfile
                ? $user->company->profile()->first([
                    'phone',
                    'zipcode',
                    'country',
                    'city',
                    'state'
                ])
                : null,
            'canUpdateCompanyProfile' => $canUpdateCompanyProfile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validate full request
        $validated = $request->validated();

        // Update user info
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // If user is company admin and has a company_id, update company profile
        if ($user->hasRole('company admin') && $user->company_id) {
            $company = $user->load('company.profile')->company;

            $companyProfileData = array_filter([
                'phone' => $validated['phone'] ?? null,
                'zipcode' => $validated['zipcode'] ?? null,
                'country' => $validated['country'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
            ], fn ($value) => !is_null($value));

            if ($company) {
                $company->profile()->updateOrCreate(
                    [], // No conditions means it checks by foreign key (company_id) automatically
                    $companyProfileData
                );
            }
        }

        return to_route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
