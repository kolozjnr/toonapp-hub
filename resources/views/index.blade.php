<x-guest-layout>

        <main class="wrap">
            <section class="hero">
                <div>
                    <h1>
                        Plugins that make your <span class="hl">animation</span> and
                        <span class="hl">video editing</span> <span class="hl2">pop!</span>
                    </h1>
                    <p>
                        Premium tools for animators and video editors. Discover professionally crafted effects, scripts,
                        and presets designed to streamline your workflow. Learn with step-by-step tutorials, pay once,
                        and download instantly. 😊
                    </p>
                    <div class="cta">
                        <a class="btn" href="#shop">Browse plugins</a><a class="btn white" href="#how">How it works</a>
                    </div>
                    <div class="apps" id="heroApps">
                        <span class="host host-ae" title="After Effects">AE</span
                        ><span class="host host-pr" title="Premiere Pro">PR</span
                        ><span class="host host-dr" title="DaVinci Resolve">DR</span
                        ><span class="host host-c4d" title="Cinema 4D">C4D</span>
                    </div>
                </div>
                <div aria-hidden="true" class="mascot-box" id="heroMascot">
                    <span class="bubble" id="heroBubble">Plug me in!</span
                    ><svg class="toon" id="mascot" viewBox="0 0 360 370">
                        <ellipse class="shadow" cx="180" cy="345" fill="rgba(27,21,48,.22)" rx="92" ry="14"></ellipse>
                        <g class="bob">
                            <path
                                d="M268 250 C 320 250, 330 300, 300 318 S 250 340, 270 360"
                                fill="none"
                                stroke="#1B1530"
                                stroke-linecap="round"
                                stroke-width="6"
                            ></path>
                            <rect
                                fill="#FFD23F"
                                height="40"
                                rx="10"
                                stroke="#1B1530"
                                stroke-width="5"
                                width="44"
                                x="246"
                                y="222"
                            ></rect>
                            <rect fill="#1B1530" height="20" rx="3" width="8" x="254" y="206"></rect>
                            <rect fill="#1B1530" height="20" rx="3" width="8" x="274" y="206"></rect>
                            <path
                                d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                                fill="#7B5CFF"
                                stroke="#1B1530"
                                stroke-width="6"
                            ></path>
                            <path
                                d="M95 150 C 110 110, 140 92, 165 88"
                                fill="none"
                                stroke="#fff"
                                stroke-linecap="round"
                                stroke-opacity=".55"
                                stroke-width="10"
                            ></path>
                            <ellipse
                                class="eye"
                                cx="135"
                                cy="190"
                                fill="#fff"
                                rx="30"
                                ry="34"
                                stroke="#1B1530"
                                stroke-width="5"
                            ></ellipse>
                            <ellipse
                                class="eye"
                                cx="225"
                                cy="190"
                                fill="#fff"
                                rx="30"
                                ry="34"
                                stroke="#1B1530"
                                stroke-width="5"
                            ></ellipse>
                            <g class="laugh-eyes" fill="none" stroke="#1B1530" stroke-linecap="round" stroke-width="8">
                                <path d="M108 198 Q135 168 162 198"></path>
                                <path d="M198 198 Q225 168 252 198"></path>
                            </g>
                            <g class="tears" fill="#7FD3FF" stroke="#1B1530" stroke-width="3">
                                <path class="tear" d="M100 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                                <path class="tear tear-late" d="M260 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                            </g>
                            <circle
                                class="pupil"
                                cx="135"
                                cy="194"
                                data-cx="135"
                                data-cy="194"
                                fill="#1B1530"
                                r="13"
                            ></circle>
                            <circle
                                class="pupil"
                                cx="225"
                                cy="194"
                                data-cx="225"
                                data-cy="194"
                                fill="#1B1530"
                                r="13"
                            ></circle>
                            <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                            <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                            <path
                                class="mouth"
                                d="M150 248 Q180 280 210 248"
                                fill="#FF5C8A"
                                stroke="#1B1530"
                                stroke-linejoin="round"
                                stroke-width="6"
                            ></path>
                            <path
                                d="M250 255 C 268 250, 270 240, 262 236"
                                fill="none"
                                stroke="#1B1530"
                                stroke-linecap="round"
                                stroke-width="6"
                            ></path>
                        </g>
                    </svg>
                </div>
            </section>
            <section
                id="shop"
                data-all-total="{{ $allTotal }}"
                data-category="{{ $filters['category'] }}"
                data-fallback-image="{{ asset('assets/images/logo.png') }}"
                data-feed-url="{{ $feedUrl }}"
                data-filtered-total="{{ $plugins->total() }}"
                data-has-more="{{ $plugins->hasMorePages() ? '1' : '0' }}"
                data-page="{{ $plugins->currentPage() }}"
                data-q="{{ $filters['q'] }}"
                data-shown="{{ $plugins->count() }}"
                data-sort="{{ $filters['sort'] }}"
            >
                <div class="shop-head">
                    <h2>The plugin shelf</h2>
                    <span aria-live="polite" class="note" id="countNote">
                        @if ($allTotal > 0)
                            Showing {{ $plugins->count() }} of {{ $allTotal }} {{ \Illuminate\Support\Str::plural('plugin', $allTotal) }}
                        @else
                            0 plugins
                        @endif
                    </span>
                </div>
                <div class="tools">
                    <div aria-label="Category" class="chip-row" id="cats" role="group">
                        <button
                            aria-pressed="{{ $filters['category'] === 'All' ? 'true' : 'false' }}"
                            class="chip"
                            data-cat="All"
                            type="button"
                        >
                            All
                        </button>
                        @foreach ($categories as $category)
                            <button
                                aria-pressed="{{ $filters['category'] === $category ? 'true' : 'false' }}"
                                class="chip"
                                data-cat="{{ $category }}"
                                type="button"
                            >
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                    <label class="search"
                        ><svg
                            fill="none"
                            height="18"
                            stroke="currentColor"
                            stroke-width="3"
                            viewBox="0 0 24 24"
                            width="18"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path></svg
                        ><input
                            aria-label="Search plugins"
                            id="q"
                            placeholder="Search plugins"
                            type="search"
                            value="{{ $filters['q'] }}"
                    /></label>
                    <select aria-label="Sort" class="sort" id="sort">
                        <option @selected($filters['sort'] === 'new') value="new">Newest</option>
                        <option @selected($filters['sort'] === 'top') value="top">Top rated</option>
                        <option @selected($filters['sort'] === 'low') value="low">Price: low to high</option>
                        <option @selected($filters['sort'] === 'high') value="high">Price: high to low</option>
                    </select>
                </div>
                <div class="grid" id="grid">
                    @foreach ($plugins as $plugin)
                        @include('partials.plugin-card', ['plugin' => $plugin])
                    @endforeach
                </div>
                <div class="empty" id="noMatches" @if ($plugins->count() > 0) hidden @endif>
                    <h3 id="emptyTitle">
                        {{ $filters['q'] !== '' || $filters['category'] !== 'All' ? 'No matches' : 'New plugins are on the way' }}
                    </h3>
                    <p id="emptyText">
                        {{ $filters['q'] !== '' || $filters['category'] !== 'All' ? 'Try another category or clear the search.' : 'Nothing on the shelf yet. Check back soon.' }}
                    </p>
                </div>
                <div class="load-more" id="loadMore" hidden>
                    <span aria-hidden="true" class="spinner"></span><span>Loading more plugins…</span>
                </div>
                <div class="load-more" id="loadMoreFallback" hidden>
                    <button class="btn sm white" id="loadMoreBtn" type="button">Load more plugins</button>
                </div>
                <div aria-hidden="true" id="scrollSentinel"></div>
            </section>
            <section class="how" id="how">
                <div class="step">
                    <div class="n bg-yellow">1</div>
                    <h3>Watch the tutorial</h3>
                    <p>Every plugin has a video walkthrough on YouTube so you know what you're getting.</p>
                </div>
                <div class="step">
                    <div class="n bg-pink">2</div>
                    <h3>Create an account and pay</h3>
                    <p>Sign up once, then pay by card. No subscription.</p>
                </div>
                <div class="step">
                    <div class="n bg-mint">3</div>
                    <h3>Download and rate</h3>
                    <p>
                        Your download link arrives by email and stays in your dashboard. Leave a rating to help other
                        animators.
                    </p>
                </div>
            </section>
            <section class="faq" id="faq">
                <h2 class="section-title">Questions</h2>
                <details>
                    <summary>Which apps do the plugins work with?</summary>
                    <p>Each plugin page lists its host apps and the versions it supports.</p>
                </details>
                <details>
                    <summary>Do I get updates?</summary>
                    <p>Yes. Updates within the same major version are free and appear in your dashboard and inbox.</p>
                </details>
                <details>
                    <summary>Can I use them on client work?</summary>
                    <p>Yes. Every license covers personal and commercial projects for one person.</p>
                </details>
                <details>
                    <summary>What if it doesn't work for me?</summary>
                    <p>Send us a message from the Contact page and we'll help you get it working.</p>
                </details>
            </section>
        </main>
        <footer class="site-foot">
            <div class="wrap foot-in">
                <span>© 2026 ToonApp Hub</span><span><a href="{{ route('contact') }}">Contact us</a> · Made for animators</span>
            </div>
        </footer>
        @include('partials.cart-drawer')
        <div aria-hidden="true" class="buddy off" id="buddy">
            <div class="buddy-bubble hide"></div>
            <svg aria-hidden="true" class="toon" viewBox="0 0 360 370">
                <ellipse class="shadow" cx="180" cy="345" fill="rgba(27,21,48,.22)" rx="92" ry="14"></ellipse>
                <g class="bob">
                    <path
                        d="M268 250 C 320 250, 330 300, 300 318 S 250 340, 270 360"
                        fill="none"
                        stroke="#1B1530"
                        stroke-linecap="round"
                        stroke-width="6"
                    ></path>
                    <rect
                        fill="#FFD23F"
                        height="40"
                        rx="10"
                        stroke="#1B1530"
                        stroke-width="5"
                        width="44"
                        x="246"
                        y="222"
                    ></rect>
                    <rect fill="#1B1530" height="20" rx="3" width="8" x="254" y="206"></rect>
                    <rect fill="#1B1530" height="20" rx="3" width="8" x="274" y="206"></rect>
                    <path
                        d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                        fill="#7B5CFF"
                        stroke="#1B1530"
                        stroke-width="6"
                    ></path>
                    <path
                        d="M95 150 C 110 110, 140 92, 165 88"
                        fill="none"
                        stroke="#fff"
                        stroke-linecap="round"
                        stroke-opacity=".55"
                        stroke-width="10"
                    ></path>
                    <ellipse
                        class="eye"
                        cx="135"
                        cy="190"
                        fill="#fff"
                        rx="30"
                        ry="34"
                        stroke="#1B1530"
                        stroke-width="5"
                    ></ellipse>
                    <ellipse
                        class="eye"
                        cx="225"
                        cy="190"
                        fill="#fff"
                        rx="30"
                        ry="34"
                        stroke="#1B1530"
                        stroke-width="5"
                    ></ellipse>
                    <g class="laugh-eyes" fill="none" stroke="#1B1530" stroke-linecap="round" stroke-width="8">
                        <path d="M108 198 Q135 168 162 198"></path>
                        <path d="M198 198 Q225 168 252 198"></path>
                    </g>
                    <g class="tears" fill="#7FD3FF" stroke="#1B1530" stroke-width="3">
                        <path class="tear" d="M100 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                        <path class="tear tear-late" d="M260 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                    </g>
                    <circle class="pupil" cx="135" cy="194" data-cx="135" data-cy="194" fill="#1B1530" r="13"></circle>
                    <circle class="pupil" cx="225" cy="194" data-cx="225" data-cy="194" fill="#1B1530" r="13"></circle>
                    <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                    <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                    <path
                        class="mouth"
                        d="M150 248 Q180 280 210 248"
                        fill="#FF5C8A"
                        stroke="#1B1530"
                        stroke-linejoin="round"
                        stroke-width="6"
                    ></path>
                    <path
                        d="M250 255 C 268 250, 270 240, 262 236"
                        fill="none"
                        stroke="#1B1530"
                        stroke-linecap="round"
                        stroke-width="6"
                    ></path>
                </g>
            </svg>
        </div>

        @push('scripts')
            <script src="{{ asset('assets/js/pages/home.js') }}"></script>
        @endpush
    </x-guest-layout>
   
