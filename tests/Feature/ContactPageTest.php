<?php

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the contact details the store has always shown are seeded into settings', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('support@tooabihub.com')
        ->assertSee('https://wa.me/2348012345678')
        ->assertSee('instagram.com/toonapphub')
        ->assertSee('Mon–Fri, 9am–6pm (WAT)');
});

test('the contact page shows the details saved from the settings page', function () {
    Setting::saveMany([
        'support_email' => 'help@toonapp.test',
        'whatsapp' => '+234 908 765 4321',
        'instagram_url' => 'https://instagram.com/toonapp.test',
        'youtube_url' => 'https://youtube.com/@toonapp',
        'support_hours' => 'Sat, 10am–2pm (WAT)',
    ]);

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('help@toonapp.test')
        ->assertSee('https://wa.me/2349087654321')
        ->assertSee('instagram.com/toonapp.test')
        ->assertSee('https://youtube.com/@toonapp')
        ->assertSee('Sat, 10am–2pm (WAT)');
});

test('a detail the admin clears is hidden from the page', function () {
    Setting::saveMany([
        'support_email' => null,
        'whatsapp' => null,
        'instagram_url' => null,
        'youtube_url' => null,
        'support_hours' => null,
    ]);

    $this->get(route('contact'))
        ->assertOk()
        ->assertDontSee('Reach us directly')
        ->assertDontSee('support@tooabihub.com')
        ->assertDontSee('wa.me')
        ->assertDontSee('Support hours')
        ->assertSee('How can we help?');
});

test('each contact card hides on its own', function () {
    Setting::saveMany([
        'whatsapp' => null,
        'instagram_url' => null,
        'youtube_url' => null,
        'support_hours' => null,
    ]);

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('Reach us directly')
        ->assertSee('support@tooabihub.com')
        ->assertDontSee('wa.me')
        ->assertDontSee('instagram.com')
        ->assertDontSee('youtube.com')
        ->assertDontSee('Support hours');
});

test('a visitor sends a message from the contact page into the inbox', function () {
    $this->post(route('contact.store'), [
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'topic' => 'Question about a plugin',
        'message' => 'Does the pack work with After Effects 2026?',
    ])
        ->assertRedirect(route('contact'))
        ->assertSessionHas('toast', "Message sent! We'll reply by email.");

    $this->assertDatabaseHas('messages', [
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'topic' => 'Question about a plugin',
        'message' => 'Does the pack work with After Effects 2026?',
        'read_at' => null,
    ]);
});

test('a message needs a name, a valid email, a topic and some words', function () {
    $this->from(route('contact'))
        ->post(route('contact.store'), [])
        ->assertRedirect(route('contact'))
        ->assertSessionHasErrors(['name', 'email', 'topic', 'message']);

    $this->assertDatabaseCount('messages', 0);
});

test('an unknown topic is refused with the friendly message', function () {
    $this->from(route('contact'))
        ->post(route('contact.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'topic' => 'Where is my order?',
            'message' => 'My download link is broken.',
        ])
        ->assertSessionHasErrors(['topic' => 'Choose one of the topics from the list.']);

    $this->assertDatabaseCount('messages', 0);
});

test('the contact form only takes a handful of messages a minute', function () {
    $payload = [
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'topic' => 'Question about a plugin',
        'message' => 'Does the pack work with After Effects 2026?',
    ];

    foreach (range(1, 5) as $attempt) {
        $this->post(route('contact.store'), $payload)->assertRedirect(route('contact'));
    }

    $this->post(route('contact.store'), $payload)->assertStatus(429);

    $this->assertDatabaseCount('messages', 5);
});
