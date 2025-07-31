<?php

use App\Http\Controllers\AddrevenueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyFrontendSettingsController;
use App\Http\Controllers\CompanyIntegrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CompanySubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dedededed', [HomeController::class, 'transactions']);

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');
Route::get('/faqs', function () {
    return Inertia::render('Faqs');
})->name('faqs');
Route::get('/services', function () {
    return Inertia::render('Services');
})->name('services');
Route::get('/company', function () {
    return Inertia::render('Company');
})->name('company');
Route::get('/blog', function () {
    return Inertia::render('Blog');
})->name('blog');
Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/subscriptions/checkout', [SubscriptionController::class, 'createCheckoutSession']);
Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancelSubscription']);
Route::get('/subscriptions/success', function () {
    return Inertia::render('subscriptions/success');
});
Route::post('/stripe/payment-intent', [PaymentController::class, 'createIntent'])->name('stripe.payment-intent');
Route::post('/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');
Route::post('/fileupload', [CompanyFrontendSettingsController::class, 'fileupload']);


Route::get('/subscriptions/cancel', function () {
    return Inertia::render('Subscriptions/Cancel');
});
//Users Routes
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class)->except(['show'])->middleware(['permission:manage users']);
    Route::resource('roles', RoleController::class)->middleware(['permission:manage roles']);
    Route::resource('permissions', PermissionController::class)->middleware(['permission:manage permissions']);
    Route::resource('menus', MenuController::class)->middleware(['permission:manage menus']);
    Route::resource('companies', CompanyController::class)->middleware(['permission:manage companies']);
    Route::resource('subscriptions', SubscriptionController::class)->middleware(['permission:manage subscriptions']);
    Route::resource('company_subscriptions', CompanySubscriptionController::class);
    Route::post('company_subscriptions/{companySubscription}/cancel', [CompanySubscriptionController::class, 'cancel'])->name('company_subscriptions.cancel');
    Route::get('/company/company-settings', [CompanyController::class, 'companySettings'])->name('company.settings');
    Route::put('/company/update', [CompanyController::class, 'update']);
    Route::put('/companies/{company}/profile', [CompanyController::class, 'updateCompanyProfile']);
    Route::get('/company/integrations', [CompanyIntegrationController::class, 'index'])->middleware(['permission:manage integrations settings']);
    Route::put('/company/integrations/adtraction', [CompanyIntegrationController::class, 'adtraction']);
    Route::put('/company/integrations/addrevenue', [CompanyIntegrationController::class, 'addrevenue']);
    Route::put('/company/integrations/tradedoubler', [CompanyIntegrationController::class, 'tradedoubler']);
    Route::get('/company/home_settings', [CompanyFrontendSettingsController::class, 'show']);
    Route::post('/company/home_settings', [CompanyFrontendSettingsController::class, 'store']);
    Route::put('/company/home_settings', [CompanyFrontendSettingsController::class, 'update']);
    Route::delete('/company/home_settings', [CompanyFrontendSettingsController::class, 'destroy']);
    Route::resource('categories', CategoryController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('offers', OfferController::class);
    Route::post('/company/addrevenue/fetch', [AddrevenueController::class, 'startSync']);
    Route::get('/company/addrevenue/getProducts', [AddrevenueController::class, 'getProducts']);
    Route::post('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::post('/categories/bulk-status', [CategoryController::class, 'bulkStatus'])->name('categories.bulkStatus');
    Route::post('/stores/bulk-delete', [StoreController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::post('/stores/bulk-status', [StoreController::class, 'bulkStatus'])->name('categories.bulkStatus');
    Route::post('/offers/bulk-delete', [OfferController::class, 'bulkDelete'])->name('offers.bulkDelete');
    Route::post('/offers/bulk-status', [OfferController::class, 'bulkStatus'])->name('offers.bulkStatus');
    Route::post('/offers/bulk-featured', [OfferController::class, 'bulkFeatured']);
    Route::post('/offers/bulk-exclusive', [OfferController::class, 'bulkExclusive']);


});






// Include other route files
$routeFiles = [
    'settings.php',
    'auth.php'
];

foreach ($routeFiles as $file) {
    require __DIR__ . '/' . $file;
}
