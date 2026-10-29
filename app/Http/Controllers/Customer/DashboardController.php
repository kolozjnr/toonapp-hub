<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the customer dashboard.
     */
    public function show()
    {
        return view('customer.dashboard');
    }
}
