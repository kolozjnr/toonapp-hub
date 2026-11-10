<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class UpdatesController extends Controller
{
    public function index()
    {
        return view('customer.updates');
    }
}
