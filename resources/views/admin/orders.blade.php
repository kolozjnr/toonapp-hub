
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
                        <div class="sub">Every purchase in your store.</div>
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
                            ><span class="tx"><b>Store admin</b><small>admin@tooabihub.com</small></span>
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
                                <div class="v">7</div>
                                <div class="s">5 this week</div>
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
                                <div class="k">Revenue</div>
                                <div class="v">$143.00</div>
                                <div class="s">$99.00 this week</div>
                            </div>
                        </div>
                        <div class="kpi">
                            <span class="ic warn"
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
                                <div class="k">Average order</div>
                                <div class="v">$20.43</div>
                            </div>
                        </div>
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
                                aria-label="Search orders"
                                data-search="#orderRows"
                                data-search-empty="#noResults"
                                data-search-items="tr"
                                id="q"
                                name="q"
                                placeholder="Search by order, customer or plugin"
                                type="search"
                                value=""
                        /></label>
                    </div>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Plugins</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="orderRows">
                                <tr>
                                    <td class="num"><b>TK7Q2M</b></td>
                                    <td class="num">29 Oct 2026<span class="sub">05:41 PM</span></td>
                                    <td><b>Liam Chen</b><span class="sub">liam@example.com</span></td>
                                    <td>Smear Frames</td>
                                    <td class="num"><b>$19.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o1" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK6P9A</b></td>
                                    <td class="num">28 Oct 2026<span class="sub">04:41 PM</span></td>
                                    <td><b>Tunde Bello</b><span class="sub">tunde@example.com</span></td>
                                    <td>Squash &amp; Stretch Pro, Line Boil FX</td>
                                    <td class="num"><b>$44.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o2" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK5N4C</b></td>
                                    <td class="num">27 Oct 2026<span class="sub">02:41 PM</span></td>
                                    <td><b>Maria Garcia</b><span class="sub">maria@example.com</span></td>
                                    <td>Wiggle Text Pack</td>
                                    <td class="num"><b>$12.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o3" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK4M8D</b></td>
                                    <td class="num">26 Oct 2026<span class="sub">05:41 PM</span></td>
                                    <td><b>Ada Okafor</b><span class="sub">ada@example.com</span></td>
                                    <td>Bounce Presets</td>
                                    <td class="num"><b>$0.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o4" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK3L1E</b></td>
                                    <td class="num">24 Oct 2026<span class="sub">06:41 PM</span></td>
                                    <td><b>Maria Garcia</b><span class="sub">maria@example.com</span></td>
                                    <td>Toon Shadows</td>
                                    <td class="num"><b>$24.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o5" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK2K7F</b></td>
                                    <td class="num">21 Oct 2026<span class="sub">03:41 PM</span></td>
                                    <td><b>Jon Park</b><span class="sub">jon@example.com</span></td>
                                    <td>Line Boil FX</td>
                                    <td class="num"><b>$15.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o6" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="num"><b>TK1J3G</b></td>
                                    <td class="num">17 Oct 2026<span class="sub">01:41 PM</span></td>
                                    <td><b>Ada Okafor</b><span class="sub">ada@example.com</span></td>
                                    <td>Squash &amp; Stretch Pro</td>
                                    <td class="num"><b>$29.00</b></td>
                                    <td><span class="badge ok">Paid</span></td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs ghost" data-resend="o7" type="button">
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
                                                >Resend links
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr data-search-ignore="" hidden="" id="noResults">
                                    <td class="note" colspan="7">No orders match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
       
    </x-app-layout>
        <script>
            /* Admin orders: re-send download links to the customer. (Search is in ui.js.) */
            (function () {
                "use strict";
                document.getElementById("orderRows").addEventListener("click", function (e) {
                    var btn = e.target.closest("[data-resend]");
                    if (!btn) return;
                    var email = btn.closest("tr").querySelector(".sub");
                    // DEMO: Laravel emails the download links for this order.
                    Toon.toast("Download links sent to " + (email ? email.textContent : "the customer"));
                });
            })();
        </script>
    </body>
</html>
