
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
                ><a aria-current="page" class="nav-i" href="plugins.html"
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
                        <h1>My plugins</h1>
                        <div class="sub">Everything you own, ready to download.</div>
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
                        ><span class="note">2 plugins</span>
                    </div>
                    <div class="ptiles" id="tiles">
                        <article class="ptile">
                            <span class="pc"><img alt="" src="../assets/images/plugins/bounce-presets.png" /></span>
                            <div class="pb">
                                <h3>Bounce Presets</h3>
                                <span class="meta">Version 1.0.0 · Bought 26 Oct 2026 · Order TK4M8D</span>
                                <span class="meta">Works with After Effects, Premiere Pro</span>
                                <div class="pa">
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
                                    <a class="btn sm ghost" href="../plugin.html#reviews"
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
                                        >Rate</a
                                    >
                                </div>
                            </div>
                        </article>
                        <article class="ptile">
                            <span class="pc"><img alt="" src="../assets/images/plugins/squash-stretch-pro.png" /></span>
                            <div class="pb">
                                <h3>Squash &amp; Stretch Pro</h3>
                                <span class="meta">Version 1.4.0 · Bought 17 Oct 2026 · Order TK1J3G</span>
                                <span class="meta">Works with After Effects</span>
                                <div class="pa">
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
                                    <a class="btn sm ghost" href="../plugin.html#reviews"
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
                                        >Rate</a
                                    >
                                </div>
                            </div>
                        </article>
                    </div>
                    <p class="note" hidden="" id="noResults">No plugins match your search.</p>
                </main>
            </div>
        </div>
    
    </x-app-layout>
