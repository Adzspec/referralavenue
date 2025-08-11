<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with counts based on user type.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->company_id) {
            $companyId = $user->company_id;

            $data = [
                'type' => 'company',
                'counts' => [
                    'offers' => Offer::where('company_id', $companyId)->count(),
                    'categories' => Category::where('company_id', $companyId)->count(),
                    'stores' => Store::where('company_id', $companyId)->count(),
                ],
            ];
        } else {
            $data = [
                'type' => 'admin',
                'counts' => [
                    'companies' => Company::count(),
                    'offers' => Offer::count(),
                    'categories' => Category::count(),
                    'stores' => Store::count(),
                ],
            ];
        }

        return Inertia::render('Dashboard', $data);
    }
}
