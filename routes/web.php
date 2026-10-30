<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PluginController as AdminPluginController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/dashboard', [DashboardController::class, '__invoke'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin area — only superadmin + admin
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'show'])->name('dashboard');
        Route::get('plugins', [AdminPluginController::class, 'index'])->name('plugins');
        Route::get('plugins/create', [AdminPluginController::class, 'create'])->name('plugins.create');
        Route::post('plugins', [AdminPluginController::class, 'store'])->name('plugins.store');
        Route::get('plugins/{plugin}/edit', [AdminPluginController::class, 'edit'])->name('plugins.edit');
        Route::put('plugins/{plugin}', [AdminPluginController::class, 'update'])->name('plugins.update');
        Route::get('orders', function () {
            return view('admin.orders');
        })->name('orders');
        Route::get('customers', function () {
            return view('admin.customers');
        })->name('customers');
        Route::get('reviews', function () {
            return view('admin.reviews');
        })->name('reviews');
        Route::get('newsletter', function () {
            return view('admin.newsletter');
        })->name('newsletter');
        Route::get('messages', function () {
            return view('admin.messages');
        })->name('messages');
        Route::get('settings', function () {
            return view('admin.settings');
        })->name('settings');
        Route::get('upload-plugin', fn () => redirect()->route('admin.plugins.create'))->name('upload-plugin');
    });

    // Customer area — only users with the "user" role
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('dashboard', [CustomerDashboardController::class, 'show'])->name('dashboard');
        Route::get('plugins', function () {
            return view('customer.plugins');
        })->name('plugins');
        Route::get('orders', function () {
            return view('customer.orders');
        })->name('orders');
        Route::get('settings', function () {
            return view('customer.settings');
        })->name('settings');
        Route::get('updates', function () {
            return view('customer.updates');
        })->name('updates');
    });
});

require __DIR__.'/auth.php';
