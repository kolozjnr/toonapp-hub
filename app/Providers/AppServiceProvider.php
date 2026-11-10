<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the DB cart with the pages that show it so the drawer
        // is server-rendered on every page (guests identified by cookie).
        View::composer(['layouts.guest', 'customer.dashboard'], function (ViewInstance $view): void {
            $payload = app(CartService::class)->payload();

            $view->with([
                'cartItems' => $payload['items'],
                'cartCount' => $payload['count'],
                'cartTotal' => $payload['total'],
            ]);
        });
    }
}
