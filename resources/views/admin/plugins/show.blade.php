@php
    $gallery = collect($plugin->gallery ?? [])->filter(fn ($image) => ! empty($image['path']));
    $features = collect(preg_split('/\r\n|\r|\n/', (string) $plugin->features))->filter()->values();
@endphp

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
                    <h1>{{ $plugin->name }}</h1>
                    <div class="sub">
                        {{ $plugin->category }}
                        @if (! empty($plugin->hosts))
                            · {{ implode(', ', (array) $plugin->hosts) }}
                        @endif
                        @if ($plugin->version)
                            · v{{ $plugin->version }}
                        @endif
                    </div>
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
                    <a class="btn ghost sm" href="{{ route('admin.plugins') }}">← Back to plugins</a>
                    <div class="acts ml-auto">
                        <a class="btn sm soft" href="{{ route('admin.plugins.edit', $plugin) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.plugins.toggle', $plugin) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn sm ghost" type="submit">
                                {{ $plugin->is_live ? 'Hide from store' : 'Show in store' }}
                            </button>
                        </form>
                        <form
                            method="POST"
                            action="{{ route('admin.plugins.destroy', $plugin) }}"
                            data-confirm="Delete “{{ $plugin->name }}”? This cannot be undone."
                        >
                            @csrf
                            @method('DELETE')
                            <button class="btn sm danger ghost" type="submit">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="form-layout">
                    <div class="form-main">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Cover image</h2>
                                    <p>Shown on the shelf and at the top of the plugin page.</p>
                                </div>
                            </div>
                            @if ($plugin->cover_image)
                                <img
                                    alt="{{ $plugin->name }} cover"
                                    src="{{ asset('storage/'.$plugin->cover_image) }}"
                                    style="display: block; width: 100%; max-width: 560px; border: 1px solid var(--line); border-radius: 12px;"
                                />
                            @else
                                <p class="note">No cover image has been uploaded.</p>
                            @endif
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>About this plugin</h2>
                                    <p>{{ $plugin->tagline ?: 'No tagline yet.' }}</p>
                                </div>
                            </div>
                            <p style="white-space: pre-line;">{{ $plugin->description ?: 'No description yet.' }}</p>

                            @if ($features->isNotEmpty())
                                <h3 style="margin-top: 18px; font-size: 15px;">Key features</h3>
                                <ul style="margin: 8px 0 0; padding-left: 20px;">
                                    @foreach ($features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Extra images <span class="badge plain">{{ $gallery->count() }} of 4</span></h2>
                                    <p>Shown in the gallery on the plugin page.</p>
                                </div>
                            </div>

                            @if ($gallery->isNotEmpty())
                                <div class="shot-slots">
                                    @foreach ($gallery as $image)
                                        <div class="slot">
                                            <img
                                                alt="{{ $image['title'] ?? 'Plugin gallery image' }}"
                                                src="{{ asset('storage/'.$image['path']) }}"
                                                style="width: 100%; border-radius: 9px; display: block;"
                                            />
                                            @if (! empty($image['title']))
                                                <b>{{ $image['title'] }}</b>
                                            @endif
                                            @if (! empty($image['text']))
                                                <span class="note">{{ $image['text'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="note">No extra images have been uploaded.</p>
                            @endif
                        </section>
                    </div>

                    <aside class="form-side">
                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Status</h2></div>
                            </div>
                            <div class="form">
                                @if ($plugin->is_live)
                                    <span class="badge ok">Live in the store</span>
                                @else
                                    <span class="badge">Hidden from customers</span>
                                @endif
                                <p class="note">
                                    Added {{ $plugin->created_at?->format('M j, Y') }}
                                    @if ($plugin->creator)
                                        by {{ $plugin->creator->name }}
                                    @endif
                                </p>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Pricing</h2></div>
                            </div>
                            <div class="form">
                                <div class="field">
                                    <span class="lab">Price</span>
                                    <b>
                                        @if ((float) $plugin->price <= 0)
                                            Free
                                        @else
                                            ${{ number_format((float) $plugin->price, 2) }}
                                        @endif
                                    </b>
                                </div>
                                @if ($plugin->old_price)
                                    <div class="field">
                                        <span class="lab">Old price</span>
                                        <b>${{ number_format((float) $plugin->old_price, 2) }}</b>
                                    </div>
                                @endif
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Details</h2></div>
                            </div>
                            <div class="form">
                                <div class="field">
                                    <span class="lab">Category</span>
                                    <b>{{ $plugin->category }}</b>
                                </div>
                                @if ($plugin->badge)
                                    <div class="field">
                                        <span class="lab">Badge</span>
                                        <b>{{ $plugin->badge }}</b>
                                    </div>
                                @endif
                                @if (! empty($plugin->hosts))
                                    <div class="field">
                                        <span class="lab">Works with</span>
                                        <b>{{ implode(', ', (array) $plugin->hosts) }}</b>
                                    </div>
                                @endif
                                @if ($plugin->version)
                                    <div class="field">
                                        <span class="lab">Version</span>
                                        <b>{{ $plugin->version }}</b>
                                    </div>
                                @endif
                                @if ($plugin->requirements)
                                    <div class="field">
                                        <span class="lab">Requirements</span>
                                        <b>{{ $plugin->requirements }}</b>
                                    </div>
                                @endif
                                @if ($plugin->youtube_url)
                                    <div class="field">
                                        <span class="lab">Tutorial</span>
                                        <a href="{{ $plugin->youtube_url }}" rel="noopener noreferrer" target="_blank">
                                            Watch on YouTube ↗
                                        </a>
                                    </div>
                                @endif
                                @if ($plugin->download_url)
                                    <div class="field">
                                        <span class="lab">Download</span>
                                        <a href="{{ $plugin->download_url }}" rel="noopener noreferrer" target="_blank">
                                            Download link ↗
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </aside>
                </div>
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
