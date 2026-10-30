<nav aria-label="Admin" class="side" id="sidebar">
    <a class="brand" href="{{ route('home') }}"
        ><img alt="" src="{{ asset('assets/images/logo.png') }}" />ToonApp <b>Hub</b></a
    >
    <span class="area">Admin</span>

    <div class="group">Overview</div>
    <a @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif class="nav-i" href="{{ route('admin.dashboard') }}">Dashboard</a>

    <div class="group">Store</div>
    <a @if (request()->routeIs('admin.plugins*', 'admin.upload-plugin')) aria-current="page" @endif class="nav-i" href="{{ route('admin.plugins') }}">Plugins</a>
    <a @if (request()->routeIs('admin.plugins.create')) aria-current="page" @endif class="nav-i" href="{{ route('admin.plugins.create') }}">Upload plugin</a>
    <a @if (request()->routeIs('admin.orders')) aria-current="page" @endif class="nav-i" href="{{ route('admin.orders') }}">Orders</a>
    <a @if (request()->routeIs('admin.customers')) aria-current="page" @endif class="nav-i" href="{{ route('admin.customers') }}">Customers</a>
    <a @if (request()->routeIs('admin.reviews')) aria-current="page" @endif class="nav-i" href="{{ route('admin.reviews') }}">Reviews</a>

    <div class="group">Engage</div>
    <a @if (request()->routeIs('admin.newsletter')) aria-current="page" @endif class="nav-i" href="{{ route('admin.newsletter') }}">Newsletter</a>
    <a @if (request()->routeIs('admin.messages')) aria-current="page" @endif class="nav-i" href="{{ route('admin.messages') }}">Messages</a>
    <a @if (request()->routeIs('admin.settings')) aria-current="page" @endif class="nav-i" href="{{ route('admin.settings') }}">Settings</a>

    <span class="spacer"></span>

    <a class="nav-i" href="{{ route('home') }}">Back to store</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="nav-i out" type="submit">Log out</button>
    </form>
</nav>
