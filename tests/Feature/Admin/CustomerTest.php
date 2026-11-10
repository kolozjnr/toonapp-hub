<?php

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function customersAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('the customer list shows the real name, email and country of every shopper', function () {
    User::factory()->create([
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'country' => 'Nigeria',
        'newsletter' => true,
    ]);

    $response = $this->actingAs(customersAdmin())->get(route('admin.customers'));

    $response->assertOk()
        ->assertSee('Ada Obi')
        ->assertSee('ada@example.com')
        ->assertSee('Nigeria')
        ->assertSee('Email confirmed')
        ->assertSee('<span class="badge ok">Yes</span>', false)
        ->assertDontSee('Sara Mensah');

    expect($response->viewData('customers')->getCollection()->pluck('email')->all())
        ->toBe(['ada@example.com']);
});

test('the customer cards count shoppers, buyers and subscribers, leaving out the staff logins', function () {
    $buyer = User::factory()->create(['newsletter' => true]);
    Payment::factory()->successful()->for($buyer)->create();
    User::factory()->create(['newsletter' => true]);
    User::factory()->create()->assignRole('admin');

    $response = $this->actingAs(customersAdmin())->get(route('admin.customers'));

    $response->assertOk();

    expect($response->viewData('summary'))
        ->toBe(['customers' => 2, 'buyers' => 1, 'newsletter' => 2]);
});

test('the orders and spent columns only count settled checkouts', function () {
    $customer = User::factory()->create(['name' => 'Ada Obi']);
    Payment::factory()->successful()->for($customer)->create(['amount' => 10500, 'currency' => 'NGN']);
    Payment::factory()->for($customer)->create(['amount' => 7500, 'currency' => 'NGN']);

    $response = $this->actingAs(customersAdmin())->get(route('admin.customers'));

    $response->assertOk()
        ->assertSee('105.00 NGN')
        ->assertDontSee('75.00 NGN');

    $row = $response->viewData('customers')->getCollection()->first();

    expect((int) $row->orders_count)->toBe(1)
        ->and((int) $row->spent)->toBe(10500);
});

test('the customer list can be searched by name, email or country', function (string $query, string $hidden) {
    User::factory()->create(['name' => 'Ada Obi', 'email' => 'ada@example.com', 'country' => 'Nigeria']);
    User::factory()->create(['name' => 'Jon Park', 'email' => 'jon@example.com', 'country' => 'Ghana']);

    $this->actingAs(customersAdmin())
        ->get(route('admin.customers', ['q' => $query]))
        ->assertOk()
        ->assertSee('Ada Obi')
        ->assertDontSee($hidden);
})->with([
    'name' => ['Ada', 'Jon Park'],
    'email' => ['ada@example.com', 'Jon Park'],
    'country' => ['Nigeria', 'Jon Park'],
]);

test('the copy button carries the addresses of every subscriber', function () {
    User::factory()->create(['email' => 'ada@example.com', 'newsletter' => true]);
    User::factory()->create(['email' => 'jon@example.com', 'newsletter' => true]);
    User::factory()->create(['email' => 'quiet@example.com', 'newsletter' => false]);

    $this->actingAs(customersAdmin())
        ->get(route('admin.customers'))
        ->assertOk()
        ->assertSee('data-copy="ada@example.com, jon@example.com"', false);
});

test('an empty store lists no sample customers', function () {
    $response = $this->actingAs(customersAdmin())->get(route('admin.customers'));

    $response->assertOk()
        ->assertSee('0 customers')
        ->assertSee('No customers yet')
        ->assertDontSee('Sara Mensah')
        ->assertDontSee('Copy subscriber emails');

    expect($response->viewData('customers')->getCollection())->toBeEmpty();
});

test('a shopper cannot open the customer list', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)->get(route('admin.customers'))->assertForbidden();
});
