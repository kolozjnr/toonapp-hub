<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderDownloadMail;
use App\Models\Payment;
use App\Models\User;
use App\Services\PurchaseHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

    /**
     * Email the download links for one of their orders back to the shopper
     * who paid for it, so an inbox that lost them can get them again.
     */
    public function resend(Request $request, Payment $payment): RedirectResponse
    {
        $buyer = $request->user();

        // An order is only ever listed to the shopper who placed it, so
        // somebody else's order may as well not exist as far as they go.
        abort_unless($buyer instanceof User && $payment->user_id === $buyer->id, 404);

        if (! $payment->isSuccessful()) {
            return back()->with('error', 'That order has not been paid, so there is nothing to download.');
        }

        $downloads = $payment->downloadLinks();

        if ($downloads === []) {
            return back()->with('error', 'The download links for that order are not available yet.');
        }

        Mail::to($buyer)->send(new OrderDownloadMail(
            $buyer->name,
            $payment->reference,
            $downloads,
        ));

        return back()->with('success', 'Download links sent to '.$buyer->email.'.');
    }
}
