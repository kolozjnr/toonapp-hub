
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
                        <h1>Updates</h1>
                        <div class="sub">News about your plugins and new releases.</div>
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
                            ><span class="tx"><b>{{ Auth::user()->name }}</b><small> {{ Auth::user()->email }} </small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="panel" @if (count($updates) === 0) hidden @endif>
                        <div class="panel-h">
                            <div>
                                <h2 id="updatesCount">
                                    {{ $updates->count() }} {{ \Illuminate\Support\Str::plural('update', $updates->count()) }}
                                </h2>
                                <p>Remove the ones you don't need.</p>
                            </div>
                            <button class="btn ghost sm" id="clearAll" type="button">Clear all</button>
                        </div>
                        <div class="list">
                            @forelse ($updates as $update)
                                <div class="row-i row-top">
                                    <span aria-hidden="true" class="kpi-dot"
                                        ><svg fill="none" height="18" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="18">
                                            <path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path>
                                            <path d="M10 20a2.2 2.2 0 0 0 4 0"></path>
                                        </svg></span>
                                    <div class="grow">
                                        <b>{{ $update['name'] }} {{ $update['version'] ? $update['version'].' is out' : 'was updated' }} <span class="badge brand">{{ $update['version'] ? 'v'.$update['version'] : 'Update' }}</span></b>
                                        <small>{{ $update['updated_at']->format('d M Y') }} · {{ $update['name'] }}</small>
                                        <p>{{ $update['tagline'] ?? 'You own this plugin - grab the latest build any time.' }}</p>
                                        <div class="acts align-left mt-10">
                                            @if ($update['download_url'])
                                                <a class="btn sm" href="{{ $update['download_url'] }}" rel="noopener noreferrer" target="_blank">Download update</a>
                                            @endif
                                            @if ($update['url'])
                                                <a class="btn sm ghost" href="{{ $update['url'] }}">View plugin</a>
                                            @endif
                                            @if ($update['youtube_url'])
                                                <a class="btn sm ghost" href="{{ $update['youtube_url'] }}" rel="noopener noreferrer" target="_blank">Tutorial</a>
                                            @endif
                                        </div>
                                    </div>
                                    <button aria-label="Remove this update" class="btn xs ghost" data-dismiss="u-{{ $loop->index }}" type="button">
                                        Remove
                                    </button>
                                </div>
                            @empty
                                <div class="note">Updates for the plugins you own show up here.</div>
                            @endforelse
                        </div>
                    </section>
                    <section class="panel" id="updatesEmpty" @if (count($updates) > 0) hidden @endif>
                        <div class="empty">
                            <span class="eic"
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
                                    <path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path>
                                    <path d="M10 20a2.2 2.2 0 0 0 4 0"></path></svg
                            ></span>
                            <h3>You're all caught up</h3>
                            <p>News about plugin updates and new releases will show up here and in your inbox.</p>
                        </div>
                    </section>
                </main>
            </div>
        </div>
      
        @push('scripts')
            <script>
                /* Customer updates: remove one update or clear them all. */
                (function () {
                    "use strict";
                    var content = document.getElementById("content");
                    var panel = content.querySelector(".panel:not(#updatesEmpty)");
                    function rows() {
                        return Toon.$$("[data-dismiss]", panel).map(function (b) {
                            return b.closest(".row-i");
                        });
                    }
                    function refresh() {
                        var n = rows().length;
                        document.getElementById("updatesCount").textContent = n + " update" + (n === 1 ? "" : "s");
                        panel.hidden = n === 0;
                        document.getElementById("updatesEmpty").hidden = n > 0;
                    }
                    content.addEventListener("click", function (e) {
                        var one = e.target.closest("[data-dismiss]");
                        if (one) {
                            // DEMO: Laravel hides this update for the customer.
                            one.closest(".row-i").remove();
                            refresh();
                            return;
                        }
                        var all = e.target.closest("#clearAll");
                        if (all && Toon.armed(all, "Tap again to clear all")) {
                            rows().forEach(function (r) {
                                r.remove();
                            });
                            refresh();
                            Toon.toast("All updates cleared");
                        }
                    });
                })();
            </script>
        @endpush
    </x-app-layout>
