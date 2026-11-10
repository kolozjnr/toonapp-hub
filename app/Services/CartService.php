<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * CartService
 *
 * DB-backed cart that persists across pages and sessions.
 *
 * - Guests are identified by a long-lived `toonhub_cart_token` cookie.
 * - Logged-in customers are identified by their `user_id`.
 * - The guest cart is merged into the user's cart on login/registration.
 */
class CartService
{
    public const COOKIE_NAME = 'toonhub_cart_token';

    /** @var string|null The token for this request, once generated. */
    private ?string $cachedToken = null;

    /**
     * The token identifying the current guest's cart (null if unknown).
     */
    public function token(): ?string
    {
        if ($token = request()->cookie(self::COOKIE_NAME)) {
            return $token;
        }

        return $this->cachedToken;
    }

    /**
     * The token for this guest's cart, creating and persisting one if needed.
     */
    public function tokenOrCreate(): string
    {
        if ($existing = $this->token()) {
            return $existing;
        }

        if (! $this->cachedToken) {
            $this->cachedToken = Str::random(40);
            Cookie::queue(self::COOKIE_NAME, $this->cachedToken, 60 * 24 * 365, '/', null, false, true);
        }

        return $this->cachedToken;
    }

    /**
     * Every cart item for this shopper, restricted to live plugins.
     */
    public function items(): Collection
    {
        $query = CartItem::query()->with('plugin');

        if ($user = Auth::user()) {
            $query->where('user_id', $user->id);
        } elseif ($token = $this->token()) {
            $query->where('cart_token', $token);
        } else {
            return collect();
        }

        return $query->get()
            ->filter(fn (CartItem $item) => $item->plugin && $item->plugin->is_live)
            ->values();
    }

    /**
     * Add a plugin to the shopper's cart.
     */
    public function add(Plugin $plugin): void
    {
        if ($user = Auth::user()) {
            CartItem::firstOrCreate([
                'user_id' => $user->id,
                'plugin_id' => $plugin->id,
            ]);

            return;
        }

        $token = $this->tokenOrCreate();

        CartItem::firstOrCreate([
            'cart_token' => $token,
            'plugin_id' => $plugin->id,
        ]);
    }

    /**
     * Remove a plugin from the shopper's cart.
     */
    public function remove(int $pluginId): void
    {
        if ($user = Auth::user()) {
            CartItem::where('user_id', $user->id)
                ->where('plugin_id', $pluginId)
                ->delete();

            return;
        }

        if ($token = $this->token()) {
            CartItem::where('cart_token', $token)
                ->where('plugin_id', $pluginId)
                ->delete();
        }
    }

    /**
     * Merge the guest cart (cookie token) into the logged-in user's cart.
     */
    public function mergeGuestIntoUser(User $user): void
    {
        $token = $this->token();

        if (! $token) {
            return;
        }

        $guest = CartItem::where('cart_token', $token)->get();
        $ownedIds = CartItem::where('user_id', $user->id)->pluck('plugin_id')->all();

        foreach ($guest as $item) {
            if (in_array($item->plugin_id, $ownedIds, true)) {
                $item->delete();
            } else {
                $item->update([
                    'user_id' => $user->id,
                    'cart_token' => null,
                ]);
            }
        }

        Cookie::queue(Cookie::forget(self::COOKIE_NAME));
    }

    /**
     * How many plugins are in the shopper's cart.
     */
    public function count(): int
    {
        return $this->items()->count();
    }

    /**
     * The running total of the shopper's cart.
     */
    public function total(): float
    {
        return (float) $this->items()->sum(fn (CartItem $item) => (float) $item->plugin->price);
    }

    /**
     * The JSON payload used by the cart drawer and the dashboard.
     */
    public function payload(): array
    {
        $items = $this->items()->map(fn (CartItem $item) => [
            'id' => $item->id,
            'plugin_id' => $item->plugin_id,
            'name' => $item->plugin->name,
            'slug' => $item->plugin->slug,
            'category' => $item->plugin->category,
            'price' => (float) $item->plugin->price,
            'price_label' => $item->plugin->priceLabel(),
            'image' => $item->plugin->coverUrl() ?: asset('assets/images/logo.png'),
            'url' => route('plugins.show', $item->plugin->slug),
        ])->values();

        return [
            'items' => $items->all(),
            'count' => $items->count(),
            'total' => round((float) $items->sum(fn (array $item) => $item['price']), 2),
        ];
    }
}
