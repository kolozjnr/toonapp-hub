<?php

use App\Models\Plugin;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the storefront shows live plugins from the database', function () {
    Plugin::create(['name' => 'Live Plugin', 'price' => 10, 'category' => 'Plugin', 'is_live' => true]);
    Plugin::create(['name' => 'Hidden Plugin', 'price' => 20, 'category' => 'Plugin', 'is_live' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Live Plugin')
        ->assertDontSee('Hidden Plugin')
        ->assertSee('data-all-total="1"', false);
});

test('the storefront renders at most twelve plugins initially', function () {
    foreach (range(1, 15) as $number) {
        Plugin::create([
            'name' => 'Plugin '.$number,
            'price' => $number,
            'category' => 'Plugin',
            'is_live' => true,
        ]);
    }

    $response = $this->get(route('home'))->assertOk();

    expect(substr_count($response->getContent(), 'class="card"'))->toBe(12);
    $response->assertSee('data-has-more="1"', false);
});

test('the plugin feed paginates, searches and filters live plugins', function () {
    foreach (range(1, 15) as $number) {
        Plugin::create([
            'name' => 'Plugin '.$number,
            'tagline' => $number % 2 ? 'Alpha plugin' : 'Beta plugin',
            'price' => $number * 10,
            'category' => $number % 2 ? 'Plugin' : 'Script',
            'is_live' => true,
        ]);
    }

    Plugin::create(['name' => 'Hidden Plugin', 'price' => 1, 'category' => 'Plugin', 'is_live' => false]);

    $this->getJson(route('plugins.feed'))
        ->assertOk()
        ->assertJsonCount(12, 'data')
        ->assertJsonPath('meta.total', 15)
        ->assertJsonPath('meta.has_more', true);

    $this->getJson(route('plugins.feed', ['page' => 2]))
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('meta.has_more', false);

    $this->getJson(route('plugins.feed', ['category' => 'Script', 'q' => 'Beta']))
        ->assertOk()
        ->assertJsonCount(7, 'data')
        ->assertJsonPath('meta.total', 7);

    $this->getJson(route('plugins.feed', ['q' => 'Hidden']))
        ->assertOk()
        ->assertJsonCount(0, 'data')
        ->assertJsonPath('meta.total', 0);

    $this->getJson(route('plugins.feed', ['sort' => 'low']))
        ->assertOk()
        ->assertJsonPath('data.0.price', 10);
});

test('plugin cards link to the blade preview page', function () {
    $plugin = Plugin::create([
        'name' => 'Linked Plugin',
        'price' => 12,
        'category' => 'Plugin',
        'is_live' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('plugins.show', $plugin->slug), false);
});

test('a live plugin can be previewed by guests', function () {
    $plugin = Plugin::create([
        'name' => 'Preview Plugin',
        'tagline' => 'A lovely plugin for testing.',
        'description' => 'This is the full preview description.',
        'features' => "Feature one\nFeature two",
        'price' => 29,
        'old_price' => 39,
        'category' => 'Plugin',
        'hosts' => ['AE'],
        'version' => '1.2.0',
        'requirements' => 'After Effects 2023 and newer',
        'is_live' => true,
    ]);

    $this->get(route('plugins.show', $plugin->slug))
        ->assertOk()
        ->assertSee('Preview Plugin')
        ->assertSee('A lovely plugin for testing.')
        ->assertSee('Feature one')
        ->assertSee('Feature two')
        ->assertSee('After Effects 2023 and newer')
        ->assertSee('$29');
});

test('hidden plugins cannot be previewed', function () {
    $plugin = Plugin::create([
        'name' => 'Secret Plugin',
        'price' => 10,
        'category' => 'Plugin',
        'is_live' => false,
    ]);

    $this->get(route('plugins.show', $plugin->slug))->assertNotFound();
});

test('the plugin page lists every review shoppers left on it, newest first', function () {
    $plugin = Plugin::create(['name' => 'Reviewed Plugin', 'price' => 29, 'category' => 'Plugin']);

    Review::factory()->for(User::factory()->create(['name' => 'Ada Okafor']))->for($plugin)->create([
        'rating' => 5,
        'comment' => 'Saved me hours on a client job.',
        'created_at' => '2026-10-20 09:00:00',
    ]);
    Review::factory()->for(User::factory()->create(['name' => 'Liam Chen']))->for($plugin)->create([
        'rating' => 3,
        'comment' => null,
        'created_at' => '2026-10-28 09:00:00',
    ]);

    $response = $this->get(route('plugins.show', $plugin->slug));

    $response->assertOk()
        ->assertSee('Ada Okafor')
        ->assertSee('Saved me hours on a client job.')
        ->assertSee('20 Oct 2026')
        ->assertSee('Liam Chen')
        ->assertSee('28 Oct 2026');

    // A review without a comment still shows its stars and date.
    expect($response->viewData('reviews')->pluck('comment')->all())
        ->toBe([null, 'Saved me hours on a client job.']);
});

test('the plugin page summarises the average rating and the star breakdown', function () {
    $plugin = Plugin::create(['name' => 'Reviewed Plugin', 'price' => 29, 'category' => 'Plugin']);

    Review::factory()->create(['rating' => 5, 'plugin_id' => $plugin->id]);
    Review::factory()->create(['rating' => 4, 'plugin_id' => $plugin->id]);

    $response = $this->get(route('plugins.show', $plugin->slug));

    $response->assertOk()
        ->assertSee('4.5')
        ->assertSee('2 reviews')
        ->assertSee('class="w-50"', false);

    expect($response->viewData('stats'))->toBe([
        'average' => '4.5',
        'total' => 2,
        'bands' => [
            ['stars' => 5, 'count' => 1, 'width' => 50],
            ['stars' => 4, 'count' => 1, 'width' => 50],
            ['stars' => 3, 'count' => 0, 'width' => 0],
            ['stars' => 2, 'count' => 0, 'width' => 0],
            ['stars' => 1, 'count' => 0, 'width' => 0],
        ],
    ]);
});

test('the plugin page only counts reviews left on that plugin', function () {
    $plugin = Plugin::create(['name' => 'Reviewed Plugin', 'price' => 29, 'category' => 'Plugin']);
    $other = Plugin::create(['name' => 'Other Plugin', 'price' => 12, 'category' => 'Plugin']);

    Review::factory()->create([
        'rating' => 1,
        'comment' => 'Not for me.',
        'plugin_id' => $other->id,
    ]);

    $response = $this->get(route('plugins.show', $plugin->slug));

    $response->assertOk()
        ->assertSee('No ratings yet.')
        ->assertDontSee('Not for me.');

    expect($response->viewData('stats')['total'])->toBe(0);
});

test('a plugin with no reviews shows the empty ratings block', function () {
    $plugin = Plugin::create(['name' => 'Unreviewed Plugin', 'price' => 15, 'category' => 'Plugin']);

    $this->get(route('plugins.show', $plugin->slug))
        ->assertOk()
        ->assertSee('No ratings yet.')
        ->assertSee('Reviews will appear here once customers rate this plugin.')
        ->assertDontSee('class="rv"', false);
});
