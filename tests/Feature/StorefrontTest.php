<?php

use App\Models\Plugin;
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
