<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('name="fname"', false);
    $response->assertSee('name="lname"', false);
    $response->assertSee('name="country"', false);
    $response->assertSee('<option value="United States"', false);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'fname' => 'Test',
        'lname' => 'User',
        'email' => 'Test@Example.com',
        'password' => 'password1',
        'password_confirmation' => 'password1',
        'country' => 'United States',
        'news' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'country' => 'United States',
        'newsletter' => true,
    ]);
});

test('registration requires the new profile fields', function () {
    $this->followingRedirects()->from('/register')->post('/register', [
        'email' => 'test@example.com',
        'password' => 'password1',
        'password_confirmation' => 'password1',
    ])->assertSee('The first name field is required.');

    $this->assertGuest();
});
