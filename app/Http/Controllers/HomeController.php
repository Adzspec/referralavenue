<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Subscription::query()->where('status',1)->get();
        return Inertia::render('Welcome', [
            'plans' => $plans,
            'user' => auth()->user(),
        ]);
    }
}
