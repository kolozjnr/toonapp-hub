<?php

use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A settled payment stored the way checkout stores it: plugin id, name and
 * the price in major units the amount was taken from.
 */
function customerOwnedOrder(User $shopper, Plugin $plugin, int $amount): Payment
{
    return Payment::factory()->successful()->for($shopper)->create([
        'amount' => $amount,
        'currency' => 'NGN',
        'items' => [['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => $amount / 100]],
    ]);
}

test('the customer dashboard shows the plugins this shopper owns and what they spent', function () {
    $shopper = User::factory()->create(['name' => 'Ada Obi']);
    $plugin = Plugin::create(['name' => 'Toon Shadows', 'price' => 105, 'category' => 'Plugin', 'version' => '1.4.0']);

    customerOwnedOrder($shopper, $plugin, 10500);
    Payment::factory()->for($shopper)->create([
        'amount' => 7500,
        'currency' => 'NGN',
        'items' => [['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => 75]],
    ]);

    $response = $this->actingAs($shopper)->get(route('customer.dashboard'));

    $response->assertOk()
        ->assertSee('Toon Shadows')
        ->assertSee('Bought')
        ->assertSee('105.00 NGN')
        ->assertDontSee('75.00 NGN');

    $summary = $response->viewData('summary');

    expect($response->viewData('firstName'))->toBe('Ada')
        ->and($response->viewData('ownedCount'))->toBe(1)
        ->and($summary['orders'])->toBe(2)
        ->and($summary['paid'])->toBe(1)
        ->and($summary['spent'])->toBe(10500)
        ->and($summary['spent_label'])->toBe('105.00 NGN');
});

test('the recommended plugins leave out the ones this shopper already owns', function () {
    $shopper = User::factory()->create();
    $owned = Plugin::create(['name' => 'Owned Plugin', 'price' => 10, 'category' => 'Plugin']);
    $fresh = Plugin::create(['name' => 'Fresh Plugin', 'price' => 20, 'category' => 'Plugin']);

    customerOwnedOrder($shopper, $owned, 1000);

    $response = $this->actingAs($shopper)->get(route('customer.dashboard'));

    expect(collect($response->viewData('recommendations'))->pluck('name')->all())
        ->toBe([$fresh->name])
        ->and($response->viewData('ownedCount'))->toBe(1);
});

test('the updates on the customer pages come from the plugins this shopper owns', function () {
    $shopper = User::factory()->create();
    $owned = Plugin::create(['name' => 'Owned Updater', 'price' => 10, 'category' => 'Plugin', 'version' => '2.1.0']);
    Plugin::create(['name' => 'Stranger Plugin', 'price' => 10, 'category' => 'Plugin', 'version' => '3.0.0', 'is_live' => false]);

    customerOwnedOrder($shopper, $owned, 1000);

    $dashboard = $this->actingAs($shopper)->get(route('customer.dashboard'));

    $dashboard->assertOk()->assertSee('Owned Updater')->assertDontSee('Stranger Plugin');

    expect(collect($dashboard->viewData('updates'))->pluck('name')->all())->toBe(['Owned Updater'])
        ->and($dashboard->viewData('recentlyUpdated'))->toBe(1);

    $this->actingAs($shopper)
        ->get(route('customer.updates'))
        ->assertOk()
        ->assertSee('1 update')
        ->assertSee('Owned Updater')
        ->assertDontSee('Stranger Plugin');
});

test('the customer orders page lists only this shoppers orders with the amount charged', function () {
    $shopper = User::factory()->create(['name' => 'Ada Obi']);
    $other = User::factory()->create();

    $mine = customerOwnedOrder($shopper, Plugin::create(['name' => 'Toon Shadows', 'price' => 105]), 10500);
    Payment::factory()->successful()->for($other)->create([
        'amount' => 4400,
        'currency' => 'NGN',
        'items' => [['plugin_id' => 99, 'name' => 'Secret Plugin', 'price' => 44]],
    ]);

    $response = $this->actingAs($shopper)->get(route('customer.orders'));

    $response->assertOk()
        ->assertSee($mine->reference)
        ->assertSee('Toon Shadows')
        ->assertSee('105.00 NGN')
        ->assertDontSee('Secret Plugin')
        ->assertDontSee('44.00 NGN');

    expect($response->viewData('summary')['orders'])->toBe(1)
        ->and($response->viewData('summary')['spent'])->toBe(10500);
});

test('the my plugins page lists only the plugins this shopper bought', function () {
    $shopper = User::factory()->create();
    $other = User::factory()->create();

    $owned = Plugin::create([
        'name' => 'Toon Shadows',
        'price' => 105,
        'category' => 'Plugin',
        'version' => '1.4.0',
        'download_url' => 'https://example.com/toon-shadows.zip',
    ]);
    Plugin::create(['name' => 'Never Bought Plugin', 'price' => 20, 'category' => 'Plugin']);
    $someoneElses = Plugin::create(['name' => 'Secret Plugin', 'price' => 44, 'category' => 'Plugin']);

    $order = customerOwnedOrder($shopper, $owned, 10500);
    customerOwnedOrder($other, $someoneElses, 4400);

    $response = $this->actingAs($shopper)->get(route('customer.plugins'));

    $response->assertOk()
        ->assertSee('Toon Shadows')
        ->assertSee('Version 1.4.0')
        ->assertSee('Bought')
        ->assertSee($order->reference)
        ->assertSee('https://example.com/toon-shadows.zip', false)
        ->assertSee('1 plugin')
        ->assertDontSee('Never Bought Plugin')
        ->assertDontSee('Secret Plugin');
});

test('a shopper who bought nothing sees an empty library instead of sample plugins', function () {
    $shopper = User::factory()->create();

    $this->actingAs($shopper)
        ->get(route('customer.plugins'))
        ->assertOk()
        ->assertSee('No plugins yet')
        ->assertSee('0 plugins')
        ->assertDontSee('Bounce Presets')
        ->assertDontSee('Squash');
});

test('a shopper with no orders or updates sees empty states instead of sample rows', function () {
    $shopper = User::factory()->create();

    $this->actingAs($shopper)
        ->get(route('customer.orders'))
        ->assertOk()
        ->assertSee('No orders yet')
        ->assertSee('0.00 NGN')
        ->assertDontSee('$29.00');

    $this->actingAs($shopper)
        ->get(route('customer.updates'))
        ->assertOk()
        ->assertSee("You're all caught up", false)
        ->assertDontSee('data-dismiss="u-', false);

    $this->actingAs($shopper)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->assertSee("You don't own any plugins yet", false);
});
