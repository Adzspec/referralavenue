<?php

// app/Http/Controllers/CompanyFrontendSettingController.php

namespace App\Http\Controllers;

use App\Models\CompanyIntegration;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\CompanyFrontendSetting;
use Inertia\Inertia;

class CompanyFrontendSettingsController extends Controller
{
    // Display settings
    public function show()
    {
        $company = auth()->user()->company;
        return Inertia::render('frontend_settings/index', [
            'settings' => $company->frontendSetting ? $company->frontendSetting->settings : [],
            'adtraction' => CompanyIntegration::where('company_id', auth()->user()->company_id)
                ->where('provider','adtraction')
                ->first(),
            'addrevenue' => CompanyIntegration::where('company_id', auth()->user()->company_id)
                ->where('provider','addrevenue')
                ->first(),
            'tradedoubler' => CompanyIntegration::where('company_id', auth()->user()->company_id)
                ->where('provider', 'tradedoubler')
                ->first(),
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
}

