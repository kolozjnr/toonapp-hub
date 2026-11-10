<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OrderStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * How many shoppers are listed on each page of the table.
     */
    private const PER_PAGE = 15;

    /**
     * Show every shopper registered in the store, newest first.
     */
    public function index(Request $request, OrderStats $stats): View
    {
        $search = trim((string) $request->query('q'));

        $customers = User::query()->shoppers()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('country', 'like', "%{$search}%");
                });
            })
            ->withCount(['payments as orders_count' => fn (Builder $query): Builder => $this->settled($query)])
            ->withSum(['payments as spent' => fn (Builder $query): Builder => $this->settled($query)], 'amount')
            ->latest()
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.customers', [
            'customers' => $customers,
            'summary' => $this->summary(),
            'currency' => $stats->currency(),
            'subscriberEmails' => $this->subscriberEmails(),
        ]);
    }

    /**
     * Limit a payments relation to the checkouts the gateway settled, so a
     * customer's order count and lifetime spend only move on a real charge.
     */
    private function settled(Builder $query): Builder
    {
        $query->successful();

        return $query;
    }

    /**
     * The numbers behind the cards above the table.
     *
     * @return array{customers: int, buyers: int, newsletter: int}
     */
    private function summary(): array
    {
        return [
            'customers' => User::query()->shoppers()->count(),
            'buyers' => User::query()->shoppers()
                ->whereHas('payments', fn (Builder $query): Builder => $this->settled($query))
                ->count(),
            'newsletter' => User::query()->shoppers()->where('newsletter', true)->count(),
        ];
    }

    /**
     * The addresses behind the "copy subscriber emails" button, ready to paste
     * into an email client.
     */
    private function subscriberEmails(): string
    {
        return User::query()->shoppers()
            ->where('newsletter', true)
            ->orderBy('email')
            ->pluck('email')
            ->implode(', ');
    }
}
