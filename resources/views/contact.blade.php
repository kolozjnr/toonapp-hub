<x-guest-layout>
        <main class="wrap">
            <div class="contact-head">
                <a class="btn sm white" href="index.html"
                    ><svg
                        fill="none"
                        height="16"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="3"
                        viewBox="0 0 24 24"
                        width="16"
                    >
                        <path d="M15 5l-7 7 7 7"></path></svg
                    >Back to shop</a
                >
                <h1>Contact us</h1>
                <p>
                    Questions about a plugin, a license or a download? Send a message and we'll reply by email, usually
                    within a day.
                </p>
            </div>
            <section class="contact" id="contact">
                <div class="contact-info">
                    <h2 class="fs-28">Reach us directly</h2>
                    <div class="ccards" id="contactCards">
                        <div class="ccard">
                            <span class="ic bg-yellow"
                                ><svg
                                    fill="none"
                                    height="20"
                                    stroke="currentColor"
                                    stroke-linejoin="round"
                                    stroke-width="2.6"
                                    viewBox="0 0 24 24"
                                    width="20"
                                >
                                    <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                    <path d="m4 7 8 6 8-6"></path></svg></span
                            ><span class="tx"><small>Email</small><span class="v">support@tooabihub.com</span></span
                            ><button
                                class="btn xs white"
                                data-copy="support@tooabihub.com"
                                data-copy-msg="Email copied"
                                type="button"
                            >
                                Copy
                            </button>
                        </div>
                        <a class="ccard" href="https://wa.me/2348012345678" rel="noopener noreferrer" target="_blank"
                            ><span class="ic bg-mint"
                                ><svg
                                    fill="none"
                                    height="20"
                                    stroke="currentColor"
                                    stroke-linejoin="round"
                                    stroke-width="2.6"
                                    viewBox="0 0 24 24"
                                    width="20"
                                >
                                    <path d="M4 20l1.4-4.2A8 8 0 1 1 8.4 18.7z"></path>
                                    <path
                                        d="M9 9.5c.5 2.5 2.5 4.5 5 5l1.2-1.3 2 1-.5 1.6c-4 .4-8.4-4-8-8l1.6-.5 1 2z"
                                        stroke-width="1.6"
                                    ></path></svg></span
                            ><span class="tx"><small>WhatsApp</small><span class="v">+234 801 234 5678</span></span></a
                        ><a
                            class="ccard"
                            href="https://instagram.com/toonapphub"
                            rel="noopener noreferrer"
                            target="_blank"
                            ><span class="ic bg-pink"
                                ><svg
                                    fill="none"
                                    height="20"
                                    stroke="currentColor"
                                    stroke-width="2.6"
                                    viewBox="0 0 24 24"
                                    width="20"
                                >
                                    <rect height="17" rx="5" width="17" x="3.5" y="3.5"></rect>
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <circle cx="17.3" cy="6.7" fill="currentColor" r="1"></circle></svg></span
                            ><span class="tx"
                                ><small>Instagram</small><span class="v">instagram.com/toonapphub</span></span
                            ></a
                        ><a
                            class="ccard"
                            href="https://youtube.com/@toonapphub"
                            rel="noopener noreferrer"
                            target="_blank"
                            ><span class="ic bg-red"
                                ><svg
                                    fill="none"
                                    height="20"
                                    stroke="currentColor"
                                    stroke-linejoin="round"
                                    stroke-width="2.6"
                                    viewBox="0 0 24 24"
                                    width="20"
                                >
                                    <rect height="13" rx="4" width="19" x="2.5" y="5.5"></rect>
                                    <path d="M10 9.5v5l4.5-2.5z" fill="currentColor"></path></svg></span
                            ><span class="tx"
                                ><small>YouTube</small><span class="v">Watch all our tutorials</span></span
                            ></a
                        >
                        <div class="ccard">
                            <span class="ic bg-soft"
                                ><svg
                                    fill="none"
                                    height="20"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-width="2.6"
                                    viewBox="0 0 24 24"
                                    width="20"
                                >
                                    <circle cx="12" cy="12" r="8.5"></circle>
                                    <path d="M12 7.5V12l3 2"></path></svg></span
                            ><span class="tx"
                                ><small>Support hours</small><span class="v">Mon–Fri, 9am–6pm (WAT)</span></span
                            >
                        </div>
                    </div>
                </div>
                <form action="contact.html" class="panel2 form" id="contactForm" method="post" novalidate="">
                    <div class="two">
                        <div class="field">
                            <label for="cName">Name</label
                            ><input autocomplete="name" id="cName" maxlength="80" name="name" />
                        </div>
                        <div class="field">
                            <label for="cEmail">Email</label
                            ><input
                                autocomplete="email"
                                id="cEmail"
                                maxlength="120"
                                name="email"
                                placeholder="you@example.com"
                                type="email"
                            />
                        </div>
                    </div>
                    <div class="field">
                        <label for="cTopic">Topic</label
                        ><select id="cTopic" name="topic">
                            <option>Question about a plugin</option>
                            <option>Help with a purchase or download</option>
                            <option>Feature request</option>
                            <option>Something else</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="cMsg">Message</label
                        ><textarea
                            class="min-h-140"
                            id="cMsg"
                            maxlength="2000"
                            name="message"
                            placeholder="How can we help?"
                        ></textarea>
                    </div>
                    <span class="err" hidden="" id="cErr"></span>
                    <button class="btn pink" id="cSend">Send message</button>
                </form>
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
            /* Contact page: check the message form before it is sent. */
            (function () {
                "use strict";
                var form = document.getElementById("contactForm");
                var err = document.getElementById("cErr");
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var name = form.elements.name.value.trim();
                    var email = form.elements.email.value.trim();
                    var text = form.elements.message.value.trim();
                    Toon.showErr(err, "");
                    if (!name) return Toon.showErr(err, "Add your name.");
                    if (!Toon.emailOk(email)) return Toon.showErr(err, "Add a valid email so we can reply.");
                    if (text.length < 5) return Toon.showErr(err, "Write your message.");
                    // DEMO: Laravel will store the message and email the admin when this form posts.
                    form.elements.message.value = "";
                    Toon.toast("Message sent! We'll reply by email.");
                });
            })();
        </script>
