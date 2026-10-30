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
                        <h3>Join the creator club</h3>
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
                                >Instant downloads of everything you buy
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
                                >All your plugins saved in one place
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
                                >Rate plugins and get updates first
                            </li>
                        </ul>
                    </aside>
                    <div class="auth-main">
                        <div class="auth-top">
                            <div>
                                <h2 id="authTitle">Create your account</h2>
                                <p class="note" id="authNote">It takes less than a minute.</p>
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
                        <form action="verify-email.html" class="mbody form" id="signupForm" method="post" novalidate="">
                            <div class="two">
                                <div class="field">
                                    <label for="rFirst">First name</label
                                    ><input
                                        autocomplete="given-name"
                                        id="rFirst"
                                        maxlength="40"
                                        name="first_name"
                                    /><span class="ferr" data-for="rFirst"></span>
                                </div>
                                <div class="field">
                                    <label for="rLast">Last name</label
                                    ><input
                                        autocomplete="family-name"
                                        id="rLast"
                                        maxlength="40"
                                        name="last_name"
                                    /><span class="ferr" data-for="rLast"></span>
                                </div>
                            </div>
                            <div class="field">
                                <label for="rEmail">Email</label
                                ><input
                                    autocomplete="email"
                                    id="rEmail"
                                    maxlength="120"
                                    name="email"
                                    placeholder="you@example.com"
                                    type="email"
                                /><span class="ferr" data-for="rEmail"></span>
                            </div>
                            <div class="field">
                                <label for="rPass">Password</label>
                                <div class="pw">
                                    <input
                                        autocomplete="new-password"
                                        id="rPass"
                                        name="password"
                                        placeholder="At least 8 characters, with a number"
                                        type="password"
                                    /><button data-peek="rPass" type="button">Show</button>
                                </div>
                                <span class="ferr" data-for="rPass"></span>
                            </div>
                            <div class="field">
                                <label for="rPass2">Confirm password</label>
                                <div class="pw">
                                    <input
                                        autocomplete="new-password"
                                        id="rPass2"
                                        name="password_confirmation"
                                        type="password"
                                    /><button data-peek="rPass2" type="button">Show</button>
                                </div>
                                <span class="ferr" data-for="rPass2"></span>
                            </div>
                            <div class="field">
                                <label for="rCountry">Country</label
                                ><select id="rCountry" name="country">
                                    <option value="">Choose your country</option>
                                    <option>Afghanistan</option>
                                    <option>Albania</option>
                                    <option>Algeria</option>
                                    <option>Andorra</option>
                                    <option>Angola</option>
                                    <option>Antigua and Barbuda</option>
                                    <option>Argentina</option>
                                    <option>Armenia</option>
                                    <option>Australia</option>
                                    <option>Austria</option>
                                    <option>Azerbaijan</option>
                                    <option>Bahamas</option>
                                    <option>Bahrain</option>
                                    <option>Bangladesh</option>
                                    <option>Barbados</option>
                                    <option>Belarus</option>
                                    <option>Belgium</option>
                                    <option>Belize</option>
                                    <option>Benin</option>
                                    <option>Bhutan</option>
                                    <option>Bolivia</option>
                                    <option>Bosnia and Herzegovina</option>
                                    <option>Botswana</option>
                                    <option>Brazil</option>
                                    <option>Brunei</option>
                                    <option>Bulgaria</option>
                                    <option>Burkina Faso</option>
                                    <option>Burundi</option>
                                    <option>Cabo Verde</option>
                                    <option>Cambodia</option>
                                    <option>Cameroon</option>
                                    <option>Canada</option>
                                    <option>Central African Republic</option>
                                    <option>Chad</option>
                                    <option>Chile</option>
                                    <option>China</option>
                                    <option>Colombia</option>
                                    <option>Comoros</option>
                                    <option>Congo</option>
                                    <option>Costa Rica</option>
                                    <option>Côte d'Ivoire</option>
                                    <option>Croatia</option>
                                    <option>Cuba</option>
                                    <option>Cyprus</option>
                                    <option>Czechia</option>
                                    <option>DR Congo</option>
                                    <option>Denmark</option>
                                    <option>Djibouti</option>
                                    <option>Dominica</option>
                                    <option>Dominican Republic</option>
                                    <option>Ecuador</option>
                                    <option>Egypt</option>
                                    <option>El Salvador</option>
                                    <option>Equatorial Guinea</option>
                                    <option>Eritrea</option>
                                    <option>Estonia</option>
                                    <option>Eswatini</option>
                                    <option>Ethiopia</option>
                                    <option>Fiji</option>
                                    <option>Finland</option>
                                    <option>France</option>
                                    <option>Gabon</option>
                                    <option>Gambia</option>
                                    <option>Georgia</option>
                                    <option>Germany</option>
                                    <option>Ghana</option>
                                    <option>Greece</option>
                                    <option>Grenada</option>
                                    <option>Guatemala</option>
                                    <option>Guinea</option>
                                    <option>Guinea-Bissau</option>
                                    <option>Guyana</option>
                                    <option>Haiti</option>
                                    <option>Honduras</option>
                                    <option>Hungary</option>
                                    <option>Iceland</option>
                                    <option>India</option>
                                    <option>Indonesia</option>
                                    <option>Iran</option>
                                    <option>Iraq</option>
                                    <option>Ireland</option>
                                    <option>Israel</option>
                                    <option>Italy</option>
                                    <option>Jamaica</option>
                                    <option>Japan</option>
                                    <option>Jordan</option>
                                    <option>Kazakhstan</option>
                                    <option>Kenya</option>
                                    <option>Kiribati</option>
                                    <option>Kuwait</option>
                                    <option>Kyrgyzstan</option>
                                    <option>Laos</option>
                                    <option>Latvia</option>
                                    <option>Lebanon</option>
                                    <option>Lesotho</option>
                                    <option>Liberia</option>
                                    <option>Libya</option>
                                    <option>Liechtenstein</option>
                                    <option>Lithuania</option>
                                    <option>Luxembourg</option>
                                    <option>Madagascar</option>
                                    <option>Malawi</option>
                                    <option>Malaysia</option>
                                    <option>Maldives</option>
                                    <option>Mali</option>
                                    <option>Malta</option>
                                    <option>Marshall Islands</option>
                                    <option>Mauritania</option>
                                    <option>Mauritius</option>
                                    <option>Mexico</option>
                                    <option>Micronesia</option>
                                    <option>Moldova</option>
                                    <option>Monaco</option>
                                    <option>Mongolia</option>
                                    <option>Montenegro</option>
                                    <option>Morocco</option>
                                    <option>Mozambique</option>
                                    <option>Myanmar</option>
                                    <option>Namibia</option>
                                    <option>Nauru</option>
                                    <option>Nepal</option>
                                    <option>Netherlands</option>
                                    <option>New Zealand</option>
                                    <option>Nicaragua</option>
                                    <option>Niger</option>
                                    <option>Nigeria</option>
                                    <option>North Korea</option>
                                    <option>North Macedonia</option>
                                    <option>Norway</option>
                                    <option>Oman</option>
                                    <option>Pakistan</option>
                                    <option>Palau</option>
                                    <option>Palestine</option>
                                    <option>Panama</option>
                                    <option>Papua New Guinea</option>
                                    <option>Paraguay</option>
                                    <option>Peru</option>
                                    <option>Philippines</option>
                                    <option>Poland</option>
                                    <option>Portugal</option>
                                    <option>Qatar</option>
                                    <option>Romania</option>
                                    <option>Russia</option>
                                    <option>Rwanda</option>
                                    <option>Saint Kitts and Nevis</option>
                                    <option>Saint Lucia</option>
                                    <option>Saint Vincent and the Grenadines</option>
                                    <option>Samoa</option>
                                    <option>San Marino</option>
                                    <option>São Tomé and Príncipe</option>
                                    <option>Saudi Arabia</option>
                                    <option>Senegal</option>
                                    <option>Serbia</option>
                                    <option>Seychelles</option>
                                    <option>Sierra Leone</option>
                                    <option>Singapore</option>
                                    <option>Slovakia</option>
                                    <option>Slovenia</option>
                                    <option>Solomon Islands</option>
                                    <option>Somalia</option>
                                    <option>South Africa</option>
                                    <option>South Korea</option>
                                    <option>South Sudan</option>
                                    <option>Spain</option>
                                    <option>Sri Lanka</option>
                                    <option>Sudan</option>
                                    <option>Suriname</option>
                                    <option>Sweden</option>
                                    <option>Switzerland</option>
                                    <option>Syria</option>
                                    <option>Taiwan</option>
                                    <option>Tajikistan</option>
                                    <option>Tanzania</option>
                                    <option>Thailand</option>
                                    <option>Timor-Leste</option>
                                    <option>Togo</option>
                                    <option>Tonga</option>
                                    <option>Trinidad and Tobago</option>
                                    <option>Tunisia</option>
                                    <option>Turkey</option>
                                    <option>Turkmenistan</option>
                                    <option>Tuvalu</option>
                                    <option>Uganda</option>
                                    <option>Ukraine</option>
                                    <option>United Arab Emirates</option>
                                    <option>United Kingdom</option>
                                    <option>United States</option>
                                    <option>Uruguay</option>
                                    <option>Uzbekistan</option>
                                    <option>Vanuatu</option>
                                    <option>Vatican City</option>
                                    <option>Venezuela</option>
                                    <option>Vietnam</option>
                                    <option>Yemen</option>
                                    <option>Zambia</option>
                                    <option>Zimbabwe</option></select
                                ><span class="ferr" data-for="rCountry"></span>
                            </div>
                            <label class="optin" for="rNews"
                                ><input checked="" id="rNews" name="newsletter" type="checkbox" value="1" /><span
                                    ><b>Join the ToonApp Hub newsletter</b
                                    ><small
                                        >Get an email when your plugins update and when new plugins drop. Unsubscribe
                                        any time in your dashboard.</small
                                    ></span
                                ></label
                            >
                            <span class="err" hidden="" id="signupErr"></span>
                            <button class="btn pink" id="signupBtn">Create account</button>
                            <span class="note text-center"
                                >Already have an account?
                                <a class="link" href="login.html" id="toLogin">Log in</a></span
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
            /* Sign up: check the fields, then go to the email code step. */
            (function () {
                "use strict";
                var form = document.getElementById("signupForm");
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var f = form.elements,
                        pw = f.password.value;
                    var ok = Toon.fieldErr("rFirst", f.first_name.value.trim() ? "" : "Enter your first name.");
                    ok = Toon.fieldErr("rLast", f.last_name.value.trim() ? "" : "Enter your last name.") && ok;
                    ok =
                        Toon.fieldErr(
                            "rEmail",
                            Toon.emailOk(f.email.value.trim()) ? "" : "Enter a valid email, like you@example.com.",
                        ) && ok;
                    ok = Toon.fieldErr("rPass", Toon.passwordRule(pw)) && ok;
                    ok =
                        Toon.fieldErr(
                            "rPass2",
                            !f.password_confirmation.value
                                ? "Type your password again."
                                : f.password_confirmation.value !== pw
                                  ? "Passwords don't match."
                                  : "",
                        ) && ok;
                    ok = Toon.fieldErr("rCountry", f.country.value ? "" : "Choose your country.") && ok;
                    if (!ok) return;
                    // DEMO: Laravel creates the pending account and emails the 6-digit code.
                    location.assign(form.getAttribute("action"));
                });
            })();
        </script>
