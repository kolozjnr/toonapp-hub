
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
                        <h1>Plugins</h1>
                        <div class="sub">Everything on your shelf.</div>
                    </div>
                    <div class="top-right">
                        <a class="btn ghost sm store-link" href="{{ route('home') }}"
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
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle></svg
                            ><span>View store</span></a
                        >
                        <div class="who">
                            <span class="av">AD</span
                            ><span class="tx"><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <div class="toolbar">
                        <form action="{{ route('admin.plugins') }}" class="search" method="GET" role="search">
                            <svg
                                fill="none"
                                height="16"
                                stroke="currentColor"
                                stroke-width="2.4"
                                viewBox="0 0 24 24"
                                width="16"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                            <input
                                aria-label="Search plugins"
                                data-search="#pluginRows"
                                data-search-empty="#noResults"
                                data-search-items="tr"
                                id="q"
                                name="q"
                                placeholder="Search plugins"
                                type="search"
                                value="{{ request('q') }}"
                            />
                        </form>
                        <span class="note">
                            {{ $totalPlugins }} {{ \Illuminate\Support\Str::plural('plugin', $totalPlugins) }} · {{ $livePlugins }} live
                        </span>
                        <a class="btn ml-auto" href="{{ route('admin.plugins.create') }}"
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
                                <path d="M12 16V4m-5 5 5-5 5 5"></path>
                                <path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"></path></svg
                            >Upload plugin</a
                        >
                    </div>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Plugin</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Sales</th>
                                    <th>Rating</th>
                                    <th>Tutorial</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="pluginRows">
                                @forelse ($plugins as $plugin)
                                    <tr>
                                        <td>
                                            <div class="thumb">
                                                @if ($plugin->cover_image)
                                                    <img
                                                        alt="{{ $plugin->name }} cover"
                                                        src="{{ asset('storage/'.$plugin->cover_image) }}"
                                                    />
                                                @else
                                                    <span
                                                        style="
                                                            display: grid;
                                                            place-items: center;
                                                            height: 100%;
                                                            font-size: 16px;
                                                            font-weight: 800;
                                                            color: var(--muted);
                                                        "
                                                        >{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($plugin->name, 0, 1)) }}</span
                                                    >
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <b>{{ $plugin->name }}</b>
                                            <span class="sub">
                                                {{ $plugin->category }}
                                                @if (! empty($plugin->hosts))
                                                    · {{ implode(', ', (array) $plugin->hosts) }}
                                                @endif
                                                @if ($plugin->version)
                                                    · v{{ $plugin->version }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="num">
                                            @if ((float) $plugin->price <= 0)
                                                Free
                                            @else
                                                ${{ number_format((float) $plugin->price, 2) }}
                                                @if ($plugin->old_price && (float) $plugin->old_price > (float) $plugin->price)
                                                    <s class="note">${{ number_format((float) $plugin->old_price, 2) }}</s>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($plugin->is_live)
                                                <span class="badge ok">Live</span>
                                            @else
                                                <span class="badge">Hidden</span>
                                            @endif
                                        </td>
                                        <td class="num">–</td>
                                        <td class="num">–</td>
                                        <td>
                                            @if ($plugin->youtube_url)
                                                <a
                                                    href="{{ $plugin->youtube_url }}"
                                                    rel="noopener noreferrer"
                                                    target="_blank"
                                                    >YouTube ↗</a
                                                >
                                            @else
                                                <span class="badge warn">Missing</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="acts">
                                                <a class="btn xs ghost" href="{{ route('admin.plugins.show', $plugin) }}">View</a>
                                                <a class="btn xs soft" href="{{ route('admin.plugins.edit', $plugin) }}">Edit</a>
                                                <form method="POST" action="{{ route('admin.plugins.toggle', $plugin) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn xs ghost" type="submit">
                                                        {{ $plugin->is_live ? 'Hide' : 'Show' }}
                                                    </button>
                                                </form>
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.plugins.destroy', $plugin) }}"
                                                    data-confirm="Delete “{{ $plugin->name }}”? This cannot be undone."
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn xs danger ghost" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="note" colspan="8">
                                            No plugins yet.
                                            <a href="{{ route('admin.plugins.create') }}">Upload your first plugin</a>.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr data-search-ignore="" hidden="" id="noResults">
                                    <td class="note" colspan="8">No plugins match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($plugins->hasPages())
                        <div class="toolbar" style="margin-top: 16px;">
                            <span class="note">
                                Showing {{ $plugins->firstItem() }}–{{ $plugins->lastItem() }} of {{ $plugins->total() }}
                            </span>
                            <div class="acts ml-auto">
                                @if ($plugins->onFirstPage())
                                    <span class="btn xs ghost" aria-disabled="true">Previous</span>
                                @else
                                    <a class="btn xs ghost" href="{{ $plugins->previousPageUrl() }}">Previous</a>
                                @endif
                                @if ($plugins->hasMorePages())
                                    <a class="btn xs ghost" href="{{ $plugins->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="btn xs ghost" aria-disabled="true">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener("submit", function (event) {
                    var form = event.target.closest("form[data-confirm]");

                    if (form && !window.confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });
            </script>
        @endpush
    </x-app-layout>
