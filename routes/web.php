<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PluginController as AdminPluginController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PluginsController as CustomerPluginsController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\SettingssController as CustomerSettingsController;
use App\Http\Controllers\Customer\UpdateController as CustomerUpdateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\ProfileController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/plugins/feed', [HomeController::class, 'feed'])->name('plugins.feed');
Route::get('/plugins/{plugin:slug}', [PluginController::class, 'show'])->name('plugins.show');
Route::get('/contact', fn (): View => view('contact', [
    'settings' => Setting::values(Setting::CONTACT_KEYS),
]))->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/dashboard', [DashboardController::class, '__invoke'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/cart/items', [CartController::class, 'items'])->name('cart.items');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'destroy'])->name('cart.remove');

// Paystack sends the shopper here after checkout; confirming the charge only
// needs the reference, so it stays reachable without a session.
Route::get('/payment/paystack/callback', [PaymentController::class, 'callback'])
    ->middleware('throttle:60,1')
    ->name('payment.paystack.callback');

Route::middleware(['auth'])->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [PaymentController::class, 'create'])->name('create');
    Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::post('/recheck', [PaymentController::class, 'recheck'])->name('recheck');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
});

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
        Route::get('plugins/{plugin}', [AdminPluginController::class, 'show'])->name('plugins.show');
        Route::get('plugins/{plugin}/edit', [AdminPluginController::class, 'edit'])->name('plugins.edit');
        Route::put('plugins/{plugin}', [AdminPluginController::class, 'update'])->name('plugins.update');
        Route::patch('plugins/{plugin}/visibility', [AdminPluginController::class, 'toggle'])->name('plugins.toggle');
        Route::delete('plugins/{plugin}', [AdminPluginController::class, 'destroy'])->name('plugins.destroy');
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders');
        Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers');
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter');
        Route::post('newsletter', [AdminNewsletterController::class, 'store'])->name('newsletter.store');
        Route::post('newsletter/{newsletter}/resend', [AdminNewsletterController::class, 'resend'])
            ->name('newsletter.resend');
        Route::delete('newsletter/{newsletter}', [AdminNewsletterController::class, 'destroy'])
            ->name('newsletter.destroy');
        Route::get('messages', [AdminMessageController::class, 'index'])->name('messages');
        Route::patch('messages/{message}', [AdminMessageController::class, 'updateRead'])->name('messages.read');
        Route::delete('messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('settings', [AdminSettingsController::class, 'show'])->name('settings');
        Route::put('settings/contact', [AdminSettingsController::class, 'updateContact'])->name('settings.contact');
        Route::put('settings/email', [AdminSettingsController::class, 'updateEmail'])->name('settings.email');
        Route::put('settings/email/test', [AdminSettingsController::class, 'testEmail'])->name('settings.email.test');
        Route::put('settings/login', [AdminSettingsController::class, 'updateAdmin'])->name('settings.login');
        Route::get('settings/backup', [AdminSettingsController::class, 'backup'])->name('settings.backup');
        Route::post('settings/restore', [AdminSettingsController::class, 'restore'])->name('settings.restore');
        Route::get('upload-plugin', fn () => redirect()->route('admin.plugins.create'))->name('upload-plugin');
    });

    // Customer area — only users with the "user" role
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('dashboard', [CustomerDashboardController::class, 'show'])->name('dashboard');
        Route::get('plugins', [CustomerPluginsController::class, 'index'])->name('plugins');
        Route::post('plugins/{plugin}/review', [CustomerReviewController::class, 'store'])->name('plugins.review');
        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders');
        Route::get('settings', [CustomerSettingsController::class, 'index'])->name('settings');
        Route::put('settings/profile', [CustomerSettingsController::class, 'updateProfile'])->name('settings.profile');
        Route::put('settings/password', [CustomerSettingsController::class, 'updatePassword'])->name('settings.password');
        Route::patch('settings/newsletter', [CustomerSettingsController::class, 'updateNewsletter'])->name('settings.newsletter');
        Route::get('updates', [CustomerUpdateController::class, 'index'])->name('updates');
    });
});

require __DIR__.'/auth.php';
