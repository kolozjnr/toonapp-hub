
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
                        <h1>Messages</h1>
                        <div class="sub">Messages from the Contact page.</div>
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
                            ><span class="tx"
                                ><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span
                            >
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="panel">
                        <div class="panel-h">
                            <div>
                                <h2>
                                    Inbox
                                    <span class="badge bad" id="inboxBadge" @if ($unreadCount === 0) hidden @endif
                                        >{{ $unreadCount }} new</span
                                    >
                                </h2>
                                <p>Reply by email. Customers see your reply in their inbox.</p>
                            </div>
                            <div class="savebar">
                                <button class="btn sm" data-f="all" type="button">All ({{ $totalCount }})</button
                                ><button class="btn sm ghost" data-f="unread" type="button"
                                    >Unread ({{ $unreadCount }})</button
                                >
                            </div>
                        </div>
                        <div class="list">
                            @forelse ($messages as $message)
                                <div class="row-i row-top" data-message @if ($message->isUnread()) data-unread @endif>
                                    <span aria-hidden="true" class="kpi-dot"
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
                                            <path d="M4 5h16v11H8l-4 4z"></path></svg
                                    ></span>
                                    <div class="grow">
                                        <b
                                            >{{ $message->name }}
                                            <span class="badge bad" data-new-badge @if (! $message->isUnread()) hidden @endif
                                                >New</span
                                            >
                                            <span class="badge plain">{{ $message->topic }}</span></b
                                        ><small
                                            >{{ $message->email }} · {{ $message->created_at->format('d M Y h:i A') }}</small
                                        >
                                        <p>{{ \Illuminate\Support\Str::limit($message->message, 240) }}</p>
                                        <div class="acts align-left mt-10">
                                            <a
                                                class="btn xs"
                                                data-read
                                                href="{{ $message->gmailReplyUrl() }}"
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
                                                    <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                                    <path d="m4 7 8 6 8-6"></path></svg
                                                >Reply in Gmail</a
                                            >
                                            <button class="btn xs ghost" data-copy="{{ $message->email }}" type="button">
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
                                                    <rect height="12" rx="2" width="12" x="8" y="8"></rect>
                                                    <path
                                                        d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"
                                                    ></path></svg
                                                >Copy email
                                            </button>
                                            <form method="post" action="{{ route('admin.messages.read', $message) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input
                                                    type="hidden"
                                                    name="read"
                                                    value="{{ $message->isUnread() ? 1 : 0 }}"
                                                />
                                                <button class="btn xs ghost" data-toggle type="submit">
                                                    {{ $message->isUnread() ? 'Mark read' : 'Mark unread' }}
                                                </button>
                                            </form>
                                            <form
                                                data-confirm="Delete this message from {{ $message->name }}? This cannot be undone."
                                                method="post"
                                                action="{{ route('admin.messages.destroy', $message) }}"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn xs danger ghost" type="submit">Delete</button>
                                            </form>
                                            <form
                                                data-read-form
                                                hidden
                                                method="post"
                                                action="{{ route('admin.messages.read', $message) }}"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="read" value="1" />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="note">No messages yet. Messages from the Contact page will show up here.</p>
                            @endforelse
                        </div>
                    </section>
                </main>
            </div>
        </div>

        @push('scripts')
            <script>
                /* Admin messages: filter all/unread; replying records the message as read. */
                (function () {
                    "use strict";
                    var content = document.getElementById("content");
                    var filter = "all";

                    function rows() {
                        return Toon.$$("[data-message]", content);
                    }

                    function refresh() {
                        var all = rows(),
                            unread = all.filter(function (row) {
                                return row.hasAttribute("data-unread");
                            });
                        all.forEach(function (row) {
                            row.hidden = filter === "unread" && !row.hasAttribute("data-unread");
                            row.querySelector("[data-new-badge]").hidden = !row.hasAttribute("data-unread");
                            row.querySelector("[data-toggle]").textContent = row.hasAttribute("data-unread")
                                ? "Mark read"
                                : "Mark unread";
                        });
                        var badge = document.getElementById("inboxBadge");
                        badge.textContent = unread.length + " new";
                        badge.hidden = unread.length === 0;
                        var allBtn = content.querySelector('[data-f="all"]'),
                            unBtn = content.querySelector('[data-f="unread"]');
                        allBtn.textContent = "All (" + all.length + ")";
                        unBtn.textContent = "Unread (" + unread.length + ")";
                        allBtn.classList.toggle("ghost", filter !== "all");
                        unBtn.classList.toggle("ghost", filter !== "unread");
                    }

                    content.addEventListener("click", function (event) {
                        var filterButton = event.target.closest("[data-f]");
                        if (filterButton) {
                            filter = filterButton.dataset.f;
                            refresh();
                            return;
                        }

                        var reply = event.target.closest("[data-read]");
                        if (reply) {
                            var form = reply.closest("[data-message]").querySelector("[data-read-form]");
                            if (form) form.submit();
                        }
                    });

                    document.addEventListener("submit", function (event) {
                        var form = event.target.closest("form[data-confirm]");

                        if (form && !window.confirm(form.dataset.confirm)) {
                            event.preventDefault();
                        }
                    });

                    refresh();
                })();
            </script>
        @endpush
    </x-app-layout>
