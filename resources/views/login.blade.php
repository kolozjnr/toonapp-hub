<x-guest-layout>
       
        <main class="auth-view">
            <section class="auth-page">
                <div class="panel">
                    <aside aria-hidden="true" class="auth-side">
                        <span class="ring r1"></span><span class="ring r2"></span><span class="spark">✦</span>
                        <div class="brand">
                            <img alt="" class="auth-logo" height="44" src="assets/images/logo.png" width="44" /><span
                                >ToonApp Hub</span
                            >
                        </div>
                        <h3>Good to see you again!</h3>
                        <ul>
                            <li>
                                <span class="ck"
                                    ><svg
                                        fill="none"
                                        height="12"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="4"
                                        viewBox="0 0 24 24"
                                        width="12"
                                    >
                                        <path d="M5 12.5l4.5 4.5L19 7"></path></svg></span
                                >Your plugins are waiting in your dashboard
                            </li>
                            <li>
                                <span class="ck"
                                    ><svg
                                        fill="none"
                                        height="12"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="4"
                                        viewBox="0 0 24 24"
                                        width="12"
                                    >
                                        <path d="M5 12.5l4.5 4.5L19 7"></path></svg></span
                                >Pick up new updates and downloads
                            </li>
                            <li>
                                <span class="ck"
                                    ><svg
                                        fill="none"
                                        height="12"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="4"
                                        viewBox="0 0 24 24"
                                        width="12"
                                    >
                                        <path d="M5 12.5l4.5 4.5L19 7"></path></svg></span
                                >Check out faster with your saved details
                            </li>
                        </ul>
                    </aside>
                    <div class="auth-main">
                        <div class="auth-top">
                            <div>
                                <h2 id="authTitle">Welcome back</h2>
                                <p class="note" id="authNote">Log in with your email and password.</p>
                            </div>
                            <a aria-label="Back to the shop" class="x" href="index.html"
                                ><svg
                                    fill="none"
                                    height="18"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    viewBox="0 0 24 24"
                                    width="18"
                                >
                                    <path d="M15 5l-7 7 7 7"></path></svg
                            ></a>
                        </div>
                        <form
                            action="customer/dashboard.html"
                            class="mbody form"
                            id="loginForm"
                            method="post"
                            novalidate=""
                        >
                            <div class="field">
                                <label for="lEmail">Email</label
                                ><input
                                    autocomplete="email"
                                    id="lEmail"
                                    name="email"
                                    placeholder="you@example.com"
                                    type="email"
                                /><span class="ferr" data-for="lEmail"></span>
                            </div>
                            <div class="field">
                                <div class="lab-row">
                                    <label for="lPass">Password</label
                                    ><a class="link" href="forgot-password.html" id="forgotLink">Forgot password?</a>
                                </div>
                                <div class="pw">
                                    <input
                                        autocomplete="current-password"
                                        id="lPass"
                                        name="password"
                                        type="password"
                                    /><button data-peek="lPass" type="button">Show</button>
                                </div>
                                <span class="ferr" data-for="lPass"></span>
                            </div>
                            <span class="err" hidden="" id="loginErr"></span>
                            <button class="btn pink" id="loginBtn">Log in</button>
                            <span class="note text-center"
                                >New to ToonApp Hub?
                                <a class="link" href="register.html" id="toRegister">Create an account</a></span
                            >
                        </form>
                    </div>
                </div>
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
            /* Log in: check the fields, then continue. */
            (function () {
                "use strict";
                var form = document.getElementById("loginForm");
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var ok = Toon.fieldErr(
                        "lEmail",
                        Toon.emailOk(form.elements.email.value.trim()) ? "" : "Enter the email you signed up with.",
                    );
                    ok = Toon.fieldErr("lPass", form.elements.password.value ? "" : "Enter your password.") && ok;
                    if (!ok) return;
                    // DEMO: Laravel checks the password and sends customers to their dashboard
                    // and the admin to admin/dashboard.html.
                    location.assign(form.getAttribute("action"));
                });
            })();
        </script>
