
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
                        <h1>Newsletter</h1>
                        <div class="sub">Send updates to your customers.</div>
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
                            <span class="av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span
                            ><span class="tx"
                                ><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span
                            >
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <div class="form-layout">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Write an update</h2>
                                    <p>Emails every shopper who asked for plugin news, or only the owners of one plugin.</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.newsletter.store') }}" class="form" id="nlForm" method="post">
                                @csrf
                                <div class="two">
                                    <div class="field">
                                        <label for="nlAud">Send to</label
                                        ><select data-subscribers="{{ $subscriberCount }}" id="nlAud" name="audience">
                                            @foreach ($audiences as $value => $label)
                                                <option @selected(old('audience', \App\Models\Newsletter::AUDIENCE_SUBSCRIBERS) === $value) value="{{ $value }}">
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="nlPlugin">About plugin <small>(required for owners)</small></label
                                        ><select id="nlPlugin" name="plugin_id">
                                            <option value="">No specific plugin</option>
                                            @foreach ($plugins as $plugin)
                                                <option
                                                    data-owners="{{ $ownerCounts[$plugin->id] ?? 0 }}"
                                                    @selected((string) old('plugin_id') === (string) $plugin->id)
                                                    value="{{ $plugin->id }}"
                                                >
                                                    {{ $plugin->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="nlSubject">Subject</label
                                    ><input
                                        id="nlSubject"
                                        maxlength="120"
                                        name="subject"
                                        placeholder="e.g. Squash Stretch 1.2 is out: new presets inside"
                                        required
                                        type="text"
                                        value="{{ old('subject') }}"
                                    />
                                </div>
                                <div class="field">
                                    <label for="nlBody">Message</label
                                    ><textarea
                                        class="min-h-170"
                                        id="nlBody"
                                        maxlength="4000"
                                        name="message"
                                        placeholder="What's new, what's fixed, and why they'll love it."
                                        required
                                    >{{ old('message') }}</textarea>
                                </div>
                                <div class="field">
                                    <label for="nlLink">Download link <small>(optional)</small></label
                                    ><input
                                        id="nlLink"
                                        name="download_url"
                                        placeholder="https://drive.google.com/... link to the updated file"
                                        type="url"
                                        value="{{ old('download_url') }}"
                                    /><small
                                        >Customers get a Download button on this update. To keep it for owners only, send
                                        to the owners of one plugin.</small
                                    >
                                </div>
                                <span class="note" id="nlCount">{{ $countNote }}</span>
                                <div class="savebar">
                                    <button class="btn">
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
                                            <path d="m4 7 8 6 8-6"></path></svg
                                        >Send newsletter
                                    </button>
                                </div>
                            </form>
                        </section>
                        <aside class="form-side">
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Sent updates</h2>
                                        <p>{{ $newsletterCount }} so far</p>
                                    </div>
                                </div>
                                <div class="list">
                                    @forelse ($newsletters as $sent)
                                        <div class="row-i row-top">
                                            <div class="grow">
                                                <b>{{ $sent->subject }}</b
                                                ><small>{{ $sent->created_at->format('d M Y') }} · {{ $sent->audienceLabel() }} · {{ $sent->recipientsLabel() }}{{ $sent->download_url ? ' · has download' : '' }}</small>
                                                <div class="acts align-left mt-8">
                                                    <form method="post" action="{{ route('admin.newsletter.resend', $sent) }}">
                                                        @csrf
                                                        <button class="btn xs ghost" type="submit">Email again</button>
                                                    </form>
                                                    <form
                                                        data-confirm="Delete “{{ $sent->subject }}”? This cannot be undone."
                                                        method="post"
                                                        action="{{ route('admin.newsletter.destroy', $sent) }}"
                                                    >
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn xs danger ghost" type="submit">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="note">Nothing sent yet. Your first update will show up here.</p>
                                    @endforelse
                                </div>
                            </section>
                        </aside>
                    </div>
                </main>
            </div>
        </div>

        @push('scripts')
            <script>
                /* Newsletter: how many subscribers the chosen audience reaches, and the delete confirm. */
                document.addEventListener("submit", function (event) {
                    var form = event.target.closest("form[data-confirm]");

                    if (form && !window.confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });

                (function () {
                    var form = document.getElementById("nlForm");
                    var count = document.getElementById("nlCount");

                    function updateCount() {
                        var audience = form.elements.audience;
                        var plugin = form.elements.plugin_id;

                        if (audience.value === "plugin" && !plugin.value) {
                            count.textContent = "Pick the plugin whose owners should get this.";
                            return;
                        }

                        var n =
                            audience.value === "plugin"
                                ? +plugin.selectedOptions[0].dataset.owners
                                : +audience.dataset.subscribers;

                        count.textContent = n + " subscribed customer" + (n === 1 ? "" : "s") + " will get this.";
                    }

                    form.addEventListener("change", updateCount);
                    updateCount();
                })();
            </script>
        @endpush
    </x-app-layout>
