<?php

use App\Models\CartItem;
use App\Models\Plugin;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a guest cart persists across requests', function () {
    $plugin = Plugin::create([
        'name' => 'Cart Plugin',
        'price' => 25,
        'category' => 'Plugin',
        'is_live' => true,
    ]);

    $token = 'guest-cart-token-abc';

    // Adding works for guests and creates the cart row.
    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->withCredentials()
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('items.0.plugin_id', $plugin->id)
        ->assertJsonPath('total', 25);

    expect(CartItem::where('cart_token', $token)->count())->toBe(1);

    // The drawer on the home page is server-rendered with the saved items.
    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Cart Plugin');

    // Hidden plugins can never be bought.
    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->withCredentials()
        ->postJson('/cart/add', ['plugin_id' => Plugin::create([
            'name' => 'Hidden Plugin',
            'price' => 10,
            'category' => 'Plugin',
            'is_live' => false,
        ])->id])
        ->assertNotFound();

    // Removing works across requests too.
    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->withCredentials()
        ->postJson('/cart/remove', ['plugin_id' => $plugin->id])
        ->assertOk()
        ->assertJsonPath('count', 0);
});

test('a guest cart is merged into the customer account on login', function () {
    $plugin = Plugin::create([
        'name' => 'Merge Me Plugin',
        'price' => 40,
        'category' => 'Plugin',
        'is_live' => true,
    ]);

    $user = User::factory()->create([
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]);

    $token = 'merge-token-xyz';

    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->withCredentials()
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk();

    expect(CartItem::where('cart_token', $token)->count())->toBe(1);

    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('dashboard', absolute: false));

    // Guest rows were moved onto the user, nothing left behind.
    expect(CartItem::where('cart_token', $token)->count())->toBe(0)
        ->and(CartItem::where('user_id', $user->id)->where('plugin_id', $plugin->id)->exists())->toBeTrue();

    // The cart is now displayed on the customer dashboard.
    $this->actingAs($user)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->assertSee('Merge Me Plugin')
        ->assertSee('Your cart');
});

test('authenticated shopper cart persists in the database', function () {
    $plugin = Plugin::create([
        'name' => 'Auth Cart Plugin',
        'price' => 15,
        'category' => 'Script',
        'is_live' => true,
    ]);

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk()
        ->assertJsonPath('count', 1);

    expect(CartItem::where('user_id', $user->id)->where('plugin_id', $plugin->id)->exists())->toBeTrue();

    $this->actingAs($user)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->assertSee('Auth Cart Plugin')
        ->assertSee('$15');
});

test('the dashboard renders an empty cart when nothing has been added', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->assertSee('Your cart')
        ->assertSee('Your cart is empty');
});

test('the cart is shown in the guest layout on every public page', function () {
    $plugin = Plugin::create([
        'name' => 'Shown Everywhere Plugin',
        'price' => 5,
        'category' => 'Plugin',
        'is_live' => true,
    ]);

    $token = 'layout-cart-token-abc';

    $this->withCookie(CartService::COOKIE_NAME, $token)
        ->withCredentials()
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk();

    foreach ([route('home'), route('plugins.show', $plugin->slug)] as $url) {
        $this->withCookie(CartService::COOKIE_NAME, $token)
            ->get($url)
            ->assertOk()
            ->assertSee('data-cart-item="'.$plugin->id.'"', false);
    }
});
