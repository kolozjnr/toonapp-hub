<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toonapp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

          <meta name="description" content="Premium plugins, scripts and presets for animators and video editors." />
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
        <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Lilita+One&amp;family=Nunito:wght@500;600;700;800;900&amp;family=Plus+Jakarta+Sans:wght@500;600;700;800&amp;display=swap"
        />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}" />
    </head>
    <body class="">
        @include('partials.flash')

        <header class="bar">
            <div class="wrap">
                <div class="bar-in">
                    <a aria-label="ToonApp Hub home" class="logo" href="index.html"
                        ><img alt="" class="logo-ic" height="40" src="assets/images/logo.png" width="40" /><span
                            >ToonApp <b class="hub">Hub</b></span
                        ></a
                    >
                    <nav aria-label="Main" class="nav">
                        <a href="index.html#shop">Plugins</a><a href="index.html#how">How it works</a
                        ><a href="index.html#faq">FAQ</a>
                        <a data-goto="contact" href="contact.html">Contact</a>
                    </nav>
                    <div class="bar-right">
                        <!-- Logged-out visitor (shown in this template). --><a
                            class="btn sm white"
                            href="login.html"
                            id="loginLink"
                            ><svg
                                fill="none"
                                height="16"
                                stroke="currentColor"
                                stroke-width="2.8"
                                viewBox="0 0 24 24"
                                width="16"
                            >
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"></path></svg
                            ><span class="hide-sm">Log in</span></a
                        >
                        <a class="btn sm pink hide-xs" href="register.html" id="acctBtn">Sign up</a>
                        <button aria-label="Open cart" class="btn sm" id="cartBtn" type="button">
                            <svg
                                fill="none"
                                height="18"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.6"
                                viewBox="0 0 24 24"
                                width="18"
                            >
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"></path>
                                <circle cx="10" cy="20.5" r="1.3"></circle>
                                <circle cx="18" cy="20.5" r="1.3"></circle></svg
                            ><span class="cart-n">2</span></button
                        ><!-- Logged-in customer: use these buttons instead of the ones above.
<a aria-label="Updates" class="btn sm white icon-btn" href="customer/updates.html"><svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" viewBox="0 0 24 24" width="18"><path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path><path d="M10 20a2.2 2.2 0 0 0 4 0"></path></svg><span class="cart-n dot">2</span></a>
<a class="btn sm white" data-first="Ada" href="customer/dashboard.html" id="userBtn" title="Open my dashboard"><span class="avatar">AO</span><span class="hide-sm">Ada</span></a>
<button aria-label="Open cart" class="btn sm" id="cartBtn" type="button"><svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" viewBox="0 0 24 24" width="18"><path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"></path><circle cx="10" cy="20.5" r="1.3"></circle><circle cx="18" cy="20.5" r="1.3"></circle></svg><span class="cart-n">2</span></button>
--><!-- Logged-in admin: use these buttons instead (no cart).
<a class="btn sm purple" href="admin/dashboard.html"><svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-width="2.8" viewBox="0 0 24 24" width="16"><rect height="9" rx="1.5" width="7" x="3" y="3"></rect><rect height="5" rx="1.5" width="7" x="14" y="3"></rect><rect height="9" rx="1.5" width="7" x="14" y="12"></rect><rect height="5" rx="1.5" width="7" x="3" y="16"></rect></svg><span class="hide-sm">Admin dashboard</span></a>
<a class="btn sm white" href="login.html">Log out</a>
-->
                    </div>
                </div>
            </div>
        </header>

            {{-- <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"> --}}
                {{ $slot }}
            {{-- </div>
        </div> --}}
    <div class="toast" id="toast" role="status" aria-live="polite"></div>
        <script src="{{ asset('assets/js/ui.js') }}"></script>
        <script src="{{ asset('assets/js/store.js') }}"></script>
        <script src="{{ asset('assets/js/toony.js') }}"></script>

    </body>
</html>
