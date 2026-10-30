<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @stack('styles')

         <meta name="robots" content="noindex" />
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
        <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Lilita+One&amp;family=Nunito:wght@500;600;700;800;900&amp;family=Plus+Jakarta+Sans:wght@500;600;700;800&amp;display=swap"
        />
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard-theme.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('scripts-head')
    </head>
    <body>
        @include('partials.flash')

         {{-- @attributes($attributes->merge(['class' => 'font-sans antialiased'])) --}}
        {{-- <div class="min-h-screen bg-gray-100"> --}}
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            {{-- @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset --}}

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        {{-- </div> --}}

        <div class="scrim" id="sideScrim"></div>
        <div class="toast" id="toast" role="status" aria-live="polite"></div>
        <script src="{{asset('assets/js/ui.js')}}"></script>
        <script src="{{asset('assets/js/dashboard.js')}}"></script>
   
        @stack('scripts')
    </body>
</html>
