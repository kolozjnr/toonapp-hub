<?php

use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function orderAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

/**
 * A settled order for a single plugin, stored the way checkout stores it.
 */
function settledOrder(User $customer, string $pluginName, int $amount, float $price): Payment
{
    return Payment::factory()->successful()->for($customer)->create([
        'amount' => $amount,
        'currency' => 'NGN',
        'items' => [['plugin_id' => 1, 'name' => $pluginName, 'price' => $price]],
    ]);
}

test('the order list shows the real reference, plugins and amount of every order', function () {
    $customer = User::factory()->create(['name' => 'Ada Obi', 'email' => 'ada@example.com']);
    $order = settledOrder($customer, 'Toon Shadows', 10500, 105);

    $this->actingAs(orderAdmin())
        ->get(route('admin.orders'))
        ->assertOk()
        ->assertSee($order->reference)
        ->assertSee('Ada Obi')
        ->assertSee('ada@example.com')
        ->assertSee('Toon Shadows')
        ->assertSee('105.00 NGN')
        ->assertSee('Paid')
        ->assertDontSee('$143.00');
});

test('the order cards add up settled orders only', function () {
    $customer = User::factory()->create();
    settledOrder($customer, 'Toon Shadows', 10500, 105);
    Payment::factory()->for($customer)->create(['amount' => 7500, 'currency' => 'NGN']);

    $this->actingAs(orderAdmin())
        ->get(route('admin.orders'))
        ->assertOk()
        ->assertSee('2 orders · 1 paid')
        ->assertSee('105.00 NGN')
        ->assertSee('75.00 NGN')
        ->assertSee('Across 1 paid order');
});

test('the dashboard cards show the real revenue, orders and sales per plugin', function () {
    $customer = User::factory()->create(['name' => 'Ada Obi']);
    settledOrder($customer, 'Toon Shadows', 10500, 105);
    Plugin::create(['name' => 'Bounce Presets', 'price' => 0, 'category' => 'Plugin']);

    $this->actingAs(orderAdmin())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('105.00 NGN')
        ->assertSee('Toon Shadows')
        ->assertSee('Ada Obi')
        ->assertSee('1 order so far and 1 plugin on your shelf')
        ->assertDontSee('$143.00')
        ->assertDontSee('$58.00');
});

test('an empty store reports zero instead of sample totals', function () {
    $this->actingAs(orderAdmin())
        ->get(route('admin.orders'))
        ->assertOk()
        ->assertSee('0 orders · 0 paid')
        ->assertSee('0.00 NGN')
        ->assertSee('No orders yet');
});

test('the order list can be searched by customer, reference or plugin', function () {
    $order = settledOrder(User::factory()->create(['name' => 'Ada Obi']), 'Toon Shadows', 10500, 105);
    settledOrder(User::factory()->create(['name' => 'Jon Park']), 'Line Boil FX', 5000, 50);

    $this->actingAs(orderAdmin())
        ->get(route('admin.orders', ['q' => 'Ada']))
        ->assertOk()
        ->assertSee($order->reference)
        ->assertSee('Toon Shadows')
        ->assertDontSee('Line Boil FX');
});

test('a customer cannot open the admin order pages', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)->get(route('admin.orders'))->assertForbidden();
    $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
});
