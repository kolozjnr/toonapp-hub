<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingssController extends Controller
{
    /**
     * Show the settings page with the shopper's saved profile details.
     *
     * Accounts created before the profile columns existed only stored a full
     * name, so the dedicated columns are split from it when they are empty.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $fullName = trim($user->name);

        return view('customer.settings', [
            'user' => $user,
            'firstName' => $user->first_name ?: Str::before($fullName, ' '),
            'lastName' => $user->last_name ?: (Str::contains($fullName, ' ') ? Str::after($fullName, ' ') : ''),
        ]);
    }

    /**
     * Save the name and country from the profile panel.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:40'],
            'last_name' => ['required', 'string', 'max:40'],
            'country' => ['required', 'string', Rule::in(config('countries'))],
        ], [
            'first_name.required' => 'Enter your first name.',
            'last_name.required' => 'Enter your last name.',
            'country.required' => 'Choose your country.',
            'country.in' => 'Choose a country from the list.',
        ]);

        $user = $request->user();
        $user->fill($validated);
        $user->name = trim($validated['first_name'].' '.$validated['last_name']);
        $user->save();

        return back()->with('toast', 'Profile saved');
    }

    /**
     * Change the password after confirming the shopper knows the current one.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
        ], [
            'current_password.required' => 'Enter your current password.',
            'current_password.current_password' => "That's not your current password.",
            'password.required' => 'Enter a new password.',
            'password.min' => 'Use at least 8 characters.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('toast', 'Password updated');
    }

    /**
     * Store the newsletter toggle from the email preferences panel.
     */
    public function updateNewsletter(Request $request): RedirectResponse
    {
        $request->validate([
            'newsletter' => ['nullable', 'boolean'],
        ]);

        $subscribed = $request->boolean('newsletter');

        $request->user()->update([
            'newsletter' => $subscribed,
        ]);

        return back()->with('toast', $subscribed ? "You're subscribed to the newsletter" : "You've unsubscribed");
    }
}
