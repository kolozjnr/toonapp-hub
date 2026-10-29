<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'fname' => ['required', 'string', 'max:40'],
            'lname' => ['required', 'string', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:120', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            'country' => ['required', 'string', Rule::in(config('countries'))],
            'news' => ['nullable', 'boolean'],
        ], [], [
            'fname' => 'first name',
            'lname' => 'last name',
            'news' => 'newsletter',
        ]);

        $user = User::create([
            'name' => trim($validated['fname'].' '.$validated['lname']),
            'first_name' => $validated['fname'],
            'last_name' => $validated['lname'],
            'email' => $validated['email'],
            'country' => $validated['country'],
            'newsletter' => $request->boolean('news'),
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}