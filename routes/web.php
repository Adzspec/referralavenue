<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyFrontendSettingsController;
use App\Http\Controllers\CompanyIntegrationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CompanySubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Users Routes
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('company_subscriptions', CompanySubscriptionController::class);
    Route::post('company_subscriptions/{companySubscription}/cancel', [CompanySubscriptionController::class, 'cancel'])->name('company_subscriptions.cancel');
    Route::get('/company/settings', [CompanyFrontendSettingsController::class, 'show']);
    Route::post('/company/settings', [CompanyFrontendSettingsController::class, 'store']);
    Route::put('/company/settings', [CompanyFrontendSettingsController::class, 'update']);
    Route::delete('/company/settings', [CompanyFrontendSettingsController::class, 'destroy']);
    Route::put('/company/integrations/adtraction', [CompanyIntegrationController::class, 'adtraction']);
    Route::put('/company/integrations/addrevenue', [CompanyIntegrationController::class, 'addrevenue']);
    Route::put('/company/integrations/tradedoubler', [CompanyIntegrationController::class, 'tradedoubler']);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
});

Route::post('/stripe/payment-intent', [PaymentController::class, 'createIntent'])->name('stripe.payment-intent');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');


// Include other route files
$routeFiles = [
    'settings.php',
    'auth.php'
];

foreach ($routeFiles as $file) {
    require __DIR__ . '/' . $file;
}
