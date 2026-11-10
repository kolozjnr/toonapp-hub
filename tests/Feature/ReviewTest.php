<?php

use App\Models\Payment;
use App\Models\Plugin;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A plugin bought by this shopper, stored the way checkout stores it.
 */
function reviewOwnedPlugin(User $shopper, string $name = 'Toon Shadows'): Plugin
{
    $plugin = Plugin::create(['name' => $name, 'price' => 105, 'category' => 'Plugin']);

    Payment::factory()->successful()->for($shopper)->create([
        'amount' => 10500,
        'currency' => 'NGN',
        'items' => [['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => 105]],
    ]);

    return $plugin;
}

test('the my plugins page offers a review button for every plugin this shopper owns', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);
    Plugin::create(['name' => 'Never Bought Plugin', 'price' => 20, 'category' => 'Plugin']);

    $this->actingAs($shopper)
        ->get(route('customer.plugins'))
        ->assertOk()
        ->assertSee('Write review')
        ->assertSee('data-plugin="'.$plugin->id.'"', false)
        ->assertDontSee('Never Bought Plugin');
});

test('the plugins page offers to edit the review this shopper already left', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);
    Review::factory()->for($shopper)->for($plugin)->create([
        'rating' => 4,
        'comment' => 'Solid bounce controls.',
    ]);

    $this->actingAs($shopper)
        ->get(route('customer.plugins'))
        ->assertOk()
        ->assertSee('Edit review')
        ->assertSee('Solid bounce controls.');
});

test('the plugins page only pre-fills the review this shopper wrote', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);
    Review::factory()->for(User::factory()->create())->for($plugin)->create([
        'rating' => 1,
        'comment' => 'Not for me.',
    ]);

    $this->actingAs($shopper)
        ->get(route('customer.plugins'))
        ->assertOk()
        ->assertSee('Write review')
        ->assertDontSee('Not for me.');
});

test('a shopper can leave a rating and a comment for a plugin they own', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    $this->actingAs($shopper)
        ->from(route('customer.plugins'))
        ->post(route('customer.plugins.review', $plugin), [
            'rating' => 4,
            'comment' => 'The bounce slider saved me an hour.',
        ])
        ->assertRedirect(route('customer.plugins'))
        ->assertSessionHas('success');

    $review = Review::query()->sole();

    expect($review->rating)->toBe(4)
        ->and($review->comment)->toBe('The bounce slider saved me an hour.')
        ->and($review->user_id)->toBe($shopper->id)
        ->and($review->plugin_id)->toBe($plugin->id);
});

test('a rating can be left without a comment', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    $this->actingAs($shopper)
        ->post(route('customer.plugins.review', $plugin), ['rating' => 5])
        ->assertRedirect();

    $review = Review::query()->sole();

    expect($review->rating)->toBe(5)
        ->and($review->comment)->toBeNull();
});

test('a second review of the same plugin updates the stars instead of adding a row', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    $this->actingAs($shopper)
        ->post(route('customer.plugins.review', $plugin), ['rating' => 5, 'comment' => 'Great.']);

    $this->actingAs($shopper)
        ->post(route('customer.plugins.review', $plugin), ['rating' => 2, 'comment' => 'Broke on my rig.']);

    $review = Review::query()->sole();

    expect(Review::query()->count())->toBe(1)
        ->and($review->rating)->toBe(2)
        ->and($review->comment)->toBe('Broke on my rig.');
});

test('a review without a rating is refused with a message', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    $this->actingAs($shopper)
        ->from(route('customer.plugins'))
        ->post(route('customer.plugins.review', $plugin), [
            'rating' => '',
            'comment' => 'Nice plugin.',
        ])
        ->assertSessionHasErrors([
            'rating' => 'Pick a star rating before you submit.',
        ]);

    expect(Review::query()->count())->toBe(0);
});

test('a rating outside one to five stars is refused', function (int $rating) {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    $this->actingAs($shopper)
        ->from(route('customer.plugins'))
        ->post(route('customer.plugins.review', $plugin), ['rating' => $rating])
        ->assertSessionHasErrors('rating');

    expect(Review::query()->count())->toBe(0);
})->with([0, 6]);

test('a failed submit brings the review form back open with the message', function () {
    $shopper = User::factory()->create();
    $plugin = reviewOwnedPlugin($shopper);

    // Mirrors the real form: plugin id and name travel with the submit.
    $this->actingAs($shopper)
        ->from(route('customer.plugins'))
        ->post(route('customer.plugins.review', $plugin), [
            'rating' => '',
            'comment' => 'Nice plugin.',
            'plugin_id' => $plugin->id,
            'plugin_name' => $plugin->name,
        ])
        ->assertRedirect(route('customer.plugins'));

    $this->actingAs($shopper)
        ->get(route('customer.plugins'))
        ->assertOk()
        ->assertSee('Pick a star rating before you submit.')
        ->assertSee('id="reviewPanel"', false)
        ->assertDontSee('id="reviewPanel" hidden', false)
        ->assertSee('name="plugin_id" type="hidden" value="'.$plugin->id.'"', false);
});

test('a shopper cannot rate a plugin they never bought', function () {
    $shopper = User::factory()->create();
    $plugin = Plugin::create(['name' => 'Stranger Plugin', 'price' => 10, 'category' => 'Plugin']);

    $this->actingAs($shopper)
        ->post(route('customer.plugins.review', $plugin), ['rating' => 5])
        ->assertForbidden();

    expect(Review::query()->count())->toBe(0);
});

test('a guest cannot submit a review', function () {
    $plugin = Plugin::create(['name' => 'Toon Shadows', 'price' => 105, 'category' => 'Plugin']);

    $this->post(route('customer.plugins.review', $plugin), ['rating' => 5])
        ->assertRedirect(route('login'));

    expect(Review::query()->count())->toBe(0);
});
