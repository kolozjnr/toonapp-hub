<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function show(Request $request, PurchaseHistory $history): View
    {
        $user = $request->user();
        $owned = $history->plugins($user);

        return view('customer.dashboard', [
            'firstName' => Str::before(trim($user->name), ' '),
            'summary' => $history->summary($user),
            'owned' => $owned,
            'ownedCount' => $owned->count(),
            'recentPlugins' => $owned->take(3)->values(),
            'recentlyUpdated' => $history->recentlyUpdated($user),
            'updates' => $history->updates($user, 3),
            'recommendations' => $history->recommendations($user),
        ]);
    }
}
