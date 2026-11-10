<?php

use App\Models\Plugin;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function reviewsAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

/**
 * A plugin with one review attached to it.
 */
function reviewedPlugin(string $name = 'Squash & Stretch Pro'): Plugin
{
    return Plugin::create(['name' => $name, 'price' => 30, 'category' => 'Plugin']);
}

test('the reviews page lists every review with its plugin, customer and stars', function () {
    $shopper = User::factory()->create(['name' => 'Ada Okafor', 'email' => 'ada@example.com']);
    $plugin = reviewedPlugin();
    Review::factory()->for($shopper)->for($plugin)->create([
        'rating' => 5,
        'comment' => 'So easy to use. My characters finally feel bouncy.',
        'created_at' => '2026-10-20 09:00:00',
    ]);

    $response = $this->actingAs(reviewsAdmin())->get(route('admin.reviews'));

    $response->assertOk()
        ->assertSee('Squash & Stretch Pro')
        ->assertSee('Ada Okafor')
        ->assertSee('ada@example.com')
        ->assertSee('So easy to use. My characters finally feel bouncy.')
        ->assertSee('20 Oct 2026')
        ->assertSee('aria-label="5.0 out of 5"', false);

    expect($response->viewData('reviews')->pluck('id'))->toHaveCount(1);
});

test('the reviews page reports the average rating and how many carry a comment', function () {
    $plugin = reviewedPlugin();
    Review::factory()->create(['rating' => 5, 'comment' => 'Great.', 'plugin_id' => $plugin->id]);
    Review::factory()->ratingOnly()->create(['rating' => 4, 'plugin_id' => $plugin->id]);

    $response = $this->actingAs(reviewsAdmin())->get(route('admin.reviews'));

    $response->assertOk()
        ->assertSee('4.5 / 5')
        ->assertSee('2 reviews')
        ->assertSee('1 with comments');

    expect($response->viewData('stats'))->toBe([
        'average' => '4.5',
        'total' => 2,
        'commented' => 1,
    ]);
});

test('the reviews page shows an empty state instead of sample reviews', function () {
    $this->actingAs(reviewsAdmin())
        ->get(route('admin.reviews'))
        ->assertOk()
        ->assertSee('No reviews yet')
        ->assertSee('0 reviews')
        ->assertDontSee('Liam Chen')
        ->assertDontSee('Smear Frames');
});

test('a shopper cannot open the reviews page', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)->get(route('admin.reviews'))->assertForbidden();
});

test('an admin can remove a review', function () {
    $review = Review::factory()->create(['plugin_id' => reviewedPlugin()->id]);

    $this->actingAs(reviewsAdmin())
        ->delete(route('admin.reviews.destroy', $review))
        ->assertRedirect();

    expect(Review::query()->count())->toBe(0);
});

test('a shopper cannot remove a review', function () {
    $review = Review::factory()->create(['plugin_id' => reviewedPlugin()->id]);
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)
        ->delete(route('admin.reviews.destroy', $review))
        ->assertForbidden();

    expect(Review::query()->count())->toBe(1);
});
