<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PluginsController extends Controller
{
    /**
     * List the plugins this shopper owns, newest first, plus the review they
     * have already left on each one keyed by plugin id.
     */
    public function index(Request $request, PurchaseHistory $history): View
    {
        $user = $request->user();

        return view('customer.plugins', [
            'plugins' => $history->plugins($user),
            'myReviews' => Review::query()
                ->whereBelongsTo($user)
                ->get()
                ->keyBy('plugin_id'),
        ]);
    }
}
