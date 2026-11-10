<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('the settings page needs a signed in shopper', function () {
    $this->get(route('customer.settings'))->assertRedirect(route('login'));
});

test('the settings page shows the shoppers saved profile and the wired up forms', function () {
    $user = User::factory()->create([
        'name' => 'Chidi Bello',
        'first_name' => 'Chidi',
        'last_name' => 'Bello',
        'country' => 'Nigeria',
        'newsletter' => true,
    ]);

    $response = $this->actingAs($user)->get(route('customer.settings'));

    $response->assertOk()
        ->assertSee('Chidi Bello')
        ->assertSee($user->email)
        ->assertSee('value="Chidi"', false)
        ->assertSee('value="Bello"', false)
        ->assertSee('<option value="Nigeria" selected>Nigeria</option>', false)
        ->assertSee('<input checked id="sNews"', false)
        ->assertSee('Member since '.$user->created_at->format('j M Y'))
        ->assertSee(route('customer.settings.profile'), false)
        ->assertSee(route('customer.settings.password'), false)
        ->assertSee(route('customer.settings.newsletter'), false)
        ->assertSee('name="_token"', false)
        ->assertSee('check each panel before it posts to Laravel', false)
        ->assertDontSee('Ada Okafor')
        ->assertDontSee('ada@example.com');
});

test('the profile panel fills in a name for accounts saved before the profile columns', function () {
    $user = User::factory()->create(['name' => 'Ada Obi', 'first_name' => null, 'last_name' => null]);

    $this->actingAs($user)
        ->get(route('customer.settings'))
        ->assertOk()
        ->assertSee('value="Ada"', false)
        ->assertSee('value="Obi"', false);
});

test('the profile panel saves the name and country', function () {
    $user = User::factory()->create([
        'first_name' => 'Ada',
        'last_name' => 'Okafor',
        'country' => 'Nigeria',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.profile'), [
            'first_name' => 'Chidi',
            'last_name' => 'Bello',
            'country' => 'Ghana',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHas('toast', 'Profile saved');

    $user->refresh();

    expect($user->first_name)->toBe('Chidi')
        ->and($user->last_name)->toBe('Bello')
        ->and($user->name)->toBe('Chidi Bello')
        ->and($user->country)->toBe('Ghana');
});

test('the profile panel asks for a name and a country before saving', function () {
    $user = User::factory()->create([
        'first_name' => 'Ada',
        'last_name' => 'Okafor',
        'country' => 'Nigeria',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.profile'), [
            'first_name' => '',
            'last_name' => '',
            'country' => '',
        ]);

    $response
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHasErrors([
            'first_name' => 'Enter your first name.',
            'last_name' => 'Enter your last name.',
            'country' => 'Choose your country.',
        ]);

    $user->refresh();

    expect($user->first_name)->toBe('Ada')
        ->and($user->last_name)->toBe('Okafor')
        ->and($user->country)->toBe('Nigeria');
});

test('the profile panel rejects a country that is not on the list', function () {
    $user = User::factory()->create(['country' => 'Nigeria']);

    $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.profile'), [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'country' => 'Atlantis',
        ])
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHasErrors(['country' => 'Choose a country from the list.']);

    expect($user->refresh()->country)->toBe('Nigeria');
});

test('the profile panel leaves the login email alone', function () {
    $user = User::factory()->create(['email' => 'ada@example.com']);

    $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.profile'), [
            'first_name' => 'Ada',
            'last_name' => 'Okafor',
            'country' => 'Nigeria',
            'email' => 'someone-elses@example.com',
        ])
        ->assertSessionHasNoErrors();

    expect($user->refresh()->email)->toBe('ada@example.com');
});

test('the password panel changes the password when the current one is correct', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.password'), [
            'current_password' => 'password',
            'password' => 'new-secret1',
            'password_confirmation' => 'new-secret1',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHas('toast', 'Password updated');

    expect(Hash::check('new-secret1', $user->refresh()->password))->toBeTrue();
});

test('the password panel keeps the password when the current one is wrong', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secret1',
            'password_confirmation' => 'new-secret1',
        ]);

    $response
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHasErrors(['current_password' => "That's not your current password."]);

    expect(Hash::check('password', $user->refresh()->password))->toBeTrue();
});

test('the password panel keeps the password when the new one has no number', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->put(route('customer.settings.password'), [
            'current_password' => 'password',
            'password' => 'allletters',
            'password_confirmation' => 'allletters',
        ]);

    $response
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHasErrors('password');

    expect(Hash::check('password', $user->refresh()->password))->toBeTrue();
});

test('the newsletter toggle saves the preference', function (bool $subscribed) {
    $user = User::factory()->create(['newsletter' => ! $subscribed]);

    $response = $this
        ->actingAs($user)
        ->from(route('customer.settings'))
        ->patch(route('customer.settings.newsletter'), $subscribed ? ['newsletter' => '1'] : []);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('customer.settings'))
        ->assertSessionHas(
            'toast',
            $subscribed ? "You're subscribed to the newsletter" : "You've unsubscribed",
        );

    expect($user->refresh()->newsletter)->toBe($subscribed);
})->with([true, false]);
