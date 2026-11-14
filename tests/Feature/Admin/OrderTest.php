<?php

use App\Mail\OrderDownloadMail;
use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

/**
 * A plugin whose download link the buyer is sent, or null when the
 * administrator has not uploaded one yet.
 */
function downloadablePlugin(string $name, ?string $url = 'https://example.com/plugin.zip'): Plugin
{
    return Plugin::create([
        'name' => $name,
        'price' => 105,
        'category' => 'Plugin',
        'download_url' => $url,
    ]);
}

/**
 * A settled order for the given plugins, stored the way checkout stores it.
 *
 * @param  list<Plugin>  $plugins
 */
function paidOrderForPlugins(User $buyer, array $plugins): Payment
{
    return Payment::factory()->successful()->for($buyer)->create([
        'amount' => 10500,
        'currency' => 'NGN',
        'items' => array_map(
            fn (Plugin $plugin): array => ['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => 105],
            $plugins,
        ),
    ]);
}

test('resending an order emails the download links to the shopper who paid for it', function () {
    Mail::fake();

    $buyer = User::factory()->create(['name' => 'Ada Obi', 'email' => 'ada@example.com']);
    $plugin = downloadablePlugin('Toon Shadows', 'https://example.com/toon-shadows.zip');
    $order = paidOrderForPlugins($buyer, [$plugin]);

    $this->actingAs(orderAdmin())
        ->from(route('admin.orders'))
        ->post(route('admin.orders.resend', $order))
        ->assertRedirect(route('admin.orders'))
        ->assertSessionHas('success');

    Mail::assertSent(
        OrderDownloadMail::class,
        fn (OrderDownloadMail $mail): bool => $mail->hasTo('ada@example.com')
            && $mail->recipientName === 'Ada Obi'
            && $mail->reference === $order->reference
            && $mail->downloads === [['name' => 'Toon Shadows', 'url' => 'https://example.com/toon-shadows.zip']]
    );
});

test('every downloadable plugin on the order is attached, and one without a link is skipped', function () {
    Mail::fake();

    $buyer = User::factory()->create();
    $order = paidOrderForPlugins($buyer, [
        downloadablePlugin('Toon Shadows', 'https://example.com/toon-shadows.zip'),
        downloadablePlugin('Line Boil FX', 'https://example.com/line-boil.zip'),
        downloadablePlugin('Bounce Presets', null),
    ]);

    $this->actingAs(orderAdmin())->post(route('admin.orders.resend', $order));

    Mail::assertSent(OrderDownloadMail::class, fn (OrderDownloadMail $mail): bool => $mail->downloads === [
        ['name' => 'Toon Shadows', 'url' => 'https://example.com/toon-shadows.zip'],
        ['name' => 'Line Boil FX', 'url' => 'https://example.com/line-boil.zip'],
    ]);
});

test('the resend email greets the buyer with a Download Plugin button', function () {
    $mail = new OrderDownloadMail('Ada Obi', 'TOON-ORDER', [
        ['name' => 'Toon Shadows', 'url' => 'https://example.com/toon-shadows.zip'],
    ]);

    $mail->assertHasSubject('Your download links for order TOON-ORDER')
        ->assertSeeInHtml('Ada Obi')
        ->assertSeeInHtml('Toon Shadows')
        ->assertSeeInHtml('Download Plugin')
        ->assertSeeInHtml('https://example.com/toon-shadows.zip')
        ->assertSeeInText('Download Plugin');
});

test('the resend email renders one Download Plugin button per plugin', function () {
    $mail = new OrderDownloadMail('Ada Obi', 'TOON-ORDER', [
        ['name' => 'Toon Shadows', 'url' => 'https://example.com/toon-shadows.zip'],
        ['name' => 'Line Boil FX', 'url' => 'https://example.com/line-boil.zip'],
    ]);

    $html = $mail->render();

    expect(substr_count($html, 'class="button button-primary"'))->toBe(2)
        ->and(substr_count($html, '<h2'))->toBe(2)
        ->and($html)->toContain('>Toon Shadows</h2>')
        ->and($html)->toContain('>Line Boil FX</h2>');
});

test('an order whose plugins have no download link is reported instead of emailed', function () {
    Mail::fake();

    $buyer = User::factory()->create();
    $order = paidOrderForPlugins($buyer, [downloadablePlugin('Bounce Presets', null)]);

    $this->actingAs(orderAdmin())
        ->from(route('admin.orders'))
        ->post(route('admin.orders.resend', $order))
        ->assertRedirect(route('admin.orders'))
        ->assertSessionHas('error');

    Mail::assertNothingSent();
});

test('an unpaid order cannot have its links resent', function () {
    Mail::fake();

    $buyer = User::factory()->create();
    $plugin = downloadablePlugin('Toon Shadows', 'https://example.com/toon-shadows.zip');
    $order = Payment::factory()->for($buyer)->create([
        'status' => Payment::STATUS_PENDING,
        'items' => [['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => 105]],
    ]);

    $this->actingAs(orderAdmin())
        ->from(route('admin.orders'))
        ->post(route('admin.orders.resend', $order))
        ->assertRedirect(route('admin.orders'))
        ->assertSessionHas('error');

    Mail::assertNothingSent();
});

test('the resend button only appears on orders that have been paid', function () {
    $buyer = User::factory()->create();
    $paid = paidOrderForPlugins($buyer, [downloadablePlugin('Toon Shadows')]);
    $pending = Payment::factory()->for($buyer)->create([
        'status' => Payment::STATUS_PENDING,
        'items' => [['plugin_id' => 1, 'name' => 'Unpaid Plugin', 'price' => 10]],
    ]);

    $this->actingAs(orderAdmin())
        ->get(route('admin.orders'))
        ->assertOk()
        ->assertSee(route('admin.orders.resend', $paid), false)
        ->assertDontSee(route('admin.orders.resend', $pending), false);
});

test('a customer cannot resend an order download links', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');
    $order = paidOrderForPlugins(User::factory()->create(), [downloadablePlugin('Toon Shadows')]);

    $this->actingAs($customer)
        ->post(route('admin.orders.resend', $order))
        ->assertForbidden();
});

test('a guest cannot resend an order download links', function () {
    $order = paidOrderForPlugins(User::factory()->create(), [downloadablePlugin('Toon Shadows')]);

    $this->post(route('admin.orders.resend', $order))->assertRedirect(route('login'));
});
