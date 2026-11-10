<?php

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function newsletterAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

function ownedPlugin(string $name = 'Smear Frames'): Plugin
{
    return Plugin::create(['name' => $name, 'price' => 10, 'category' => 'Plugin']);
}

function purchaseFor(User $user, Plugin $plugin, string $status = Payment::STATUS_SUCCESS): Payment
{
    return Payment::factory()->for($user)->create([
        'status' => $status,
        'items' => [['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => 10]],
    ]);
}

test('the newsletter page counts the subscribers and offers every plugin', function () {
    User::factory()->create(['newsletter' => true]);
    User::factory()->create(['newsletter' => true]);
    User::factory()->create();
    $plugin = ownedPlugin();

    $this->actingAs(newsletterAdmin())
        ->get(route('admin.newsletter'))
        ->assertOk()
        ->assertSee('2 subscribed customers will get this.')
        ->assertSee('Everyone subscribed')
        ->assertSee('Owners of a plugin')
        ->assertSee($plugin->name)
        ->assertSee('Nothing sent yet');
});

test('a shopper cannot open the newsletter page', function () {
    $customer = User::factory()->create();
    $customer->assignRole('user');

    $this->actingAs($customer)->get(route('admin.newsletter'))->assertForbidden();
});

test('an update goes to every shopper who subscribed, and to nobody else', function () {
    Mail::fake();
    User::factory()->create(['email' => 'ada@example.com', 'newsletter' => true]);
    User::factory()->create(['email' => 'jon@example.com', 'newsletter' => true]);
    User::factory()->create(['email' => 'quiet@example.com', 'newsletter' => false]);
    User::factory()->create(['email' => 'staff@example.com', 'newsletter' => true])->assignRole('admin');

    $admin = newsletterAdmin();

    $this->actingAs($admin)
        ->post(route('admin.newsletter.store'), [
            'audience' => Newsletter::AUDIENCE_SUBSCRIBERS,
            'subject' => 'Smear Frames 1.4 is out',
            'message' => "Character presets landed.\n\nGrab the update.",
            'download_url' => 'https://drive.google.com/file/d/example',
        ])
        ->assertRedirect(route('admin.newsletter'));

    Mail::assertSentTimes(NewsletterMail::class, 2);
    Mail::assertSent(NewsletterMail::class, fn (NewsletterMail $mail): bool => $mail->hasTo('ada@example.com'));
    Mail::assertSent(NewsletterMail::class, fn (NewsletterMail $mail): bool => $mail->hasTo('jon@example.com'));
    Mail::assertNotSent(
        NewsletterMail::class,
        fn (NewsletterMail $mail): bool => $mail->hasTo('quiet@example.com')
            || $mail->hasTo('staff@example.com')
    );

    $newsletter = Newsletter::query()->sole();

    expect($newsletter->recipients_count)->toBe(2)
        ->and($newsletter->sent_by)->toBe($admin->id);
});

test('sending by plugin reaches only the opted-in owners of that plugin', function () {
    Mail::fake();
    $plugin = ownedPlugin('Squash & Stretch Pro');
    $other = ownedPlugin('Line Boil FX');

    $owner = User::factory()->create(['email' => 'owner@example.com', 'newsletter' => true]);
    purchaseFor($owner, $plugin);

    $quietOwner = User::factory()->create(['email' => 'quiet@example.com', 'newsletter' => false]);
    purchaseFor($quietOwner, $plugin);

    $subscriber = User::factory()->create(['email' => 'subscriber@example.com', 'newsletter' => true]);
    purchaseFor($subscriber, $other);

    $pending = User::factory()->create(['email' => 'pending@example.com', 'newsletter' => true]);
    purchaseFor($pending, $plugin, Payment::STATUS_PENDING);

    $this->actingAs(newsletterAdmin())
        ->post(route('admin.newsletter.store'), [
            'audience' => Newsletter::AUDIENCE_PLUGIN,
            'plugin_id' => $plugin->id,
            'subject' => 'Squash & Stretch Pro 1.4 is out',
            'message' => 'Version 1.4 adds character presets.',
        ])
        ->assertRedirect(route('admin.newsletter'));

    Mail::assertSentTimes(NewsletterMail::class, 1);
    Mail::assertSent(NewsletterMail::class, fn (NewsletterMail $mail): bool => $mail->hasTo('owner@example.com'));

    $newsletter = Newsletter::query()->sole();

    expect($newsletter->plugin_id)->toBe($plugin->id)
        ->and($newsletter->audience)->toBe(Newsletter::AUDIENCE_PLUGIN)
        ->and($newsletter->recipients_count)->toBe(1)
        ->and($newsletter->download_url)->toBeNull();
});

test('the owners count on the page only covers shoppers who opted in', function () {
    $plugin = ownedPlugin();
    purchaseFor(User::factory()->create(['newsletter' => true]), $plugin);
    purchaseFor(User::factory()->create(['newsletter' => true]), $plugin);
    purchaseFor(User::factory()->create(['newsletter' => false]), $plugin);

    $response = $this->actingAs(newsletterAdmin())->get(route('admin.newsletter'));

    $response->assertOk()->assertSee('2 subscribed customers will get this.');

    expect($response->viewData('ownerCounts'))->toBe([$plugin->id => 2]);
});

test('the form asks for a plugin when the update is written for its owners', function () {
    Mail::fake();

    $this->actingAs(newsletterAdmin())
        ->from(route('admin.newsletter'))
        ->post(route('admin.newsletter.store'), [
            'audience' => Newsletter::AUDIENCE_PLUGIN,
            'subject' => 'Smear Frames 1.4 is out',
            'message' => 'Character presets landed.',
        ])
        ->assertSessionHasErrors([
            'plugin_id' => 'Pick the plugin whose owners should get this.',
        ]);

    Mail::assertNothingSent();

    expect(Newsletter::query()->count())->toBe(0);
});

test('an update without a subject or message is not sent', function () {
    Mail::fake();
    User::factory()->create(['newsletter' => true]);

    $this->actingAs(newsletterAdmin())
        ->from(route('admin.newsletter'))
        ->post(route('admin.newsletter.store'), [
            'audience' => Newsletter::AUDIENCE_SUBSCRIBERS,
            'subject' => '',
            'message' => '',
        ])
        ->assertSessionHasErrors(['subject', 'message']);

    Mail::assertNothingSent();

    expect(Newsletter::query()->count())->toBe(0);
});

test('an update nobody subscribed to is reported instead of sent', function () {
    Mail::fake();
    User::factory()->create(['newsletter' => false]);

    $this->actingAs(newsletterAdmin())
        ->from(route('admin.newsletter'))
        ->post(route('admin.newsletter.store'), [
            'audience' => Newsletter::AUDIENCE_SUBSCRIBERS,
            'subject' => 'Smear Frames 1.4 is out',
            'message' => 'Character presets landed.',
        ])
        ->assertSessionHas('error');

    Mail::assertNothingSent();

    expect(Newsletter::query()->count())->toBe(0);
});

test('a sent update is listed with its audience, size and download flag', function () {
    Newsletter::factory()->create([
        'subject' => 'Spring sale: 25% off',
        'audience' => Newsletter::AUDIENCE_SUBSCRIBERS,
        'recipients_count' => 4,
        'download_url' => 'https://drive.google.com/file/d/example',
    ]);

    $this->actingAs(newsletterAdmin())
        ->get(route('admin.newsletter'))
        ->assertOk()
        ->assertSee('1 so far')
        ->assertSee('Spring sale: 25% off')
        ->assertSee('Everyone subscribed')
        ->assertSee('4 subscribers')
        ->assertSee('has download');
});

test('the update can be emailed to its audience again', function () {
    Mail::fake();
    User::factory()->create(['email' => 'ada@example.com', 'newsletter' => true]);
    $newsletter = Newsletter::factory()->create();

    $this->actingAs(newsletterAdmin())
        ->post(route('admin.newsletter.resend', $newsletter))
        ->assertRedirect();

    Mail::assertSentTimes(NewsletterMail::class, 1);
    Mail::assertSent(NewsletterMail::class, fn (NewsletterMail $mail): bool => $mail->hasTo('ada@example.com'));
});

test('an admin can delete a past update from the list', function () {
    $newsletter = Newsletter::factory()->create();

    $this->actingAs(newsletterAdmin())
        ->delete(route('admin.newsletter.destroy', $newsletter))
        ->assertRedirect();

    expect(Newsletter::query()->count())->toBe(0);
});

test('the newsletter email carries the message and a download button', function () {
    $mail = new NewsletterMail(
        'Squash Stretch 1.4 is out',
        "Character presets landed.\n\nGrab the update.",
        'https://drive.google.com/file/d/example',
    );

    $mail->assertHasSubject('Squash Stretch 1.4 is out')
        ->assertSeeInHtml('Character presets landed.')
        ->assertSeeInHtml('Download the update')
        ->assertSeeInHtml('https://drive.google.com/file/d/example')
        ->assertSeeInText('Character presets landed.');

    expect($mail->body)->toContain('Grab the update.');
});
