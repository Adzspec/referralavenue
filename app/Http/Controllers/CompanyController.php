<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyFrontendSetting;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;
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
                'homePage' => 'layout1',
            ],
        ]);


        return redirect()->back()->with('success', 'Company and admin user created successfully.');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:companies,email,{$company->id}",
            'domain' => "required|string|unique:companies,domain,{$company->id}",
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
}
