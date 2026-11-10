<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdateController extends Controller
{
    /**
     * List the updates for the plugins this shopper owns, newest first.
     */
    public function index(Request $request, PurchaseHistory $history): View
    {
        return view('customer.updates', [
            'updates' => $history->updates($request->user()),
        ]);
    }
}
