<?php

use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

function pluginAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('an admin can open the create and edit forms', function () {
    $admin = pluginAdmin();
    $plugin = Plugin::create(['name' => 'Toon Shadows', 'price' => 10, 'category' => 'Plugin']);

    $this->actingAs($admin)
        ->get(route('admin.plugins.create'))
        ->assertOk()
        ->assertSee('Upload plugin');

    $this->actingAs($admin)
        ->get(route('admin.plugins.edit', $plugin))
        ->assertOk()
        ->assertSee('Toon Shadows');
});

test('global flash displays errors and success messages', function () {
    $admin = pluginAdmin();

    $this->actingAs($admin)
        ->withSession(['error' => 'The upload could not be completed.'])
        ->get(route('admin.plugins.create'))
        ->assertOk()
        ->assertSee('The upload could not be completed.')
        ->assertSee('flash-error', false);

    $this->actingAs($admin)
        ->withSession(['success' => 'Saved successfully.'])
        ->get(route('admin.plugins.create'))
        ->assertOk()
        ->assertSee('Saved successfully.');
});

test('validation failures are shown in the global flash message', function () {
    $admin = pluginAdmin();

    $response = $this->actingAs($admin)
        ->followingRedirects()
        ->from(route('admin.plugins.create'))
        ->post(route('admin.plugins.store'), [
            'price' => '10',
            'category' => 'Plugin',
        ]);

    $response
        ->assertOk()
        ->assertSee('The name field is required.')
        ->assertSee('flash-error', false);
});

test('an admin can create a plugin', function () {
    Storage::fake('public');

    $response = $this
        ->actingAs(pluginAdmin())
        ->post(route('admin.plugins.store'), [
            'name' => 'Smear Frames',
            'tagline' => 'Smear any layer between frames.',
            'description' => 'A handy plugin.',
            'features' => "Fast\nLightweight",
            'youtube_url' => 'https://www.youtube.com/watch?v=abc123',
            'cover' => UploadedFile::fake()->image('cover.png'),
            'images' => [0 => UploadedFile::fake()->image('shot-1.png')],
            'image_titles' => [0 => 'One-click setup'],
            'image_texts' => [0 => 'Apply it in a single click.'],
            'price' => '19.00',
            'old_price' => '29.00',
            'category' => 'Plugin',
            'badge' => 'New',
            'hosts' => ['AE', 'PR'],
            'version' => '1.0.0',
            'requirements' => 'AE 2024+',
            'download_url' => 'https://example.com/download.zip',
            'is_live' => '1',
        ]);

    $plugin = Plugin::firstOrFail();

    $response->assertRedirect(route('admin.plugins.edit', $plugin));
    $response->assertSessionHas('success', 'Plugin created successfully.');

    expect($plugin->name)->toBe('Smear Frames')
        ->and($plugin->slug)->toBe('smear-frames')
        ->and($plugin->price)->toBe('19.00')
        ->and($plugin->hosts)->toBe(['AE', 'PR'])
        ->and($plugin->is_live)->toBeTrue()
        ->and($plugin->created_by)->not->toBeNull()
        ->and($plugin->gallery)->toHaveCount(1)
        ->and($plugin->gallery[0]['title'])->toBe('One-click setup');

    Storage::disk('public')->assertExists($plugin->cover_image);
    Storage::disk('public')->assertExists($plugin->gallery[0]['path']);
});

test('an admin can update a plugin', function () {
    Storage::fake('public');

    $plugin = Plugin::create([
        'name' => 'Line Boil FX',
        'price' => 15,
        'category' => 'Plugin',
        'hosts' => ['AE'],
        'is_live' => true,
    ]);

    $response = $this
        ->actingAs(pluginAdmin())
        ->put(route('admin.plugins.update', $plugin), [
            'name' => 'Line Boil FX Pro',
            'tagline' => 'Add line boil to your animation.',
            'price' => '25.00',
            'category' => 'Script',
            'hosts' => ['AE', 'BL'],
            'version' => '2.0.0',
        ]);

    $response->assertRedirect(route('admin.plugins.edit', $plugin));
    $response->assertSessionHas('success', 'Plugin updated successfully.');

    $plugin->refresh();

    expect($plugin->name)->toBe('Line Boil FX Pro')
        ->and($plugin->slug)->toBe('line-boil-fx')
        ->and($plugin->price)->toBe('25.00')
        ->and($plugin->category)->toBe('Script')
        ->and($plugin->hosts)->toBe(['AE', 'BL'])
        ->and($plugin->version)->toBe('2.0.0')
        ->and($plugin->is_live)->toBeFalse();
});

test('a plugin name is required', function () {
    $this
        ->actingAs(pluginAdmin())
        ->post(route('admin.plugins.store'), [
            'price' => '10',
            'category' => 'Plugin',
        ])
        ->assertSessionHasErrors('name');

    expect(Plugin::count())->toBe(0);
});

test('a non admin cannot create a plugin', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->post(route('admin.plugins.store'), [
            'name' => 'Sneaky Plugin',
            'price' => '10',
            'category' => 'Plugin',
        ])
        ->assertForbidden();

    expect(Plugin::count())->toBe(0);
});

test('duplicate plugin names receive unique slugs', function () {
    Plugin::create(['name' => 'Toon Shadows', 'price' => 10, 'category' => 'Plugin']);
    $second = Plugin::create(['name' => 'Toon Shadows', 'price' => 10, 'category' => 'Plugin']);

    expect($second->slug)->toBe('toon-shadows-2');
});
