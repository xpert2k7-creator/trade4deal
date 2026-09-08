<?php

use App\Http\Controllers\Employee\LeadModerationController;
use App\Http\Controllers\Employee\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\LeadController;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\SellerProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::get('/', HomeController::class)->name('home');
Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('leads.store');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::post('/leads/{lead}/contact', [LeadController::class, 'contact'])
    ->middleware('throttle:8,1')
    ->name('leads.contact');

Route::get('/sellers/{slug}', [SellerProfileController::class, 'show'])->name('sellers.show');
Route::post('/sellers/{slug}/contact', [SellerProfileController::class, 'contact'])
    ->middleware('throttle:8,1')
    ->name('sellers.contact');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user?->isSeller()) {
        return redirect()->route('seller.dashboard');
    }
    if ($user?->canModerateLeads()) {
        return redirect()->route('employee.dashboard');
    }

    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::middleware('auth')->group(function () {
    Route::post('/plans/gold/checkout', [PlanController::class, 'checkout'])->name('plans.gold.checkout');
    Route::get('/plans/gold/success', [PlanController::class, 'success'])->name('plans.gold.success');
    Route::get('/plans/gold/cancel', [PlanController::class, 'cancel'])->name('plans.gold.cancel');
});

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::middleware(['auth', 'role:seller|admin'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [SellerDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [SellerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/products', [SellerDashboardController::class, 'products'])->name('products.index');
    Route::get('/products/create', [SellerDashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerDashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerDashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [SellerDashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [SellerDashboardController::class, 'destroyProduct'])->name('products.destroy');
});

Route::middleware(['auth', 'role:admin|employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/', [LeadModerationController::class, 'dashboard'])->name('dashboard');
    Route::get('/leads/create', [LeadModerationController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadModerationController::class, 'store'])->name('leads.store');
    Route::get('/leads', [LeadModerationController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}/edit', [LeadModerationController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadModerationController::class, 'update'])->name('leads.update');
    Route::post('/leads/{lead}/approve', [LeadModerationController::class, 'approve'])->name('leads.approve');
    Route::post('/leads/{lead}/reject', [LeadModerationController::class, 'reject'])->name('leads.reject');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/plan', [UserManagementController::class, 'updatePlan'])->name('users.plan.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
