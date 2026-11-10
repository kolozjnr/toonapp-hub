<?php

namespace App\Services;

use App\Models\Payment;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * OrderStats
 *
 * The order numbers shown on the admin cards and the order list.
 *
 * An order is stored as a `payments` row: the cart total is saved when the
 * shopper clicks Pay now and only becomes revenue once the gateway confirmed
 * the charge, so pending and failed checkouts never inflate the totals.
 */
class OrderStats
{
    /**
     * The rolling window behind the "this week" cards, in days.
     */
    private const WEEK_DAYS = 7;

    /**
     * The counters and ready-to-print amounts behind the admin cards.
     *
     * @return array{
     *     orders: int,
     *     paid: int,
     *     awaiting: int,
     *     failed: int,
     *     revenue: int,
     *     revenue_label: string,
     *     orders_this_week: int,
     *     revenue_this_week: int,
     *     revenue_this_week_label: string,
     *     average: int,
     *     average_label: string,
     *     currency: string
     * }
     */
    public function summary(): array
    {
        $allTime = $this->totals();
        $thisWeek = $this->totals(now()->subDays(self::WEEK_DAYS));
        $currency = $this->currency();
        $average = $allTime['paid'] > 0 ? (int) round($allTime['revenue'] / $allTime['paid']) : 0;

        return [
            'orders' => $allTime['orders'],
            'paid' => $allTime['paid'],
            'awaiting' => $allTime['awaiting'],
            'failed' => $allTime['failed'],
            'revenue' => $allTime['revenue'],
            'revenue_label' => Payment::formatAmount($allTime['revenue'], $currency),
            'orders_this_week' => $thisWeek['orders'],
            'revenue_this_week' => $thisWeek['revenue'],
            'revenue_this_week_label' => Payment::formatAmount($thisWeek['revenue'], $currency),
            'average' => $average,
            'average_label' => Payment::formatAmount($average, $currency),
            'currency' => $currency,
        ];
    }

    /**
     * The newest orders, newest first, with the shopper who placed them.
     *
     * @return Collection<int, Payment>
     */
    public function latest(int $limit = 5): Collection
    {
        return Payment::query()
            ->with('user')
            ->latest()
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Paid revenue per plugin, taken from the cart snapshot on each order,
     * highest first.
     *
     * @return Collection<int, array{name: string, revenue: int, label: string, width: int}>
     */
    public function salesByPlugin(string $currency, int $limit = 6): Collection
    {
        $sales = Payment::query()
            ->successful()
            ->pluck('items')
            ->flatMap(fn (?array $items): array => $items ?? [])
            ->groupBy(fn (array $item): string => (string) ($item['plugin_id'] ?? $item['name'] ?? 'unknown'))
            ->map(fn (Collection $items): array => [
                'name' => (string) ($items->first()['name'] ?? 'Unknown plugin'),
                'revenue' => (int) $items->sum(fn (array $item): int => $this->toMinorUnits((float) ($item['price'] ?? 0))),
            ])
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();

        $top = (int) ($sales->first()['revenue'] ?? 0);

        return $sales
            ->map(fn (array $sale): array => $sale + [
                'label' => Payment::formatAmount($sale['revenue'], $currency),
                'width' => $top > 0 ? min(100, (int) round($sale['revenue'] / $top * 20) * 5) : 0,
            ])
            ->values();
    }

    /**
     * The orders placed on each of the last $days days, oldest first, for the
     * dashboard chart. Days without an order are filled in with a count of 0
     * so the bars line up against the dates below them.
     *
     * @return Collection<int, array{date: string, label: string, count: int, height: int, show_label: bool}>
     */
    public function dailyOrders(int $days = 14): Collection
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = Payment::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as orders')
            ->groupBy('day')
            ->pluck('orders', 'day');

        $peak = max(1, (int) $counts->max());

        return collect(range(0, $days - 1))
            ->map(function (int $offset) use ($start, $counts, $peak): array {
                $date = $start->copy()->addDays($offset);
                $count = (int) ($counts[$date->toDateString()] ?? 0);

                return [
                    'date' => $date->toDateString(),
                    'label' => $date->format('d M'),
                    'count' => $count,
                    'height' => $count > 0 ? max(5, (int) round($count / $peak * 20) * 5) : 0,
                    'show_label' => $offset % 2 === 1,
                ];
            })
            ->values();
    }

    /**
     * Order counts and gross amounts for the whole store, or for the orders
     * that were paid or started inside the window.
     *
     * @return array{orders: int, paid: int, awaiting: int, failed: int, revenue: int}
     */
    private function totals(?DateTimeInterface $since = null): array
    {
        $rows = Payment::query()
            ->select('status')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('COALESCE(SUM(amount), 0) as amount')
            ->when($since !== null, function (Builder $query) use ($since): void {
                $query->whereRaw('COALESCE(paid_at, created_at) >= ?', [$since]);
            })
            ->groupBy('status')
            ->get();

        $settled = $rows->filter(fn (Payment $row): bool => $row->isSuccessful());
        $declined = $rows->filter(fn (Payment $row): bool => $row->hasFailed());
        $orders = (int) $rows->sum('orders');
        $paid = (int) $settled->sum('orders');
        $failed = (int) $declined->sum('orders');

        return [
            'orders' => $orders,
            'paid' => $paid,
            'awaiting' => $orders - $paid - $failed,
            'failed' => $failed,
            'revenue' => (int) $settled->sum('amount'),
        ];
    }

    /**
     * The currency the store charges in, taken from the most recent order.
     */
    public function currency(): string
    {
        $currency = Payment::query()->latest('id')->value('currency');

        return strtoupper((string) ($currency ?: config('payment.paystack.currency', 'NGN')));
    }

    /**
     * Convert a price held in the cart snapshot to the smallest currency unit.
     */
    private function toMinorUnits(float $amount): int
    {
        return (int) round($amount * 100);
    }
}
