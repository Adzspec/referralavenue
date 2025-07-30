<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyFrontendSetting;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    public function index(): Response
    {
        $companies = Company::with('profile')->paginate(10);

        return Inertia::render('companies/index', [
            'companies' => $companies,
            'can' => [
                'create' => auth()->user()->can('create companies'),
                'edit' => auth()->user()->can('edit companies'),
                'delete' => auth()->user()->can('delete companies'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'domain' => 'required|string|unique:companies,domain',
            'registration_no' => 'nullable|string',
            'vat' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $company = Company::create($validated);

        // Create user for the company
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
            'company_id' => $company->id,
        ]);

        // Ensure the 'company admin' role exists
        $role = Role::firstOrCreate(['name' => 'company admin']);
        $user->assignRole($role);
        CompanyFrontendSetting::create([
            'company_id' => $company->id,
            'settings' => [
                'homePage'      => 'homeOne',
                'primaryColor'  => '#c5497d'
            ],
        ]);


        return redirect()->back()->with('success', 'Company and admin user created successfully.');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('companies', 'email')->ignore($company->id),
            ],
            'domain' => [
                'required',
                'string',
                Rule::unique('companies', 'domain')->ignore($company->id),
            ],
            'registration_no' => 'nullable|string',
            'vat' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $company->update($validated);

//        $company->profile()->updateOrCreate(
//            ['company_id' => $company->id],
//            [
//                'phone' => $validated['phone'] ?? null,
//                'address' => $validated['address'] ?? null,
//                'zipcode' => $validated['zipcode'] ?? null,
//                'city' => $validated['city'] ?? null,
//                'state' => $validated['state'] ?? null,
//            ]
//        );

        return redirect()->back()->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->back()->with('success', 'Company deleted.');
    }

    public function companySettings()
    {
        $company = Company::with(['profile','latestSubscription.subscription'])
            ->where('id', auth()->user()->company_id)
            ->first();

        $subscriptions = \App\Models\CompanySubscription::with('subscription')
            ->where('company_id', $company->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('frontend_settings/company', [
            'company' => $company,
            'subscriptions' => $subscriptions,
            'can' => [
                'create' => auth()->user()->can('create companies'),
                'edit' => auth()->user()->can('edit companies'),
                'delete' => auth()->user()->can('delete companies'),
            ],
        ]);
    }

    public function updateCompanyProfile(Request $request, Company $company)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:255',
        ]);
        $company->profile()->updateOrCreate(
            ['company_id' => $company->id],
            $validated
        );

        return redirect()->back()->with('success', 'Profile updated!');
    }

}
