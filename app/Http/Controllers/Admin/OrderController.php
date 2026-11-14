<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderDownloadMail;
use App\Models\Payment;
use App\Models\User;
use App\Services\OrderStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show every order in the store, newest first.
     */
    public function index(Request $request, OrderStats $stats): View
    {
        $search = trim((string) $request->query('q'));

        $orders = Payment::query()
            ->with('user')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('reference', 'like', "%{$search}%")
                        ->orWhere('items', 'like', "%{$search}%")
                        ->orWhereHas('user', function (Builder $query) use ($search): void {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders', [
            'orders' => $orders,
            'summary' => $stats->summary(),
        ]);
    }

    /**
     * Email the download links for one order back to the shopper who paid
     * for it, so an inbox that lost them can get them again.
     */
    public function resend(Payment $payment): RedirectResponse
    {
        $buyer = $payment->user;

        if (! $payment->isSuccessful()) {
            return back()->with(
                'error',
                "Order {$payment->reference} has not been paid, so there is nothing to download."
            );
        }

        if (! $buyer instanceof User) {
            return back()->with('error', "Order {$payment->reference} has no customer to send the links to.");
        }

        $downloads = $payment->downloadLinks();

        if ($downloads === []) {
            return back()->with('error', 'None of the plugins on that order has a download link yet.');
        }

        Mail::to($buyer)->send(new OrderDownloadMail(
            $buyer->name,
            $payment->reference,
            $downloads,
        ));

        return back()->with('success', 'Download links sent to '.$buyer->email.'.');
    }
}
