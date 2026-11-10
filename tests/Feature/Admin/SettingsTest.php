<?php

use App\Models\Payment;
use App\Models\Plugin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function settingsAdmin(): User
{
    $admin = User::factory()->create(['email' => 'admin@tooabihub.com']);
    $admin->assignRole('admin');

    return $admin;
}

/**
 * The five EmailJS values the test email and the badge both need.
 *
 * @return array<string, string>
 */
function savedEmailKeys(): array
{
    return [
        'emailjs_public_key' => 'pub-key-123',
        'emailjs_service_id' => 'service_abc',
        'template_verify' => 'template_verify',
        'template_reset' => 'template_reset',
        'template_purchase' => 'template_purchase',
    ];
}

test('the settings page shows the saved values and the admin account', function () {
    Setting::saveMany([
        'support_email' => 'help@toonapp.test',
        'whatsapp' => '+234 800 000 0000',
    ]);

    $this->actingAs(settingsAdmin())
        ->get(route('admin.settings'))
        ->assertOk()
        ->assertSee('help@toonapp.test')
        ->assertSee('+234 800 000 0000')
        ->assertSee('admin@tooabihub.com')
        ->assertSee('Not set up')
        ->assertSee('check each panel before it posts to Laravel', false);
});

test('a shopper cannot open the settings page', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)->get(route('admin.settings'))->assertForbidden();
});

test('a guest is sent to the login page', function () {
    $this->get(route('admin.settings'))->assertRedirect(route('login'));
});

test('the contact details are saved', function () {
    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.contact'), [
            'support_email' => '  help@toonapp.test  ',
            'whatsapp' => '+234 801 234 5678',
            'instagram_url' => 'https://instagram.com/toonapphub',
            'youtube_url' => 'https://youtube.com/@toonapphub',
            'support_hours' => 'Mon–Fri, 9am–6pm (WAT)',
        ])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('toast', 'Contact details saved');

    expect(Setting::values())->toMatchArray([
        'support_email' => 'help@toonapp.test',
        'whatsapp' => '+234 801 234 5678',
        'instagram_url' => 'https://instagram.com/toonapphub',
        'youtube_url' => 'https://youtube.com/@toonapphub',
        'support_hours' => 'Mon–Fri, 9am–6pm (WAT)',
    ]);
});

test('a bad contact value is rejected with a helpful message', function () {
    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.contact'), [
            'support_email' => 'not-an-email',
            'whatsapp' => 'call me sometime',
            'instagram_url' => 'http://instagram.com/toonapphub',
        ])
        ->assertSessionHasErrors([
            'support_email' => "That email doesn't look right.",
            'whatsapp' => 'Add the WhatsApp number with country code, like +234 801 234 5678.',
            'instagram_url' => 'The Instagram link should start with https://',
        ]);

    $settings = Setting::values();

    expect($settings['support_email'])->not->toBe('not-an-email')
        ->and($settings['whatsapp'])->not->toBe('call me sometime')
        ->and($settings['instagram_url'])->not->toBe('http://instagram.com/toonapphub');
});

test('the email settings are saved and flip the setup badge', function () {
    $admin = settingsAdmin();

    $this->actingAs($admin)
        ->put(route('admin.settings.email'), savedEmailKeys())
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('toast', 'Email settings saved');

    $this->actingAs($admin)
        ->get(route('admin.settings'))
        ->assertOk()
        ->assertSee('Set up')
        ->assertDontSee('Not set up');
});

test('the test email goes to EmailJS with the saved keys', function () {
    Http::preventStrayRequests();
    Http::fake(['api.emailjs.com/*' => Http::response('', 200)]);
    Setting::saveMany(savedEmailKeys());

    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.email.test'), ['test_email' => 'me@example.com'])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('toast', 'Test email sent to me@example.com. Check your inbox.');

    Http::assertSent(function (ClientRequest $request): bool {
        return $request->url() === 'https://api.emailjs.com/api/v1.0/email/send'
            && $request['service_id'] === 'service_abc'
            && $request['template_id'] === 'template_verify'
            && $request['user_id'] === 'pub-key-123'
            && $request['template_params']['to_email'] === 'me@example.com';
    });
});

test('the test email asks for the keys when nothing is saved', function () {
    Http::preventStrayRequests();

    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.email.test'), ['test_email' => 'me@example.com'])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('error', 'Save your EmailJS public key, service ID and template IDs first.');
});

test('the test email reports what EmailJS refused', function () {
    Http::preventStrayRequests();
    Http::fake(['api.emailjs.com/*' => Http::response('The service ID is invalid.', 400)]);
    Setting::saveMany(savedEmailKeys());

    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.email.test'), ['test_email' => 'me@example.com'])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('error', 'EmailJS refused the test: The service ID is invalid.');
});

test('the test email needs an address', function () {
    Setting::saveMany(savedEmailKeys());

    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.email.test'), ['test_email' => ''])
        ->assertSessionHasErrors([
            'test_email' => 'Enter the email address to send the test to.',
        ]);
});

test('the admin login keeps the old password when the current one is wrong', function () {
    $admin = settingsAdmin();

    $this->actingAs($admin)
        ->from(route('admin.settings'))
        ->put(route('admin.settings.login'), [
            'email' => 'boss@tooabihub.com',
            'current_password' => 'wrong-password',
            'password' => 'fresh-pass123',
            'password_confirmation' => 'fresh-pass123',
        ])
        ->assertSessionHasErrors([
            'current_password' => "That's not your current password.",
        ]);

    $admin->refresh();

    expect($admin->email)->toBe('admin@tooabihub.com')
        ->and(Hash::check('fresh-pass123', $admin->password))->toBeFalse();
});

