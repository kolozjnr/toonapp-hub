
    <x-app-layout>
        <div class="app">
            <nav aria-label="Account" class="side" id="sidebar">
                <a class="brand" href="../index.html"
                    ><img alt="" src="../assets/images/logo.png" />ToonApp <b>Hub</b></a
                >
                <span class="area">Customer</span>
                <div class="group">Account</div>
                <a class="nav-i" href="dashboard.html"
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
                        <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"></path></svg
                    >Dashboard</a
                ><a class="nav-i" href="plugins.html"
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
                        <path d="M21 8 12 3 3 8v8l9 5 9-5z"></path>
                        <path d="m3 8 9 5 9-5M12 13v8"></path></svg
                    >My plugins<span class="ct">2</span></a
                ><a class="nav-i" href="orders.html"
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
                        <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                        <path d="M9 8h6M9 12h6"></path></svg
                    >Orders<span class="ct">2</span></a
                ><a aria-current="page" class="nav-i" href="updates.html"
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
                    >Updates</a
                ><a class="nav-i" href="settings.html"
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
                        <circle cx="12" cy="12" r="3"></circle>
                        <path
                            d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 0 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 0 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 0 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 0 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"
                        ></path></svg
                    >Settings</a
                >
                <div class="spacer"></div>
                <a class="nav-i" href="../index.html"
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
                        <path d="M4 9.5 5.5 4h13L20 9.5"></path>
                        <path d="M4 9.5h16v1a3 3 0 0 1-5.3 1.9 3 3 0 0 1-5.4 0A3 3 0 0 1 4 10.5z"></path>
                        <path d="M5 12.5V20h14v-7.5"></path></svg
                    >Back to store</a
                >
                <a class="nav-i out" href="../login.html"
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
                        <path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"></path></svg
                    >Log out</a
                >
            </nav>
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
                        <a class="btn ghost sm store-link" href="../index.html#shop"
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
                                        <a class="btn sm ghost" href="../plugin.html">View plugin</a>
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
                                        <a class="btn sm ghost" href="../plugin.html">View plugin</a>
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
