<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * PurchaseHistory
 *
 * Everything the customer area shows about one shopper: what they ordered,
 * what they own and what is still on the shelf for them.
 *
 * Only payments the gateway confirmed count as a purchase, so a checkout the
 * shopper abandoned never shows up as an order, a download or a spent amount.
 */
class PurchaseHistory
{
    /**
     * The plugins this shopper owns, keyed by the purchase that added them.
     *
     * @var array<int, Collection<int, array<string, mixed>>>
     */
    private array $owned = [];

    /**
     * Order counters and spend for a single shopper.
     *
     * @return array{
     *     orders: int,
     *     paid: int,
     *     spent: int,
     *     spent_label: string,
     *     last_order_at: Carbon|null,
     *     currency: string
     * }
     */
    public function summary(User $user): array
    {
        $rows = Payment::query()
            ->whereBelongsTo($user)
            ->select('status')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('COALESCE(SUM(amount), 0) as amount')
            ->selectRaw('MAX(COALESCE(paid_at, created_at)) as last_at')
            ->selectRaw('MAX(currency) as currency')
            ->groupBy('status')
            ->get();

        $settled = $rows->filter(fn (Payment $row): bool => $row->isSuccessful());
        $last = $rows->map(fn (Payment $row) => $row->last_at)->filter()->sort()->last();
        $currency = strtoupper((string) ($rows->first()?->currency ?: config('payment.paystack.currency', 'NGN')));
        $spent = (int) $settled->sum('amount');

        return [
            'orders' => (int) $rows->sum('orders'),
            'paid' => (int) $settled->sum('orders'),
            'spent' => $spent,
            'spent_label' => Payment::formatAmount($spent, $currency),
            'last_order_at' => $last !== null ? Carbon::parse($last) : null,
            'currency' => $currency,
        ];
    }

    /**
     * The plugins this shopper owns, most recently bought first.
     *
     * The cart snapshot on the order only holds the plugin id, name and price,
     * so the rest of each row (cover, version, download link) is read from the
     * plugin itself. Plugins that were taken off the shelf keep their place in
     * the list with just the name the shopper paid for.
     *
     * @return Collection<int, array{
     *     plugin_id: int,
     *     name: string,
     *     version: string|null,
     *     tagline: string|null,
     *     youtube_url: string|null,
     *     cover: string,
     *     url: string|null,
     *     download_url: string|null,
     *     order: string|null,
     *     hosts: list<array{code: string, name: string}>,
     *     bought_at: Carbon,
     *     updated_at: Carbon|null
     * }>
     */
    public function plugins(User $user): Collection
    {
        return $this->owned[$user->id] ??= $this->loadPlugins($user);
    }

    /**
     * The shopper's plugins that carry an update, newest first.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function updates(User $user, ?int $limit = null): Collection
    {
        $updates = $this->plugins($user)
            ->filter(fn (array $plugin): bool => $plugin['updated_at'] !== null)
            ->sortByDesc('updated_at')
            ->values();

        return $limit === null ? $updates : $updates->take($limit)->values();
    }

    /**
     * How many of the shopper's plugins changed in the last few days.
     */
    public function recentlyUpdated(User $user, int $days = 30): int
    {
        $cutoff = now()->subDays($days);

        return $this->plugins($user)
            ->filter(fn (array $plugin): bool => $plugin['updated_at'] !== null && $plugin['updated_at']->gte($cutoff))
            ->count();
    }

    /**
     * Live plugins the shopper has not bought yet, newest first.
     *
     * @return Collection<int, Plugin>
     */
    public function recommendations(User $user, int $limit = 3): Collection
    {
        $ownedIds = $this->plugins($user)->pluck('plugin_id')->filter()->all();

        return Plugin::query()
            ->live()
            ->when($ownedIds !== [], fn ($query) => $query->whereNotIn('id', $ownedIds))
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Read a shopper's confirmed purchases newest first.
     *
     * @return Collection<int, Payment>
     */
    private function purchases(User $user): Collection
    {
        return Payment::query()
            ->whereBelongsTo($user)
            ->successful()
            ->select(['id', 'reference', 'items', 'paid_at', 'created_at'])
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Flatten the cart snapshots of a shopper's purchases into one row per
     * plugin, keeping the most recent purchase of each.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function loadPlugins(User $user): Collection
    {
        $purchases = $this->purchases($user);
        $plugins = Plugin::query()
            ->whereIn('id', $purchases->flatMap(fn (Payment $payment): array => $this->pluginIds($payment))->unique()->all())
            ->get()
            ->keyBy('id');

        $owned = [];

        foreach ($purchases as $payment) {
            foreach ($payment->items ?? [] as $item) {
                $pluginId = (int) ($item['plugin_id'] ?? 0);
                $key = $pluginId > 0 ? $pluginId : (string) ($item['name'] ?? 'unknown');

                if (isset($owned[$key])) {
                    continue;
                }

                $plugin = $plugins->get($pluginId);

                $owned[$key] = [
                    'plugin_id' => $pluginId,
                    'name' => $plugin?->name ?? (string) ($item['name'] ?? 'Unknown plugin'),
                    'version' => $plugin?->version,
                    'tagline' => $plugin?->tagline,
                    'youtube_url' => $plugin?->youtube_url,
                    'cover' => $plugin?->coverUrl() ?: asset('assets/images/logo.png'),
                    'url' => $plugin ? route('plugins.show', $plugin->slug) : null,
                    'download_url' => $plugin?->download_url,
                    'order' => $payment->reference,
                    'hosts' => $plugin?->hostTags() ?? [],
                    'bought_at' => Carbon::parse($payment->paid_at ?? $payment->created_at),
                    'updated_at' => $plugin?->updated_at,
                ];
            }
        }

        return collect($owned)->values();
    }

    /**
     * The plugin ids a payment's cart snapshot refers to.
     *
     * @return list<int>
     */
    private function pluginIds(Payment $payment): array
    {
        return collect($payment->items)
            ->map(fn (array $item): int => (int) ($item['plugin_id'] ?? 0))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
