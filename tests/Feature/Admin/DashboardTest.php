<?php

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
});

function dashboardAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('the customers card counts shoppers and leaves out the staff logins', function () {
    User::factory()->create();
    User::factory()->create();
    User::factory()->create()->assignRole('admin');
    User::factory()->create()->assignRole('superadmin');

    $response = $this->actingAs(dashboardAdmin())->get(route('admin.dashboard'));

    $response->assertOk()->assertSee('Customers')->assertSee('Registered');

    expect($response->viewData('customerCount'))->toBe(2);

    // The admin nav badge shows the same shopper count as the dashboard card.
    $response->assertSee('<span class="ct">2</span>', false);
});

test('the dashboard chart counts the orders placed on each of the last 14 days', function () {
    $customer = User::factory()->create();
    Payment::factory()->successful()->for($customer)->create(['created_at' => now()->subDays(2)]);
    Payment::factory()->successful()->for($customer)->create(['created_at' => now()->subDays(2)]);
    Payment::factory()->for($customer)->create(['created_at' => now()->subDays(9)]);

    $response = $this->actingAs(dashboardAdmin())->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('Orders, last 14 days')
        ->assertSee('this week')
        ->assertDontSee('Visits, last 14 days');

    $days = collect($response->viewData('dailyOrders'));
    $busiest = $days->first(fn (array $day): bool => $day['date'] === now()->subDays(2)->toDateString());
    $quiet = $days->first(fn (array $day): bool => $day['date'] === now()->subDays(9)->toDateString());

    expect($days)->toHaveCount(14)
        ->and($days->first()['date'])->toBe(now()->subDays(13)->toDateString())
        ->and($days->first()['count'])->toBe(0)
        ->and($days->last()['date'])->toBe(now()->toDateString())
        ->and($days->last()['count'])->toBe(0)
        ->and($days->sum('count'))->toBe(3)
        ->and($days->sum('show_label'))->toBe(7)
        ->and($busiest['count'])->toBe(2)
        ->and($busiest['height'])->toBe(100)
        ->and($quiet['count'])->toBe(1)
        ->and($quiet['height'])->toBe(50);
});

test('the awaiting payment card counts the checkouts that never settled', function () {
    $customer = User::factory()->create();
    Payment::factory()->successful()->for($customer)->create(['amount' => 10500, 'currency' => 'NGN']);
    Payment::factory()->for($customer)->create(['amount' => 7500, 'currency' => 'NGN']);
    Payment::factory()->for($customer)->create(['amount' => 2500, 'currency' => 'NGN', 'status' => 'failed']);

    $response = $this->actingAs(dashboardAdmin())->get(route('admin.dashboard'));

    $response->assertOk()->assertSee('Awaiting payment')->assertSee('Unpaid checkouts');

    expect($response->viewData('summary')['awaiting'])->toBe(1)
        ->and($response->viewData('summary')['failed'])->toBe(1)
        ->and($response->viewData('summary')['orders'])->toBe(3);
});

test('the storage card reports the public disk against the configured quota', function () {
    config(['filesystems.quota' => 10 * 1024 ** 3]);

    $response = $this->actingAs(dashboardAdmin())->get(route('admin.dashboard'));

    $response->assertOk()->assertSee('Store storage')->assertSee('of 10 GB used');

    $storage = $response->viewData('storage');

    expect($storage['files'])->toBeInt()
        ->and($storage['used'])->toBeInt()
        ->and($storage['quota_label'])->toBe('10 GB')
        ->and($storage['percent'])->toBeBetween(0, 100)
        ->and($storage['width'])->toBeBetween(0, 100);
});

test('the dashboard drops the panels that have no data behind them', function () {
    $this->actingAs(dashboardAdmin())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertDontSee('Latest reviews')
        ->assertDontSee('Avg rating')
        ->assertDontSee('unread messages')
        ->assertDontSee('Open messages');
});
