
    <x-app-layout>
        <div class="app">
            @include('layouts.admin-nav')
            <div class="app-main">
                <header class="top">
                    <button
                        aria-controls="sidebar"
                        aria-expanded="false"
                        aria-label="Open menu"
                        class="menu-btn"
                        id="menuBtn"
                        type="button"
                    >
                        <svg
                            fill="none"
                            height="18"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            width="18"
                        >
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1>Settings</h1>
                        <div class="sub">Your profile, password and email preferences.</div>
                    </div>
                    <div class="top-right">
                        <a class="btn ghost sm store-link" href="{{ route('home') }}#shop"
                            ><svg
                                fill="none"
                                height="18"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                width="18"
                            >
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"></path>
                                <circle cx="10" cy="20.5" r="1"></circle>
                                <circle cx="18" cy="20.5" r="1"></circle></svg
                            ><span>Shop plugins</span></a
                        >
                        <div class="who">
                            <span class="av">AO</span
                            ><span class="tx"><b>{{ $user->name }}</b><small>{{ $user->email }}</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <div class="grid2">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Profile</h2>
                                    <p>Your name and country.</p>
                                </div>
                            </div>
                            <form action="{{ route('customer.settings.profile') }}" class="form" id="profForm" method="post" novalidate="">
                                @csrf
                                @method('PUT')
                                <div class="two">
                                    <div class="field">
                                        <label for="sFirst">First name</label
                                        ><input id="sFirst" maxlength="40" name="first_name" value="{{ old('first_name', $firstName) }}" /><span
                                            class="ferr"
                                            data-for="sFirst"
                                        >@error('first_name'){{ $message }}@enderror</span>
                                    </div>
                                    <div class="field">
                                        <label for="sLast">Last name</label
                                        ><input id="sLast" maxlength="40" name="last_name" value="{{ old('last_name', $lastName) }}" /><span
                                            class="ferr"
                                            data-for="sLast"
                                        >@error('last_name'){{ $message }}@enderror</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="sEmail">Email</label
                                    ><input id="sEmail" name="email" readonly="" value="{{ $user->email }}" /><small
                                        >This is the email you log in with. To use a different one, create a new
                                        account.</small
                                    >
                                </div>
                                <div class="field">
                                    <label for="sCountry">Country</label
                                    ><select id="sCountry" name="country">
                                        <option value="">Choose your country</option>
                                        @foreach (config('countries') as $country)
                                            <option value="{{ $country }}" @selected(old('country', $user->country) === $country)>{{ $country }}</option>
                                        @endforeach
                                    </select>
                                    <span class="ferr" data-for="sCountry">@error('country'){{ $message }}@enderror</span>
                                </div>
                                <div class="savebar">
                                    <button class="btn">Save profile</button
                                    ><span class="note">Member since {{ $user->created_at?->format('j M Y') }}</span>
                                </div>
                            </form>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Change password</h2>
                                    <p>Use at least 8 characters with a number.</p>
                                </div>
                            </div>
                            <form action="{{ route('customer.settings.password') }}" class="form" id="pwForm" method="post" novalidate="">
                                @csrf
                                @method('PUT')
                                <div class="field">
                                    <label for="pCur">Current password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="current-password"
                                            id="pCur"
                                            name="current_password"
                                            type="password"
                                        /><button data-peek="pCur" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pCur">@error('current_password'){{ $message }}@enderror</span>
                                </div>
                                <div class="field">
                                    <label for="pNew">New password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="new-password"
                                            id="pNew"
                                            name="password"
                                            type="password"
                                        /><button data-peek="pNew" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pNew">@error('password'){{ $message }}@enderror</span>
                                </div>
                                <div class="field">
                                    <label for="pNew2">Confirm new password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="new-password"
                                            id="pNew2"
                                            name="password_confirmation"
                                            type="password"
                                        /><button data-peek="pNew2" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pNew2"></span>
                                </div>
                                <div class="savebar"><button class="btn">Update password</button></div>
                            </form>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Email preferences</h2>
                                    <p>Choose what we send you.</p>
                                </div>
                            </div>
                            <form action="{{ route('customer.settings.newsletter') }}" id="newsForm" method="post">
                                @csrf
                                @method('PATCH')
                                <label class="toggle"
                                    ><input @checked(old('newsletter', $user->newsletter)) id="sNews" name="newsletter" type="checkbox" value="1" /> Newsletter:
                                    plugin updates and new releases</label
                                >
                                <span class="ferr" data-for="sNews">@error('newsletter'){{ $message }}@enderror</span>
                            </form>
                            <p class="note note-spaced">
                                Order receipts and download links are always sent, so you never lose a purchase.
                            </p>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Sign out</h2>
                                    <p>Log out of ToonApp Hub on this device.</p>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="post" style="display: contents">
                                @csrf
                                <button class="btn danger ghost" id="logoutBtn" type="submit"
                                    ><svg
                                        fill="none"
                                        height="18"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                        width="18"
                                    >
                                        <path
                                            d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"
                                        ></path></svg
                                    >Log out</button
                                >
                            </form>
                        </section>
                    </div>
                </main>
            </div>
        </div>

        @push('scripts')
            <script>
                /* Customer settings: check each panel before it posts to Laravel. */
                (function () {
                    "use strict";

                    var prof = document.getElementById("profForm");
                    prof.addEventListener("submit", function (e) {
                        var ok = Toon.fieldErr(
                            "sFirst",
                            prof.elements.first_name.value.trim() ? "" : "Enter your first name.",
                        );
                        ok =
                            Toon.fieldErr("sLast", prof.elements.last_name.value.trim() ? "" : "Enter your last name.") &&
                            ok;
                        ok = Toon.fieldErr("sCountry", prof.elements.country.value ? "" : "Choose your country.") && ok;
                        if (!ok) e.preventDefault();
                    });

                    var pw = document.getElementById("pwForm");
                    pw.addEventListener("submit", function (e) {
                        var f = pw.elements,
                            nw = f.password.value;
                        var ok = Toon.fieldErr("pCur", f.current_password.value ? "" : "Enter your current password.");
                        ok = Toon.fieldErr("pNew", Toon.passwordRule(nw)) && ok;
                        ok =
                            Toon.fieldErr("pNew2", f.password_confirmation.value !== nw ? "Passwords don't match." : "") &&
                            ok;
                        if (!ok) e.preventDefault();
                    });

                    document.getElementById("sNews").addEventListener("change", function (e) {
                        e.target.form.submit();
                    });
                })();
            </script>
        @endpush
    </x-app-layout>
