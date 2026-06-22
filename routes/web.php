<?php

use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    CheckoutController,
    OrderController,
    DashboardController,
    PageController,
    ProfileController,
    RecommendationController
};
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Kasir\TransactionController as KasirTransactionController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/track-order', [PageController::class, 'trackOrder'])->name('order.track');
Route::post('/track-order', [PageController::class, 'searchOrder'])->name('order.search');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

//Search
// Route::get('/search/global', [SearchController::class, 'index'])->name('search.global');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});


// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });

});

// ============================================
// ADMIN / INTERNAL ROUTES
// ============================================
Route::get('/dashboard-redirect', function () {
    $role = auth()->user()->role;
    if (in_array($role, ['owner', 'admin', 'superadmin'])) {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'kasir') {
        return redirect()->route('kasir.pos');
    }
    return redirect()->route('dashboard');
})->middleware('auth')->name('dashboard-redirect');

// ['role:owner,admin,superadmin']
Route::middleware(['auth', 'role:owner,admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    
    // Legacy orders for online checkout
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::patch('orders/{order}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('orders.confirm');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
});

// ['role:owner,kasir,superadmin']
Route::middleware(['auth', 'role:owner,kasir,superadmin'])->prefix('admin/kasir')->name('kasir.')->group(function () {
    Route::get('/', fn () => redirect()->route('kasir.pos'));
    Route::get('/pos', [\App\Http\Controllers\Kasir\KasirController::class, 'index'])->name('pos');
    Route::post('/pos/store', [\App\Http\Controllers\Kasir\KasirController::class, 'store'])->name('store');
    Route::get('/pos/receipt/{id}', [\App\Http\Controllers\Kasir\KasirController::class, 'printReceipt'])->name('receipt');
});

// ['role:owner,superadmin']
Route::middleware(['auth', 'role:owner,superadmin'])->prefix('admin/owner')->name('owner.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'update']);
    Route::post('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.role');
    // Financial reports can go here
});

// ============================================
// AUTH ROUTES (Laravel Breeze/UI)
// ============================================
require __DIR__.'/auth.php';
