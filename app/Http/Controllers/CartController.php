<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cart,
    ) {}

    /**
     * The cart's current contents, for the drawer to re-render itself.
     */
    public function items(): JsonResponse
    {
        return response()->json($this->cart->payload());
    }

    /**
     * Add a plugin to the cart.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plugin_id' => ['required', 'integer'],
        ]);

        $plugin = Plugin::live()->findOrFail($validated['plugin_id']);

        $this->cart->add($plugin);

        return response()->json($this->cart->payload());
    }

    /**
     * Remove a plugin from the cart.
     */
    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'plugin_id' => ['required', 'integer'],
        ]);

        $this->cart->remove((int) $validated['plugin_id']);

        if ($request->expectsJson()) {
            return response()->json($this->cart->payload());
        }

        return back()->with('status', 'Removed from your cart.');
    }
}
