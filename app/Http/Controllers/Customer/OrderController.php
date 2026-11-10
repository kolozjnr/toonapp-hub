<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * List the orders this shopper placed, newest first, with the totals they
     * were charged.
     */
    public function index(Request $request, PurchaseHistory $history): View
    {
        $user = $request->user();

        $orders = Payment::query()
            ->whereBelongsTo($user)
            ->latest()
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('customer.orders', [
            'orders' => $orders,
            'summary' => $history->summary($user),
        ]);
    }
}
