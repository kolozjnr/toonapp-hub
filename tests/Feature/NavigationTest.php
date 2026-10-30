<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

test('the shared sidebar shows admin links to administrators', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $html = $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->getContent();

    $nav = Str::between($html, '<nav aria-label="Admin"', '</nav>');

    expect($nav)
        ->toContain('Upload plugin')
        ->toContain('Customers')
        ->toContain('Newsletter')
        ->toContain(route('admin.plugins'))
        ->toContain(route('admin.orders'))
        ->toContain(route('admin.settings'))
        ->not->toContain('My plugins')
        ->not->toContain('.html');
});

test('the shared sidebar shows customer links to customers', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $html = $this->actingAs($customer)
        ->get(route('customer.dashboard'))
        ->assertOk()
        ->getContent();

    $nav = Str::between($html, '<nav aria-label="Account"', '</nav>');

    expect($nav)
        ->toContain('My plugins')
        ->toContain('Updates')
        ->toContain(route('customer.plugins'))
        ->toContain(route('customer.orders'))
        ->toContain(route('customer.settings'))
        ->not->toContain('Upload plugin')
        ->not->toContain('Newsletter')
        ->not->toContain('.html');
});

test('customer pages use the shared role-aware sidebar', function (string $route) {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $html = $this->actingAs($customer)
        ->get(route($route))
        ->assertOk()
        ->getContent();

    $nav = Str::between($html, '<nav aria-label="Account"', '</nav>');

    expect($nav)
        ->toContain('My plugins')
        ->not->toContain('Upload plugin');
})->with([
    'dashboard' => ['customer.dashboard'],
    'plugins' => ['customer.plugins'],
    'orders' => ['customer.orders'],
    'updates' => ['customer.updates'],
    'settings' => ['customer.settings'],
]);
