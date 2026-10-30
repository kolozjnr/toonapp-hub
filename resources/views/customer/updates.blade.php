
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
                            ><span class="tx"><b>Ada Okafor</b><small>ada@example.com</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="panel">
                        <div class="panel-h">
                            <div>
                                <h2 id="updatesCount">3 updates</h2>
                                <p>Remove the ones you don't need.</p>
                            </div>
                            <button class="btn ghost sm" id="clearAll" type="button">Clear all</button>
                        </div>
                        <div class="list">
                            <div class="row-i row-top">
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
                                        <path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path>
                                        <path d="M10 20a2.2 2.2 0 0 0 4 0"></path></svg
                                ></span>
                                <div class="grow">
                                    <b>New: Smear Frames just landed <span class="badge brand">New</span></b
                                    ><small>28 Oct 2026 · Smear Frames</small>
                                    <p>
                                        Our newest script draws cartoon smear frames from your motion path. It's in the
                                        shop now with a step-by-step tutorial.
                                    </p>
                                    <div class="acts align-left mt-10">
                                        <a class="btn sm ghost" href="{{ route('home') }}#shop">View plugin</a>
                                        <a
                                            class="btn sm ghost"
                                            href="https://www.youtube.com/@toonapphub"
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
                                    </div>
                                </div>
                                <button
                                    aria-label="Remove this update"
                                    class="btn xs ghost"
                                    data-dismiss="n2"
                                    type="button"
                                >
                                    Remove
                                </button>
                            </div>
                            <div class="row-i row-top">
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
                                        <path d="M12 4v11m-5-5 5 5 5-5"></path>
                                        <path d="M4 19h16"></path></svg
                                ></span>
                                <div class="grow">
                                    <b>Squash &amp; Stretch Pro 1.4 is out <span class="badge brand">New</span></b
                                    ><small>27 Oct 2026 · Squash &amp; Stretch Pro</small>
                                    <p>
                                        Version 1.4 adds character presets and a new overshoot slider, and fixes a bug
                                        with 3D layers. Download the update below and replace the old version.
                                    </p>
                                    <div class="acts align-left mt-10">
                                        <a
                                            class="btn sm"
                                            href="https://drive.google.com/file/d/example-squash-1-4"
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
                                            >Download update</a
                                        >
                                        <a class="btn sm ghost" href="{{ route('home') }}#shop">View plugin</a>
                                        <a
                                            class="btn sm ghost"
                                            href="https://www.youtube.com/@toonapphub"
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
                                    </div>
                                </div>
                                <button
                                    aria-label="Remove this update"
                                    class="btn xs ghost"
                                    data-dismiss="n1"
                                    type="button"
                                >
                                    Remove
                                </button>
                            </div>
                            <div class="row-i row-top">
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
                                        <path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"></path>
                                        <path d="M10 20a2.2 2.2 0 0 0 4 0"></path></svg
                                ></span>
                                <div class="grow">
                                    <b>Spring sale: 25% off Squash &amp; Stretch Pro </b><small>19 Oct 2026</small>
                                    <p>
                                        For one week only, Squash &amp; Stretch Pro is 25% off. The price drops
                                        automatically at checkout.
                                    </p>
                                    <div class="acts align-left mt-10"></div>
                                </div>
                                <button
                                    aria-label="Remove this update"
                                    class="btn xs ghost"
                                    data-dismiss="n3"
                                    type="button"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </section>
                    <section class="panel" hidden="" id="updatesEmpty">
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
      
    </x-app-layout>
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
    </body>
</html>
