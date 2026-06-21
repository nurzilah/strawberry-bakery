<?php

use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    CheckoutController,
    OrderController,
    DashboardController,
    PageController,
    ProfileController
};
use App\Http\Controllers\Auth\GoogleController;
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
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    
    // Only Admin and Superadmin can manage products and categories
    Route::middleware([\App\Http\Middleware\ManagerMiddleware::class])->group(function() {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
    });
    
    // Only Superadmin can manage users
    Route::middleware([\App\Http\Middleware\SuperadminMiddleware::class])->group(function() {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'update']);
        Route::post('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.role');
    });
});

// ============================================
// AUTH ROUTES (Laravel Breeze/UI)
// ============================================
require __DIR__.'/auth.php';
