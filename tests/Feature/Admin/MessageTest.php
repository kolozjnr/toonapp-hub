<?php

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

function inboxAdmin(): User
{
    $admin = User::factory()->create(['email' => 'inbox@tooabihub.com']);
    $admin->assignRole('admin');

    return $admin;
}

test('the messages page lists the inbox with counts and reply links', function () {
    Message::factory()->create([
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'topic' => 'Feature request',
    ]);
    Message::factory()->create([
        'name' => 'Tunde Bello',
        'email' => 'tunde@example.com',
        'topic' => 'Question about a plugin',
    ]);
    Message::factory()->read()->create([
        'name' => 'Zainab Okoye',
        'email' => 'zainab@example.com',
    ]);

    $this->actingAs(inboxAdmin())
        ->get(route('admin.messages'))
        ->assertOk()
        ->assertSee('Ada Obi')
        ->assertSee('zainab@example.com')
        ->assertSee('2 new')
        ->assertSee('All (3)')
        ->assertSee('Unread (2)')
        ->assertSee('Mark read')
        ->assertSee('Mark unread')
        ->assertSee('to=ada%40example.com')
        ->assertSee('su=Re%3A%20Feature%20request');
});

test('an empty inbox says so', function () {
    $this->actingAs(inboxAdmin())
        ->get(route('admin.messages'))
        ->assertOk()
        ->assertSee('No messages yet')
        ->assertSee('All (0)');
});

test('a guest is sent to the login page from the messages inbox', function () {
    $this->get(route('admin.messages'))->assertRedirect(route('login'));
});

test('a shopper cannot open the messages inbox', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.messages'))
        ->assertForbidden();
});

test('an admin marks a message as read', function () {
    $message = Message::factory()->create();

    $this->actingAs(inboxAdmin())
        ->from(route('admin.messages'))
        ->patch(route('admin.messages.read', $message), ['read' => 1])
        ->assertRedirect(route('admin.messages'))
        ->assertSessionHas('toast', 'Message marked as read');

    expect($message->fresh()->read_at)->not->toBeNull();
});

test('an admin can mark a message as unread again', function () {
    $message = Message::factory()->read()->create();

    $this->actingAs(inboxAdmin())
        ->from(route('admin.messages'))
        ->patch(route('admin.messages.read', $message), ['read' => 0])
        ->assertRedirect(route('admin.messages'))
        ->assertSessionHas('toast', 'Message marked as unread');

    expect($message->fresh()->read_at)->toBeNull();
});

test('the read state has to be a boolean', function () {
    $message = Message::factory()->create();

    $this->actingAs(inboxAdmin())
        ->patch(route('admin.messages.read', $message), ['read' => 'maybe'])
        ->assertSessionHasErrors('read');

    expect($message->fresh()->read_at)->toBeNull();
});

test('an admin deletes a message', function () {
    $message = Message::factory()->create();

    $this->actingAs(inboxAdmin())
        ->from(route('admin.messages'))
        ->delete(route('admin.messages.destroy', $message))
        ->assertRedirect(route('admin.messages'))
        ->assertSessionHas('success', 'Message deleted.');

    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});
