<?php

// app/Http/Controllers/CompanyFrontendSettingController.php

namespace App\Http\Controllers;

use App\Models\CompanyIntegration;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\CompanyFrontendSetting;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CompanyFrontendSettingsController extends Controller
{
    // Display settings
    public function show()
    {
//        $company = auth()->user()->company;
        $company = auth()->user()->company()->with('latestSubscription.subscription')->first();

        $customHome = $company->latestSubscription->subscription->getFeatureValue('home_data_customize');
        return Inertia::render('frontend_settings/homepage', [
            'settings' => $company->frontendSetting ? $company->frontendSetting->settings : [],
            'customHome' => (bool)((int)$customHome),
            'can' => [
                'create' => auth()->user()->can('create company settings'),
                'edit' => auth()->user()->can('edit company settings'),
                'delete' => auth()->user()->can('delete company settings'),
            ],
        ]);
    }

    // Store (create) settings
    public function store(Request $request)
    {
        $company = auth()->user()->company;
        $data = $request->validate(['settings' => 'required|array']);

        $setting = CompanyFrontendSetting::create([
            'company_id' => $company->id,
            'settings' => $data['settings'],
        ]);

        return response()->json(['settings' => $setting->settings, 'success' => true]);
    }

    // Update settings
    public function update(Request $request)
    {
        $company = auth()->user()->company;
        $data = $request->validate(['settings' => 'required|array']);

        $company->frontendSetting()->updateOrCreate(
            ['company_id' => $company->id],
            ['settings' => $data['settings']]
        );

        return redirect()->back()->with('success', 'Settings updated successfully.');
//        return response()->json(['settings' => $company->frontendSetting->settings, 'success' => true]);
    }

    // Delete settings (reset to empty)
    public function destroy()
    {
        $company = auth()->user()->company;
        if ($company->frontendSetting) {
            $company->frontendSetting->delete();
        }
        return response()->json(['success' => true]);
    }

    public function fileupload(Request $request)
    {

        $user = auth()->user();
        $company = $user->company; // Adjust if your relation is named differently

        // Validation
        $request->validate([
            'uploadedfile' => 'required|image|max:2048', // 2MB
        ]);

        // Make safe folder name from company name (slug)
        $companyFolder = Str::slug($company->name);

        // Store file in 'logos/{company-name}/'
        $path = $request->file('uploadedfile')->store("{$companyFolder}", 'public');

        // (Optional) Update company's logo path in database
        // $company->logo = $path;
        // $company->save();

        return response()->json([
            'logo_url' => asset('storage/' . $path),
            'path' => $path,
            'success' => true,
        ]);
    }
}

