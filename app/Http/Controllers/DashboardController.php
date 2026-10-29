<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect to the right dashboard after login.
     */
    public function __invoke(): RedirectResponse
    {
        $user = Auth::user();

        if ($user?->hasAnyRole(['superadmin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    /**
     * Show the store dashboard (admin panel).
     */
    public function admin(): \Illuminate\View\View
    {
        $users = User::latest()->take(10)->get();

        return view('admin.dashboard', ['users' => $users]);
    }

    /**
     * Show the customer dashboard.
     */
    public function customer(): \Illuminate\View\View
    {
        $user = Auth::user();

        return view('customer.dashboard', ['user' => $user]);
    }
}
