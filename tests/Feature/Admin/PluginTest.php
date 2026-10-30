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

test('the plugin form includes the client-side image size guard', function () {
    $this->actingAs(pluginAdmin())
        ->get(route('admin.plugins.create'))
        ->assertOk()
        ->assertSee('larger than 4 MB');
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

test('the plugin list is loaded from the database', function () {
    $plugin = Plugin::create([
        'name' => 'Database Only Plugin',
        'price' => 19,
        'category' => 'Plugin',
        'is_live' => true,
    ]);

    $this->actingAs(pluginAdmin())
        ->get(route('admin.plugins'))
        ->assertOk()
        ->assertSee('Database Only Plugin')
        ->assertSee('1 plugin · 1 live')
        ->assertSee(route('admin.plugins.show', $plugin), false)
        ->assertSee(route('admin.plugins.edit', $plugin), false)
        ->assertSee(route('admin.plugins.toggle', $plugin), false)
        ->assertSee(route('admin.plugins.destroy', $plugin), false)
        ->assertDontSee('Smear Frames')
        ->assertDontSee('Squash &amp; Stretch Pro', false);
});

test('the plugin list can be searched', function () {
    Plugin::create(['name' => 'Alpha Plugin', 'price' => 10, 'category' => 'Plugin']);
    Plugin::create(['name' => 'Beta Plugin', 'price' => 20, 'category' => 'Script']);

    $this->actingAs(pluginAdmin())
        ->get(route('admin.plugins', ['q' => 'Alpha']))
        ->assertOk()
        ->assertSee('Alpha Plugin')
        ->assertDontSee('Beta Plugin');
});

test('an admin can view a plugin', function () {
    $plugin = Plugin::create([
        'name' => 'Toon Shadows',
        'tagline' => 'Soft shadows in one click.',
        'price' => 24,
        'category' => 'Plugin',
    ]);

    $this->actingAs(pluginAdmin())
        ->get(route('admin.plugins.show', $plugin))
        ->assertOk()
        ->assertSee('Toon Shadows')
        ->assertSee('Soft shadows in one click.')
        ->assertSee(route('admin.plugins.edit', $plugin), false);
});

test('an admin can hide and show a plugin', function () {
    $admin = pluginAdmin();
    $plugin = Plugin::create([
        'name' => 'Bounce Presets',
        'price' => 0,
        'category' => 'Preset pack',
        'is_live' => true,
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.plugins.toggle', $plugin))
        ->assertRedirect();

    expect($plugin->refresh()->is_live)->toBeFalse();

    $this->actingAs($admin)
        ->patch(route('admin.plugins.toggle', $plugin))
        ->assertRedirect();

    expect($plugin->refresh()->is_live)->toBeTrue();
});

test('an admin can delete a plugin and its artwork', function () {
    Storage::fake('public');

    $cover = UploadedFile::fake()->image('cover.png')->store('plugins/covers', 'public');
    $shot = UploadedFile::fake()->image('shot.png')->store('plugins/gallery', 'public');

    $plugin = Plugin::create([
        'name' => 'Temporary Plugin',
        'price' => 5,
        'category' => 'Plugin',
        'cover_image' => $cover,
        'gallery' => [['path' => $shot, 'title' => 'Shot', 'text' => '']],
    ]);

    $this->actingAs(pluginAdmin())
        ->delete(route('admin.plugins.destroy', $plugin))
        ->assertRedirect(route('admin.plugins'));

    expect(Plugin::find($plugin->id))->toBeNull();

    Storage::disk('public')->assertMissing($cover);
    Storage::disk('public')->assertMissing($shot);
});
