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
                ><a aria-current="page" class="nav-i" href="orders.html"
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
                ><a class="nav-i" href="updates.html"
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
                    >Updates<span class="ct hot">2</span></a
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
                        <h1>Orders</h1>
                        <div class="sub">Your purchase history and receipts.</div>
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
                    <section class="kpis">
                        <div class="kpi">
                            <span class="ic"
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
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Orders</div>
                                <div class="v">2</div>
                            </div>
                        </div>
                        <div class="kpi">
                            <span class="ic ok"
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
                                    <rect height="12" rx="2" width="19" x="2.5" y="6"></rect>
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                    <path d="M6 10v4M18 10v4"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Total spent</div>
                                <div class="v">$29.00</div>
                            </div>
                        </div>
                    </section>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Plugins</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="num"><b>TK4M8D</b></td>
                                    <td class="num">26 Oct 2026</td>
                                    <td>Bounce Presets</td>
                                    <td class="num"><b>$0.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-mail="o4" type="button">
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
                                                >Email my links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK1J3G</b></td>
                                    <td class="num">17 Oct 2026</td>
                                    <td>Squash &amp; Stretch Pro</td>
                                    <td class="num"><b>$29.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-mail="o7" type="button">
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
                                                >Email my links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="note">
                        “Email my links” sends the download links for that order to ada@example.com again.
                    </p>
                </main>
            </div>
        </div>

    </x-app-layout>
<script>
            /* Customer orders: re-send the download links for an order. */
            (function () {
                "use strict";
                document.getElementById("content").addEventListener("click", function (e) {
                    var btn = e.target.closest("[data-mail]");
                    if (!btn) return;
                    // DEMO: Laravel emails the download links for this order.
                    Toon.toast("Download links sent to ada@example.com");
                });
            })();
        </script>