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
                        <h1>Dashboard</h1>
                        <div class="sub">Your plugins, orders and updates at a glance.</div>
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
                    <section class="hello">
                        <div class="hello-l">
                            <svg aria-hidden="true" class="toon-peek" viewBox="40 50 290 300">
                                <path
                                    d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                                    fill="#7B5CFF"
                                    stroke="#1B1530"
                                    stroke-width="8"
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
                                    cx="135"
                                    cy="190"
                                    fill="#fff"
                                    rx="30"
                                    ry="34"
                                    stroke="#1B1530"
                                    stroke-width="6"
                                ></ellipse>
                                <ellipse
                                    cx="225"
                                    cy="190"
                                    fill="#fff"
                                    rx="30"
                                    ry="34"
                                    stroke="#1B1530"
                                    stroke-width="6"
                                ></ellipse>
                                <circle cx="140" cy="196" fill="#1B1530" r="13"></circle>
                                <circle cx="230" cy="196" fill="#1B1530" r="13"></circle>
                                <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".85" rx="16" ry="9"></ellipse>
                                <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".85" rx="16" ry="9"></ellipse>
                                <path
                                    d="M150 248 Q180 284 210 248"
                                    fill="#FF5C8A"
                                    stroke="#1B1530"
                                    stroke-linejoin="round"
                                    stroke-width="7"
                                ></path>
                            </svg>
                            <div>
                                <h2><span data-greeting="">Hello</span>, Ada 👋</h2>
                                <p>You own 2 plugins. Download them any time from My plugins.</p>
                            </div>
                        </div>
                        <a class="btn white" href="{{ route('customer.plugins') }}">Go to My plugins</a>
                    </section>
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
                                    <path d="M21 8 12 3 3 8v8l9 5 9-5z"></path>
                                    <path d="m3 8 9 5 9-5M12 13v8"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Plugins owned</div>
                                <div class="v">2</div>
                                <div class="s">Ready to download</div>
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
                                <div class="k">Orders</div>
                                <div class="v">2</div>
                                <div class="s">Last on 26 Oct 2026</div>
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
                                <div class="k">Total spent</div>
                                <div class="v">$29.00</div>
                                <div class="s">All time</div>
                            </div>
                        </div>
                        <div class="kpi">
                            <span class="ic bad"
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
                            <div class="min-w-0">
                                <div class="k">Unread updates</div>
                                <div class="v">2</div>
                                <div class="s">3 in total</div>
                            </div>
                        </div>
                    </section>
                    <div class="grid2">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Recent plugins</h2>
                                    <p>Your latest purchases.</p>
                                </div>
                                <a class="btn ghost sm" href="{{ route('customer.plugins') }}">View all</a>
                            </div>
                            <div class="list" id="recent">
                                <div class="row-i">
                                    <span class="thumb"
                                        ><img alt="" src="../assets/images/plugins/bounce-presets.png"
                                    /></span>
                                    <div class="grow">
                                        <b>Bounce Presets</b><small>v1.0.0 · Bought 26 Oct 2026</small>
                                    </div>
                                    <a
                                        class="btn sm"
                                        href="https://drive.google.com/file/d/example-bounce"
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
                                </div>
                                <div class="row-i">
                                    <span class="thumb"
                                        ><img alt="" src="../assets/images/plugins/squash-stretch-pro.png"
                                    /></span>
                                    <div class="grow">
                                        <b>Squash &amp; Stretch Pro</b><small>v1.4.0 · Bought 17 Oct 2026</small>
                                    </div>
                                    <a
                                        class="btn sm"
                                        href="https://drive.google.com/file/d/example-squash"
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
                                </div>
                            </div>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Latest updates</h2>
                                    <p>News about your plugins.</p>
                                </div>
                                <a class="btn ghost sm" href="{{ route('customer.updates') }}">View all</a>
                            </div>
                            <div class="list">
                                <div class="row-i">
                                    <span class="badge brand">New</span>
                                    <div class="grow">
                                        <b>New: Smear Frames just landed</b
                                        ><small
                                            >Our newest script draws cartoon smear frames from your motion path. It's in
                                            the shop now w…</small
                                        >
                                    </div>
                                </div>
                                <div class="row-i">
                                    <span class="badge brand">New</span>
                                    <div class="grow">
                                        <b>Squash &amp; Stretch Pro 1.4 is out</b
                                        ><small
                                            >Version 1.4 adds character presets and a new overshoot slider, and fixes a
                                            bug with 3D lay…</small
                                        >
                                    </div>
                                </div>
                                <div class="row-i">
                                    <span class="badge plain">19 Oct 2026</span>
                                    <div class="grow">
                                        <b>Spring sale: 25% off Squash &amp; Stretch Pro</b
                                        ><small
                                            >For one week only, Squash &amp; Stretch Pro is 25% off. The price drops
                                            automatically at check…</small
                                        >
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <section class="panel">
                        <div class="panel-h">
                            <div>
                                <h2>Recommended for you</h2>
                                <p>Plugins you don't have yet.</p>
                            </div>
                            <a class="btn ghost sm" href="{{ route('home') }}#shop">Visit the shop</a>
                        </div>
                        <div class="ptiles" id="recs">
                            <article class="ptile">
                                <a class="pc" href="{{ route('home') }}#shop"
                                    ><img alt="" src="../assets/images/plugins/smear-frames.png"
                                /></a>
                                <div class="pb">
                                    <h3>Smear Frames</h3>
                                    <span class="meta">Script · $19</span>
                                    <div class="pa"><a class="btn sm ghost" href="{{ route('home') }}#shop">View plugin</a></div>
                                </div>
                            </article>
                            <article class="ptile">
                                <a class="pc" href="{{ route('home') }}#shop"
                                    ><img alt="" src="../assets/images/plugins/line-boil-fx.png"
                                /></a>
                                <div class="pb">
                                    <h3>Line Boil FX</h3>
                                    <span class="meta">Plugin · $15</span>
                                    <div class="pa"><a class="btn sm ghost" href="{{ route('home') }}#shop">View plugin</a></div>
                                </div>
                            </article>
                            <article class="ptile">
                                <a class="pc" href="{{ route('home') }}#shop"
                                    ><img alt="" src="../assets/images/plugins/toon-shadows.png"
                                /></a>
                                <div class="pb">
                                    <h3>Toon Shadows</h3>
                                    <span class="meta">Plugin · $24</span>
                                    <div class="pa"><a class="btn sm ghost" href="{{ route('home') }}#shop">View plugin</a></div>
                                </div>
                            </article>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </x-app-layout>

  
