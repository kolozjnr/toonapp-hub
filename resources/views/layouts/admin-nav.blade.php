@php
    $navUser = auth()->user();
    $isAdmin = $navUser?->hasAnyRole(['superadmin', 'admin']) ?? false;
    $isCustomer = $navUser?->hasRole('user') ?? false;

    $pluginCount = $isAdmin ? \App\Models\Plugin::query()->count() : null;
    $customerCount = $isAdmin
        ? \App\Models\User::query()
            ->whereHas('roles', fn ($query) => $query->where('name', 'user'))
            ->count()
        : null;
@endphp

<nav aria-label="{{ $isAdmin ? 'Admin' : 'Account' }}" class="side" id="sidebar">
    <a class="brand" href="{{ route('home') }}">
        <img alt="" src="{{ asset('assets/images/logo.png') }}" />ToonApp <b>Hub</b>
    </a>

    <span class="area">{{ $isAdmin ? 'Admin' : 'Customer' }}</span>

    @if ($isAdmin)
        <div class="group">Overview</div>
        <a
            @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.dashboard') }}"
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
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"></path>
            </svg>
            Dashboard
        </a>

        <div class="group">Store</div>
        <a
            @if (request()->routeIs('admin.plugins', 'admin.plugins.show', 'admin.plugins.edit')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.plugins') }}"
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
                <path d="M21 8 12 3 3 8v8l9 5 9-5z"></path>
                <path d="m3 8 9 5 9-5M12 13v8"></path>
            </svg>
            Plugins
            @if ($pluginCount)
                <span class="ct">{{ $pluginCount }}</span>
            @endif
        </a>
        <a
            @if (request()->routeIs('admin.plugins.create', 'admin.upload-plugin')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.plugins.create') }}"
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
                <path d="M12 16V4m-5 5 5-5 5 5"></path>
                <path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"></path>
            </svg>
            Upload plugin
        </a>
        <a
            @if (request()->routeIs('admin.orders')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.orders') }}"
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
                <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                <path d="M9 8h6M9 12h6"></path>
            </svg>
            Orders
        </a>
        <a
            @if (request()->routeIs('admin.customers')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.customers') }}"
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
                <circle cx="9" cy="8" r="3.5"></circle>
                <path d="M2.5 20c.8-3.5 3.4-5.5 6.5-5.5s5.7 2 6.5 5.5"></path>
                <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.8c1.9.7 3.1 2.5 3.5 5.2"></path>
            </svg>
            Customers
            @if ($customerCount)
                <span class="ct">{{ $customerCount }}</span>
            @endif
        </a>
        <a
            @if (request()->routeIs('admin.reviews')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.reviews') }}"
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
                <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z"></path>
            </svg>
            Reviews
        </a>

        <div class="group">Engage</div>
        <a
            @if (request()->routeIs('admin.newsletter')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.newsletter') }}"
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
                <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                <path d="m4 7 8 6 8-6"></path>
            </svg>
            Newsletter
        </a>
        <a
            @if (request()->routeIs('admin.messages')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.messages') }}"
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
                <path d="M4 5h16v11H8l-4 4z"></path>
            </svg>
            Messages
        </a>
        <a
            @if (request()->routeIs('admin.settings')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('admin.settings') }}"
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
                <circle cx="12" cy="12" r="3"></circle>
                <path
                    d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 0 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 0 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 0 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 0 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"
                ></path>
            </svg>
            Settings
        </a>
    @endif

    @if ($isCustomer)
        <div class="group">Account</div>
        <a
            @if (request()->routeIs('customer.dashboard')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('customer.dashboard') }}"
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
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"></path>
            </svg>
            Dashboard
        </a>
        <a
            @if (request()->routeIs('customer.plugins')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('customer.plugins') }}"
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
                <path d="M21 8 12 3 3 8v8l9 5 9-5z"></path>
                <path d="m3 8 9 5 9-5M12 13v8"></path>
            </svg>
            My plugins
        </a>
        <a
            @if (request()->routeIs('customer.orders')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('customer.orders') }}"
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
                <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                <path d="M9 8h6M9 12h6"></path>
            </svg>
            Orders
        </a>
        <a
            @if (request()->routeIs('customer.updates')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('customer.updates') }}"
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
                <path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path>
                <path d="M10 20a2.2 2.2 0 0 0 4 0"></path>
            </svg>
            Updates
        </a>
        <a
            @if (request()->routeIs('customer.settings')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('customer.settings') }}"
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
                <circle cx="12" cy="12" r="3"></circle>
                <path
                    d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 0 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 0 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 0 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 0 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"
                ></path>
            </svg>
            Settings
        </a>
    @endif

    @unless ($isAdmin || $isCustomer)
        <div class="group">Account</div>
        <a
            @if (request()->routeIs('dashboard')) aria-current="page" @endif
            class="nav-i"
            href="{{ route('dashboard') }}"
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
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"></path>
            </svg>
            Dashboard
        </a>
    @endunless

    <div class="spacer"></div>

    <a class="nav-i" href="{{ route('home') }}">
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
            <path d="M4 9.5 5.5 4h13L20 9.5"></path>
            <path d="M4 9.5h16v1a3 3 0 0 1-5.3 1.9 3 3 0 0 1-5.4 0A3 3 0 0 1 4 10.5z"></path>
            <path d="M5 12.5V20h14v-7.5"></path>
        </svg>
        Back to store
    </a>

    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="nav-i out" type="submit">
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
                    <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"></path>
                </svg>
                Log out
            </button>
        </form>
    @else
        <a class="nav-i out" href="{{ route('login') }}">
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
                <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"></path>
            </svg>
            Log in
        </a>
    @endauth
</nav>