test('the admin login email and password can be changed', function () {
    $admin = settingsAdmin();

    $this->actingAs($admin)
        ->put(route('admin.settings.login'), [
            'email' => 'boss@tooabihub.com',
            'current_password' => 'password',
            'password' => 'fresh-pass123',
            'password_confirmation' => 'fresh-pass123',
        ])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('toast', 'Admin login updated');

    $admin->refresh();

    expect($admin->email)->toBe('boss@tooabihub.com')
        ->and(Hash::check('password', $admin->password))->toBeFalse()
        ->and(Hash::check('fresh-pass123', $admin->password))->toBeTrue();
});

test('the admin login can update the email without a new password', function () {
    $admin = settingsAdmin();

    $this->actingAs($admin)
        ->put(route('admin.settings.login'), [
            'email' => 'boss@tooabihub.com',
            'current_password' => 'password',
        ])
        ->assertRedirect(route('admin.settings'));

    $admin->refresh();

    expect($admin->email)->toBe('boss@tooabihub.com')
        ->and(Hash::check('password', $admin->password))->toBeTrue();
});

test('another account’s email cannot be taken for the admin login', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->put(route('admin.settings.login'), [
            'email' => 'taken@example.com',
            'current_password' => 'password',
        ])
        ->assertSessionHasErrors('email');
});

test('the backup file carries the plugins, customers, orders and settings', function () {
    $admin = settingsAdmin();
    Plugin::create(['name' => 'Smear Frames', 'price' => 19]);
    $customer = User::factory()->create(['email' => 'ada@example.com']);
    $customer->assignRole('user');
    Payment::factory()->for($customer)->successful()->create();
    Setting::saveMany(['support_email' => 'help@toonapp.test']);

    $response = $this->actingAs($admin)->get(route('admin.settings.backup'));

    $response->assertOk();
    expect($response->headers->get('content-disposition'))->toContain('toonapp-backup-');

    $backup = json_decode($response->streamedContent(), true);

    expect($backup['app'])->toBe('toonapp-hub')
        ->and($backup['type'])->toBe('backup')
        ->and(collect($backup['plugins'])->pluck('name')->all())->toBe(['Smear Frames'])
        ->and(collect($backup['customers'])->pluck('email')->all())->toBe(['ada@example.com'])
        ->and($backup['settings']['support_email'])->toBe('help@toonapp.test')
        ->and($backup['orders'])->toHaveCount(1)
        ->and($backup['customer_roles'])->toHaveCount(1);
});

test('a backup restores the plugins, customers, orders and settings', function () {
    $admin = settingsAdmin();
    Plugin::create(['name' => 'Smear Frames', 'price' => 19]);
    $customer = User::factory()->create(['email' => 'ada@example.com']);
    $customer->assignRole('user');
    Payment::factory()->for($customer)->successful()->create();
    Setting::saveMany(['support_email' => 'help@toonapp.test']);

    $content = $this->actingAs($admin)->get(route('admin.settings.backup'))->streamedContent();

    // The store moves on after the backup was taken.
    Plugin::create(['name' => 'Line Boil FX', 'price' => 15]);
    User::factory()->create(['email' => 'late@example.com'])->assignRole('user');
    Payment::factory()->for($customer)->successful()->create();
    Setting::saveMany(['support_email' => 'other@toonapp.test']);

    $this->actingAs($admin)
        ->post(route('admin.settings.restore'), [
            'backup' => UploadedFile::fake()->createWithContent('backup.json', $content),
        ])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('success', 'Restored 1 plugin, 1 customer and 1 order.');

    expect(Plugin::query()->pluck('name')->all())->toBe(['Smear Frames'])
        ->and(User::shoppers()->pluck('email')->all())->toBe(['ada@example.com'])
        ->and(Setting::values()['support_email'])->toBe('help@toonapp.test')
        ->and(Payment::query()->count())->toBe(1)
        ->and(User::query()->where('email', 'admin@tooabihub.com')->exists())->toBeTrue()
        ->and(User::query()->where('email', 'ada@example.com')->first()->hasRole('user'))->toBeTrue();
});

test('a file that is not a backup changes nothing', function () {
    $admin = settingsAdmin();
    Plugin::create(['name' => 'Smear Frames']);

    $this->actingAs($admin)
        ->post(route('admin.settings.restore'), [
            'backup' => UploadedFile::fake()->createWithContent('notes.json', '{"hello": "world"}'),
        ])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('error', 'That file is not a ToonApp Hub backup.');

    expect(Plugin::query()->count())->toBe(1);
});

test('a backup missing its sections is refused', function () {
    $admin = settingsAdmin();

    $this->actingAs($admin)
        ->post(route('admin.settings.restore'), [
            'backup' => UploadedFile::fake()->createWithContent(
                'backup.json',
                json_encode(['app' => 'toonapp-hub', 'type' => 'backup']),
            ),
        ])
        ->assertRedirect(route('admin.settings'))
        ->assertSessionHas('error', 'That backup is missing its plugins, customers, orders or settings.');

    expect(Plugin::query()->count())->toBe(0);
});

test('restoring without a file is refused', function () {
    $this->actingAs(settingsAdmin())
        ->from(route('admin.settings'))
        ->post(route('admin.settings.restore'), [])
        ->assertSessionHasErrors('backup');
});
