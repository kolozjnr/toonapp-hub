
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
                        <h1>Customers</h1>
                        <div class="sub">Everyone who has registered.</div>
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
                                    <circle cx="9" cy="8" r="3.5"></circle>
                                    <path d="M2.5 20c.8-3.5 3.4-5.5 6.5-5.5s5.7 2 6.5 5.5"></path>
                                    <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.8c1.9.7 3.1 2.5 3.5 5.2"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Customers</div>
                                <div class="v">6</div>
                                <div class="s">Registered</div>
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
                                    <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                                    <path d="M9 8h6M9 12h6"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Buyers</div>
                                <div class="v">5</div>
                                <div class="s">Placed at least one order</div>
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
                                    <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                    <path d="m4 7 8 6 8-6"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Newsletter</div>
                                <div class="v">5</div>
                                <div class="s">Subscribed</div>
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
                                aria-label="Search customers"
                                data-search="#customerRows"
                                data-search-empty="#noResults"
                                data-search-items="tr"
                                id="q"
                                name="q"
                                placeholder="Search by name, email or country"
                                type="search"
                                value=""
                        /></label>
                        <button
                            class="btn ghost sm ml-auto"
                            data-copy="ada@example.com, tunde@example.com, maria@example.com, liam@example.com, jon@example.com"
                            data-copy-msg="Subscriber emails copied"
                            id="copyAll"
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
                                <rect height="12" rx="2" width="12" x="8" y="8"></rect>
                                <path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"></path></svg
                            >Copy subscriber emails
                        </button>
                    </div>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Newsletter</th>
                                    <th>Joined</th>
                                    <th>Orders</th>
                                    <th>Spent</th>
                                </tr>
                            </thead>
                            <tbody id="customerRows">
                                <tr>
                                    <td><b>Sara Mensah</b><span class="sub">Email confirmed</span></td>
                                    <td>sara@example.com</td>
                                    <td>Ghana</td>
                                    <td><span class="badge">No</span></td>
                                    <td class="num">25 Oct 2026</td>
                                    <td class="num">0</td>
                                    <td class="num">$0.00</td>
                                </tr>
                                <tr>
                                    <td><b>Liam Chen</b><span class="sub">Email confirmed</span></td>
                                    <td>liam@example.com</td>
                                    <td>Canada</td>
                                    <td><span class="badge ok">Yes</span></td>
                                    <td class="num">23 Oct 2026</td>
                                    <td class="num">1</td>
                                    <td class="num">$19.00</td>
                                </tr>
                                <tr>
                                    <td><b>Jon Park</b><span class="sub">Email confirmed</span></td>
                                    <td>jon@example.com</td>
                                    <td>United States</td>
                                    <td><span class="badge ok">Yes</span></td>
                                    <td class="num">20 Oct 2026</td>
                                    <td class="num">1</td>
                                    <td class="num">$15.00</td>
                                </tr>
                                <tr>
                                    <td><b>Maria Garcia</b><span class="sub">Email confirmed</span></td>
                                    <td>maria@example.com</td>
                                    <td>Spain</td>
                                    <td><span class="badge ok">Yes</span></td>
                                    <td class="num">18 Oct 2026</td>
                                    <td class="num">2</td>
                                    <td class="num">$36.00</td>
                                </tr>
                                <tr>
                                    <td><b>Tunde Bello</b><span class="sub">Email confirmed</span></td>
                                    <td>tunde@example.com</td>
                                    <td>Nigeria</td>
                                    <td><span class="badge ok">Yes</span></td>
                                    <td class="num">14 Oct 2026</td>
                                    <td class="num">1</td>
                                    <td class="num">$44.00</td>
                                </tr>
                                <tr>
                                    <td><b>Ada Okafor</b><span class="sub">Email confirmed</span></td>
                                    <td>ada@example.com</td>
                                    <td>Nigeria</td>
                                    <td><span class="badge ok">Yes</span></td>
                                    <td class="num">9 Oct 2026</td>
                                    <td class="num">2</td>
                                    <td class="num">$29.00</td>
                                </tr>
                                <tr data-search-ignore="" hidden="" id="noResults">
                                    <td class="note" colspan="7">No customers match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
    
    </x-app-layout>
