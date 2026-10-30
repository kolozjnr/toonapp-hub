<x-guest-layout>

        <main class="wrap">
            <section class="hero">
                <div>
                    <h1>
                        Plugins that make your <span class="hl">animation</span> and
                        <span class="hl">video editing</span> <span class="hl2">pop!</span>
                    </h1>
                    <p>
                        Premium tools for animators and video editors. Discover professionally crafted effects, scripts,
                        and presets designed to streamline your workflow. Learn with step-by-step tutorials, pay once,
                        and download instantly. 😊
                    </p>
                    <div class="cta">
                        <a class="btn" href="#shop">Browse plugins</a><a class="btn white" href="#how">How it works</a>
                    </div>
                    <div class="apps" id="heroApps">
                        <span class="host host-ae" title="After Effects">AE</span
                        ><span class="host host-pr" title="Premiere Pro">PR</span
                        ><span class="host host-dr" title="DaVinci Resolve">DR</span
                        ><span class="host host-c4d" title="Cinema 4D">C4D</span>
                    </div>
                </div>
                <div aria-hidden="true" class="mascot-box" id="heroMascot">
                    <span class="bubble" id="heroBubble">Plug me in!</span
                    ><svg class="toon" id="mascot" viewBox="0 0 360 370">
                        <ellipse class="shadow" cx="180" cy="345" fill="rgba(27,21,48,.22)" rx="92" ry="14"></ellipse>
                        <g class="bob">
                            <path
                                d="M268 250 C 320 250, 330 300, 300 318 S 250 340, 270 360"
                                fill="none"
                                stroke="#1B1530"
                                stroke-linecap="round"
                                stroke-width="6"
                            ></path>
                            <rect
                                fill="#FFD23F"
                                height="40"
                                rx="10"
                                stroke="#1B1530"
                                stroke-width="5"
                                width="44"
                                x="246"
                                y="222"
                            ></rect>
                            <rect fill="#1B1530" height="20" rx="3" width="8" x="254" y="206"></rect>
                            <rect fill="#1B1530" height="20" rx="3" width="8" x="274" y="206"></rect>
                            <path
                                d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                                fill="#7B5CFF"
                                stroke="#1B1530"
                                stroke-width="6"
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
                                class="eye"
                                cx="135"
                                cy="190"
                                fill="#fff"
                                rx="30"
                                ry="34"
                                stroke="#1B1530"
                                stroke-width="5"
                            ></ellipse>
                            <ellipse
                                class="eye"
                                cx="225"
                                cy="190"
                                fill="#fff"
                                rx="30"
                                ry="34"
                                stroke="#1B1530"
                                stroke-width="5"
                            ></ellipse>
                            <g class="laugh-eyes" fill="none" stroke="#1B1530" stroke-linecap="round" stroke-width="8">
                                <path d="M108 198 Q135 168 162 198"></path>
                                <path d="M198 198 Q225 168 252 198"></path>
                            </g>
                            <g class="tears" fill="#7FD3FF" stroke="#1B1530" stroke-width="3">
                                <path class="tear" d="M100 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                                <path class="tear tear-late" d="M260 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                            </g>
                            <circle
                                class="pupil"
                                cx="135"
                                cy="194"
                                data-cx="135"
                                data-cy="194"
                                fill="#1B1530"
                                r="13"
                            ></circle>
                            <circle
                                class="pupil"
                                cx="225"
                                cy="194"
                                data-cx="225"
                                data-cy="194"
                                fill="#1B1530"
                                r="13"
                            ></circle>
                            <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                            <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                            <path
                                class="mouth"
                                d="M150 248 Q180 280 210 248"
                                fill="#FF5C8A"
                                stroke="#1B1530"
                                stroke-linejoin="round"
                                stroke-width="6"
                            ></path>
                            <path
                                d="M250 255 C 268 250, 270 240, 262 236"
                                fill="none"
                                stroke="#1B1530"
                                stroke-linecap="round"
                                stroke-width="6"
                            ></path>
                        </g>
                    </svg>
                </div>
            </section>
            <section id="shop">
                <div class="shop-head">
                    <h2>The plugin shelf</h2>
                    <span class="note" id="countNote">6 of 6 plugins</span>
                </div>
                <div class="tools">
                    <div aria-label="Category" class="chip-row" id="cats" role="group">
                        <button aria-pressed="true" class="chip" data-cat="All" type="button">All</button
                        ><button aria-pressed="false" class="chip" data-cat="Script" type="button">Script</button
                        ><button aria-pressed="false" class="chip" data-cat="Plugin" type="button">Plugin</button
                        ><button aria-pressed="false" class="chip" data-cat="Preset pack" type="button">
                            Preset pack
                        </button>
                    </div>
                    <label class="search"
                        ><svg
                            fill="none"
                            height="18"
                            stroke="currentColor"
                            stroke-width="3"
                            viewBox="0 0 24 24"
                            width="18"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path></svg
                        ><input aria-label="Search plugins" id="q" placeholder="Search plugins" type="search"
                    /></label>
                    <select aria-label="Sort" class="sort" id="sort">
                        <option value="new">Newest</option>
                        <option value="top">Top rated</option>
                        <option value="low">Price: low to high</option>
                        <option value="high">Price: high to low</option>
                    </select>
                </div>
                <div class="grid" id="grid">
                    <article
                        class="card"
                        data-category="Script"
                        data-features="Three smear styles: stretch, multiples and streaks|Follows your motion path automatically|Adjustable length, taper and colour|Works on shapes, text and footage"
                        data-hosts="After Effects"
                        data-id="smear"
                        data-image="assets/images/plugins/smear-frames.png"
                        data-name="Smear Frames"
                        data-order="0"
                        data-price="19"
                        data-product=""
                        data-rating="4.0"
                        data-reviews="1"
                        data-tagline="Hand-drawn smear frames for fast moves, generated from your motion path."
                        data-tutorial="1"
                        data-url="plugin.html"
                    >
                        <span class="sticker">$19</span>
                        <a aria-label="View Smear Frames" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/smear-frames.png" /><span class="flag">New</span
                            ><span class="vidflag">▶ Tutorial</span></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Script</span
                                ><span class="host-list"
                                    ><span class="host host-ae" title="After Effects">AE</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Smear Frames</a></h3>
                            <span class="rate"
                                ><span aria-label="4.0 out of 5" class="stars"
                                    ><i>★</i><i>★</i><i>★</i><i>★</i><i class="off">★</i></span
                                >
                                4.0 (1)</span
                            >
                            <p>Hand-drawn smear frames for fast moves, generated from your motion path.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm in"
                                    data-add="smear"
                                    data-in-label="✓ In cart"
                                    data-out-label="Add to cart"
                                    type="button"
                                >
                                    ✓ In cart
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                    <article
                        class="card"
                        data-category="Plugin"
                        data-features="One-click squash and stretch on any layer|Keeps the layer's volume as it deforms|Bounce, delay and overshoot sliders|Presets for balls, characters and text|Works with 2D and 3D layers"
                        data-hosts="After Effects"
                        data-id="squash"
                        data-image="assets/images/plugins/squash-stretch-pro.png"
                        data-name="Squash &amp; Stretch Pro"
                        data-order="1"
                        data-price="29"
                        data-product=""
                        data-rating="5.0"
                        data-reviews="2"
                        data-tagline="Give any layer a bouncy, cartoony squash and stretch in one click."
                        data-tutorial="1"
                        data-url="plugin.html"
                        data-was="39"
                    >
                        <span class="sticker">$29<s>$39</s></span>
                        <a aria-label="View Squash &amp; Stretch Pro" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/squash-stretch-pro.png" /><span class="flag"
                                >Sale</span
                            ><span class="vidflag">▶ Tutorial</span></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Plugin</span
                                ><span class="host-list"
                                    ><span class="host host-ae" title="After Effects">AE</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Squash &amp; Stretch Pro</a></h3>
                            <span class="rate"
                                ><span aria-label="5.0 out of 5" class="stars"
                                    ><i>★</i><i>★</i><i>★</i><i>★</i><i>★</i></span
                                >
                                5.0 (2)</span
                            >
                            <p>Give any layer a bouncy, cartoony squash and stretch in one click.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm"
                                    data-add="squash"
                                    data-in-label="✓ In cart"
                                    data-out-label="Add to cart"
                                    type="button"
                                >
                                    Add to cart
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                    <article
                        class="card"
                        data-category="Plugin"
                        data-features="Animated line boil on shapes and footage|Boil on 2s or 3s for a hand-drawn feel|Control amount, speed and seed|Light enough for long timelines"
                        data-hosts="After Effects and Blender"
                        data-id="boil"
                        data-image="assets/images/plugins/line-boil-fx.png"
                        data-name="Line Boil FX"
                        data-order="2"
                        data-price="15"
                        data-product=""
                        data-rating="5.0"
                        data-reviews="1"
                        data-tagline="That wobbly hand-drawn line boil, on any shape or footage."
                        data-tutorial="1"
                        data-url="plugin.html"
                    >
                        <span class="sticker">$15</span>
                        <a aria-label="View Line Boil FX" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/line-boil-fx.png" /><span class="flag"
                                >Bestseller</span
                            ><span class="vidflag">▶ Tutorial</span></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Plugin</span
                                ><span class="host-list"
                                    ><span class="host host-ae" title="After Effects">AE</span
                                    ><span class="host host-bl" title="Blender">BL</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Line Boil FX</a></h3>
                            <span class="rate"
                                ><span aria-label="5.0 out of 5" class="stars"
                                    ><i>★</i><i>★</i><i>★</i><i>★</i><i>★</i></span
                                >
                                5.0 (1)</span
                            >
                            <p>That wobbly hand-drawn line boil, on any shape or footage.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm in"
                                    data-add="boil"
                                    data-in-label="✓ In cart"
                                    data-out-label="Add to cart"
                                    type="button"
                                >
                                    ✓ In cart
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                    <article
                        class="card"
                        data-category="Plugin"
                        data-features="Cel shading with up to three bands|Rim light and outline controls|Matches the light in your scene"
                        data-hosts="After Effects and Cinema 4D"
                        data-id="shadows"
                        data-image="assets/images/plugins/toon-shadows.png"
                        data-name="Toon Shadows"
                        data-order="3"
                        data-price="24"
                        data-product=""
                        data-rating="4.0"
                        data-reviews="1"
                        data-tagline="Flat cel shading and rim lights for 3D and 2D layers."
                        data-tutorial="1"
                        data-url="plugin.html"
                    >
                        <span class="sticker">$24</span>
                        <a aria-label="View Toon Shadows" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/toon-shadows.png" /><span class="vidflag"
                                >▶ Tutorial</span
                            ></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Plugin</span
                                ><span class="host-list"
                                    ><span class="host host-ae" title="After Effects">AE</span
                                    ><span class="host host-c4d" title="Cinema 4D">C4D</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Toon Shadows</a></h3>
                            <span class="rate"
                                ><span aria-label="4.0 out of 5" class="stars"
                                    ><i>★</i><i>★</i><i>★</i><i>★</i><i class="off">★</i></span
                                >
                                4.0 (1)</span
                            >
                            <p>Flat cel shading and rim lights for 3D and 2D layers.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm"
                                    data-add="shadows"
                                    data-in-label="✓ In cart"
                                    data-out-label="Add to cart"
                                    type="button"
                                >
                                    Add to cart
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                    <article
                        class="card"
                        data-category="Preset pack"
                        data-features="40 text animation presets|Drag and drop onto any text|Edit words, colours and timing"
                        data-hosts="Premiere Pro and DaVinci Resolve"
                        data-id="wiggle"
                        data-image="assets/images/plugins/wiggle-text-pack.png"
                        data-name="Wiggle Text Pack"
                        data-order="4"
                        data-price="12"
                        data-product=""
                        data-rating="0.0"
                        data-reviews="0"
                        data-tagline="40 playful text animations for titles, captions and lower thirds."
                        data-tutorial="1"
                        data-url="plugin.html"
                    >
                        <span class="sticker">$12</span>
                        <a aria-label="View Wiggle Text Pack" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/wiggle-text-pack.png" /><span class="vidflag"
                                >▶ Tutorial</span
                            ></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Preset pack</span
                                ><span class="host-list"
                                    ><span class="host host-pr" title="Premiere Pro">PR</span
                                    ><span class="host host-dr" title="DaVinci Resolve">DR</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Wiggle Text Pack</a></h3>
                            <span class="rate"
                                ><span aria-label="0.0 out of 5" class="stars"
                                    ><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i
                                    ><i class="off">★</i></span
                                >
                                No ratings yet</span
                            >
                            <p>40 playful text animations for titles, captions and lower thirds.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm"
                                    data-add="wiggle"
                                    data-in-label="✓ In cart"
                                    data-out-label="Add to cart"
                                    type="button"
                                >
                                    Add to cart
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                    <article
                        class="card"
                        data-category="Preset pack"
                        data-features="12 bounce presets|Works on any layer|Free forever"
                        data-hosts="After Effects and Premiere Pro"
                        data-id="bounce"
                        data-image="assets/images/plugins/bounce-presets.png"
                        data-name="Bounce Presets"
                        data-order="5"
                        data-price="0"
                        data-product=""
                        data-rating="0.0"
                        data-reviews="0"
                        data-tagline="Free starter pack of bouncy motion presets."
                        data-tutorial="1"
                        data-url="plugin.html"
                    >
                        <span class="sticker free">Free</span>
                        <a aria-label="View Bounce Presets" class="cover" href="plugin.html"
                            ><img alt="" src="assets/images/plugins/bounce-presets.png" /><span class="flag">Free</span
                            ><span class="vidflag">▶ Tutorial</span></a
                        >
                        <div class="cbody">
                            <div class="cmeta">
                                <span>Preset pack</span
                                ><span class="host-list"
                                    ><span class="host host-ae" title="After Effects">AE</span
                                    ><span class="host host-pr" title="Premiere Pro">PR</span></span
                                >
                            </div>
                            <h3><a class="plain-link" href="plugin.html">Bounce Presets</a></h3>
                            <span class="rate"
                                ><span aria-label="0.0 out of 5" class="stars"
                                    ><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i
                                    ><i class="off">★</i></span
                                >
                                No ratings yet</span
                            >
                            <p>Free starter pack of bouncy motion presets.</p>
                            <div class="cfoot">
                                <button
                                    class="btn sm"
                                    data-add="bounce"
                                    data-in-label="✓ In cart"
                                    data-out-label="Get it free"
                                    type="button"
                                >
                                    Get it free
                                </button>
                                <a class="btn sm white" href="plugin.html">Preview</a>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="empty" hidden="" id="noMatches">
                    <h3>No matches</h3>
                    <p>Try another category or clear the search.</p>
                </div>
            </section>
            <section class="how" id="how">
                <div class="step">
                    <div class="n bg-yellow">1</div>
                    <h3>Watch the tutorial</h3>
                    <p>Every plugin has a video walkthrough on YouTube so you know what you're getting.</p>
                </div>
                <div class="step">
                    <div class="n bg-pink">2</div>
                    <h3>Create an account and pay</h3>
                    <p>Sign up once, then pay by card. No subscription.</p>
                </div>
                <div class="step">
                    <div class="n bg-mint">3</div>
                    <h3>Download and rate</h3>
                    <p>
                        Your download link arrives by email and stays in your dashboard. Leave a rating to help other
                        animators.
                    </p>
                </div>
            </section>
            <section class="faq" id="faq">
                <h2 class="section-title">Questions</h2>
                <details>
                    <summary>Which apps do the plugins work with?</summary>
                    <p>Each plugin page lists its host apps and the versions it supports.</p>
                </details>
                <details>
                    <summary>Do I get updates?</summary>
                    <p>Yes. Updates within the same major version are free and appear in your dashboard and inbox.</p>
                </details>
                <details>
                    <summary>Can I use them on client work?</summary>
                    <p>Yes. Every license covers personal and commercial projects for one person.</p>
                </details>
                <details>
                    <summary>What if it doesn't work for me?</summary>
                    <p>Send us a message from the Contact page and we'll help you get it working.</p>
                </details>
            </section>
        </main>
        <footer class="site-foot">
            <div class="wrap foot-in">
                <span>© 2026 ToonApp Hub</span><span><a href="contact.html">Contact us</a> · Made for animators</span>
            </div>
        </footer>
        <div class="scrim" id="cartScrim"></div>
        <aside aria-label="Cart" class="drawer" id="cartDrawer">
            <div class="dh">
                <h3>Your cart</h3>
                <button aria-label="Close" class="x" data-close="" type="button">×</button>
            </div>
            <div class="dbody" id="cartBody">
                <div class="li" data-cart-item="smear" data-price="19">
                    <a class="th" href="plugin.html"><img alt="" src="assets/images/plugins/smear-frames.png" /></a>
                    <div class="min-w-0">
                        <b>Smear Frames</b><small>Script</small><br /><button
                            class="link"
                            data-rm="smear"
                            type="button"
                        >
                            Remove
                        </button>
                    </div>
                    <span class="p">$19</span>
                </div>
                <div class="li" data-cart-item="boil" data-price="15">
                    <a class="th" href="plugin.html"><img alt="" src="assets/images/plugins/line-boil-fx.png" /></a>
                    <div class="min-w-0">
                        <b>Line Boil FX</b><small>Plugin</small><br /><button class="link" data-rm="boil" type="button">
                            Remove
                        </button>
                    </div>
                    <span class="p">$15</span>
                </div>
                <div class="empty flat" hidden="" id="cartEmpty">
                    <h3>Cart's empty</h3>
                    <p>Add a plugin and it'll wait here for you.</p>
                </div>
            </div>
            <div class="dfoot" id="cartFoot">
                <div class="row total"><span>Total</span><span id="cartTotal">$34</span></div>
                <a class="btn pink" href="login.html" id="toCheckout">Log in to checkout</a>
                <span class="note text-center"
                    >New here? <a class="link" href="register.html">Create an account</a></span
                >
                <span class="note text-center">One-time payment · Instant download</span>
            </div>
            <div class="dfoot" hidden="" id="cartFootEmpty">
                <a class="btn" data-close="" href="index.html#shop">Browse plugins</a>
            </div>
        </aside>
        <template id="cartItemTemplate"
            ><div class="li" data-cart-item="" data-price="">
                <a class="th" href=""><img alt="" src="" /></a>
                <div class="min-w-0">
                    <b></b><small></small><br /><button class="link" data-rm="" type="button">Remove</button>
                </div>
                <span class="p"></span></div
        ></template>
        <div aria-hidden="true" class="buddy off" id="buddy">
            <div class="buddy-bubble hide"></div>
            <svg aria-hidden="true" class="toon" viewBox="0 0 360 370">
                <ellipse class="shadow" cx="180" cy="345" fill="rgba(27,21,48,.22)" rx="92" ry="14"></ellipse>
                <g class="bob">
                    <path
                        d="M268 250 C 320 250, 330 300, 300 318 S 250 340, 270 360"
                        fill="none"
                        stroke="#1B1530"
                        stroke-linecap="round"
                        stroke-width="6"
                    ></path>
                    <rect
                        fill="#FFD23F"
                        height="40"
                        rx="10"
                        stroke="#1B1530"
                        stroke-width="5"
                        width="44"
                        x="246"
                        y="222"
                    ></rect>
                    <rect fill="#1B1530" height="20" rx="3" width="8" x="254" y="206"></rect>
                    <rect fill="#1B1530" height="20" rx="3" width="8" x="274" y="206"></rect>
                    <path
                        d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                        fill="#7B5CFF"
                        stroke="#1B1530"
                        stroke-width="6"
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
                        class="eye"
                        cx="135"
                        cy="190"
                        fill="#fff"
                        rx="30"
                        ry="34"
                        stroke="#1B1530"
                        stroke-width="5"
                    ></ellipse>
                    <ellipse
                        class="eye"
                        cx="225"
                        cy="190"
                        fill="#fff"
                        rx="30"
                        ry="34"
                        stroke="#1B1530"
                        stroke-width="5"
                    ></ellipse>
                    <g class="laugh-eyes" fill="none" stroke="#1B1530" stroke-linecap="round" stroke-width="8">
                        <path d="M108 198 Q135 168 162 198"></path>
                        <path d="M198 198 Q225 168 252 198"></path>
                    </g>
                    <g class="tears" fill="#7FD3FF" stroke="#1B1530" stroke-width="3">
                        <path class="tear" d="M100 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                        <path class="tear tear-late" d="M260 206 q-7 12 0 16 q7 -4 0 -16z"></path>
                    </g>
                    <circle class="pupil" cx="135" cy="194" data-cx="135" data-cy="194" fill="#1B1530" r="13"></circle>
                    <circle class="pupil" cx="225" cy="194" data-cx="225" data-cy="194" fill="#1B1530" r="13"></circle>
                    <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                    <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".8" rx="16" ry="9"></ellipse>
                    <path
                        class="mouth"
                        d="M150 248 Q180 280 210 248"
                        fill="#FF5C8A"
                        stroke="#1B1530"
                        stroke-linejoin="round"
                        stroke-width="6"
                    ></path>
                    <path
                        d="M250 255 C 268 250, 270 240, 262 236"
                        fill="none"
                        stroke="#1B1530"
                        stroke-linecap="round"
                        stroke-width="6"
                    ></path>
                </g>
            </svg>
        </div>
    
        </x-guest-layout>
        <script>
            /* Home page: filter the plugin shelf by category, search and sort.
   The cards are already in the HTML; this only hides, shows and re-orders them. */
            (function () {
                "use strict";
                var grid = document.getElementById("grid");
                var cards = Toon.$$("article.card", grid);
                var state = { cat: "All", q: "", sort: "new" };

                function apply() {
                    var shown = cards.filter(function (card) {
                        var d = card.dataset;
                        var okCat = state.cat === "All" || d.category === state.cat;
                        var okText = !state.q || card.textContent.toLowerCase().indexOf(state.q) !== -1;
                        card.hidden = !(okCat && okText);
                        return !card.hidden;
                    });
                    var sorted = cards.slice().sort(function (a, b) {
                        var A = a.dataset,
                            B = b.dataset;
                        if (state.sort === "top") return B.rating - A.rating || B.reviews - A.reviews;
                        if (state.sort === "low") return A.price - B.price;
                        if (state.sort === "high") return B.price - A.price;
                        return A.order - B.order;
                    });
                    sorted.forEach(function (card) {
                        grid.appendChild(card);
                    });
                    document.getElementById("countNote").textContent =
                        shown.length + " of " + cards.length + " plugin" + (cards.length === 1 ? "" : "s");
                    document.getElementById("noMatches").hidden = shown.length > 0;
                }

                document.getElementById("cats").addEventListener("click", function (e) {
                    var chip = e.target.closest("[data-cat]");
                    if (!chip) return;
                    state.cat = chip.dataset.cat;
                    Toon.$$("#cats [data-cat]").forEach(function (c) {
                        c.setAttribute("aria-pressed", String(c === chip));
                    });
                    apply();
                });
                document.getElementById("q").addEventListener("input", function (e) {
                    state.q = e.target.value.trim().toLowerCase();
                    apply();
                });
                document.getElementById("sort").addEventListener("change", function (e) {
                    state.sort = e.target.value;
                    apply();
                });
            })();
        </script>
   
