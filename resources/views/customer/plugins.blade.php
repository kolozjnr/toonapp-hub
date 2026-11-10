
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
                        <h1>My plugins</h1>
                        <div class="sub">Everything you own, ready to download.</div>
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
                    <section class="panel" id="reviewPanel" {{ old('plugin_id') ? '' : 'hidden' }}>
                        <div class="panel-h">
                            <div>
                                <h2 id="reviewTitle">Write a review</h2>
                                <p id="reviewNote">Rate the plugin and tell other animators how it went.</p>
                            </div>
                        </div>
                        <form
                            action="{{ route('customer.plugins.review', '__plugin__') }}"
                            class="form"
                            id="reviewForm"
                            method="post"
                        >
                            @csrf
                            <input id="reviewPluginId" name="plugin_id" type="hidden" value="{{ old('plugin_id') }}" /><input
                                id="reviewPluginName"
                                name="plugin_name"
                                type="hidden"
                                value="{{ old('plugin_name') }}"
                            />
                            <div class="field">
                                <span class="lab" id="reviewRatingLabel">Rating <small>(required)</small></span>
                                <div aria-labelledby="reviewRatingLabel" class="rate-pick" role="radiogroup">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <input
                                            @checked((string) old('rating') === (string) $star)
                                            id="rating{{ $star }}"
                                            name="rating"
                                            type="radio"
                                            value="{{ $star }}"
                                        /><label for="rating{{ $star }}"
                                            ><span aria-hidden="true">★</span><span class="visually-hidden"
                                                >{{ $star }} {{ \Illuminate\Support\Str::plural('star', $star) }}</span
                                            ></label
                                        >
                                    @endfor
                                </div>
                                <span class="ferr">@error('rating'){{ $message }}@enderror</span>
                            </div>
                            <div class="field">
                                <label for="reviewComment">Review <small>(optional)</small></label
                                ><textarea
                                    id="reviewComment"
                                    maxlength="1000"
                                    name="comment"
                                    placeholder="What worked, what you'd change, and who it's for."
                                >{{ old('comment') }}</textarea
                                ><span class="ferr">@error('comment'){{ $message }}@enderror</span>
                            </div>
                            <div class="savebar">
                                <button class="btn" type="submit">Submit review</button
                                ><button class="btn ghost" id="reviewCancel" type="button">Cancel</button>
                            </div>
                        </form>
                    </section>
                    <div class="toolbar">
                        <label class="search"
                            ><svg
                                fill="none"
                                height="16"
                                stroke="currentColor"
                                stroke-width="2.4"
                                viewBox="0 0 24 24"
                                width="16"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path></svg
                            ><input
                                aria-label="Search my plugins"
                                data-search="#tiles"
                                data-search-empty="#noResults"
                                data-search-items=".ptile"
                                id="q"
                                name="q"
                                placeholder="Search my plugins"
                                type="search"
                                value="" /></label
                        ><span class="note"
                            >{{ $plugins->count() }} {{ \Illuminate\Support\Str::plural('plugin', $plugins->count()) }}</span
                        >
                    </div>
                    <div class="ptiles" id="tiles">
                        @forelse ($plugins as $plugin)
                            @php($myReview = $myReviews->get($plugin['plugin_id']))
                            <article class="ptile">
                                <span class="pc"><img alt="" src="{{ $plugin['cover'] }}" /></span>
                                <div class="pb">
                                    <h3>{{ $plugin['name'] }}</h3>
                                    <span class="meta"
                                        >{{ $plugin['version'] ? 'Version '.$plugin['version'].' · ' : '' }}Bought
                                        {{ $plugin['bought_at']->format('d M Y') }}{{
                                            $plugin['order'] ? ' · Order '.$plugin['order'] : ''
                                        }}</span
                                    >
                                    @if (count($plugin['hosts']))
                                        <span class="meta"
                                            >Works with {{ implode(', ', array_column($plugin['hosts'], 'name')) }}</span
                                        >
                                    @endif
                                    <div class="pa">
                                        @if ($plugin['download_url'])
                                            <a
                                                class="btn sm"
                                                href="{{ $plugin['download_url'] }}"
                                                rel="noopener noreferrer"
                                                target="_blank"
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
                                                    <path d="M12 4v11m-5-5 5 5 5-5"></path>
                                                    <path d="M4 19h16"></path></svg
                                                >Download</a
                                            >
                                        @elseif ($plugin['url'])
                                            <a class="btn sm" href="{{ $plugin['url'] }}">View plugin</a>
                                        @endif
                                        @if ($plugin['youtube_url'])
                                            <a
                                                class="btn sm ghost"
                                                href="{{ $plugin['youtube_url'] }}"
                                                rel="noopener noreferrer"
                                                target="_blank"
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
                                                    <path d="M7 4.5v15l12-7.5z"></path></svg
                                                >Tutorial</a
                                            >
                                        @endif
                                        @if ($plugin['url'])
                                            <button
                                                class="btn sm ghost"
                                                data-comment="{{ $myReview?->comment ?? '' }}"
                                                data-plugin="{{ $plugin['plugin_id'] }}"
                                                data-plugin-name="{{ $plugin['name'] }}"
                                                data-rating="{{ $myReview?->rating ?? '' }}"
                                                data-review=""
                                                type="button"
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
                                                        d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z"
                                                    ></path></svg
                                                >{{ $myReview ? 'Edit review' : 'Write review' }}</button
                                            >
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="empty">
                                <h3>No plugins yet</h3>
                                <p>Buy a plugin and it lands here with its download link.</p>
                                <a class="btn" href="{{ route('home') }}#shop">Browse plugins</a>
                            </div>
                        @endforelse
                    </div>
                    <p class="note" hidden="" id="noResults">No plugins match your search.</p>
                </main>
            </div>
        </div>

        @push('styles')
            <style>
                /* Star picker for the review form: the chosen star and every
                   star before it light up. */
                .rate-pick {
                    position: relative;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }
                .rate-pick input {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 1px;
                    height: 1px;
                    margin: 0;
                    opacity: 0;
                    pointer-events: none;
                }
                .rate-pick label {
                    display: inline-flex;
                    align-items: center;
                    padding: 4px;
                    border-radius: 8px;
                    font-size: 26px;
                    line-height: 1;
                    color: var(--line2);
                    cursor: pointer;
                }
                .rate-pick input:checked + label,
                .rate-pick label:has(~ input:checked),
                .rate-pick label:has(+ input:checked) {
                    color: #f5b300;
                }
                .rate-pick input:focus-visible + label {
                    outline: 2px solid var(--brand);
                    outline-offset: 2px;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                /* My plugins: the Write review button fills the form at the top
                   of the page and points it at that plugin. */
                (function () {
                    "use strict";

                    var panel = document.getElementById("reviewPanel");
                    var form = document.getElementById("reviewForm");
                    var action = form.getAttribute("action");
                    var title = document.getElementById("reviewTitle");
                    var pluginId = document.getElementById("reviewPluginId");
                    var pluginName = document.getElementById("reviewPluginName");
                    var comment = document.getElementById("reviewComment");
                    var ratings = form.querySelectorAll('input[name="rating"]');

                    function pointAt(id) {
                        form.setAttribute("action", action.replace("__plugin__", id));
                    }

                    function show() {
                        panel.hidden = false;
                        panel.scrollIntoView({ behavior: "smooth", block: "start" });
                    }

                    function open(button) {
                        pluginId.value = button.dataset.plugin;
                        pluginName.value = button.dataset.pluginName;
                        pointAt(pluginId.value);
                        title.textContent =
                            (button.dataset.rating ? "Edit your review of " : "Write a review for ") +
                            button.dataset.pluginName;

                        ratings.forEach(function (input) {
                            input.checked = input.value === button.dataset.rating;
                        });
                        comment.value = button.dataset.comment || "";

                        show();
                    }

                    document.getElementById("content").addEventListener("click", function (event) {
                        var button = event.target.closest("[data-review]");

                        if (button) open(button);
                    });

                    document.getElementById("reviewCancel").addEventListener("click", function () {
                        panel.hidden = true;
                    });

                    // A failed submit comes back with the old input: the fields
                    // are already filled in, so only the panel has to reopen.
                    if (pluginId.value) {
                        pointAt(pluginId.value);
                        title.textContent = "Write a review for " + (pluginName.value || "this plugin");
                        show();
                    }
                })();
            </script>
        @endpush
    </x-app-layout>
