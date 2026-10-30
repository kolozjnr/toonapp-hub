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
                        <h1>Orders</h1>
                        <div class="sub">Your purchase history and receipts.</div>
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