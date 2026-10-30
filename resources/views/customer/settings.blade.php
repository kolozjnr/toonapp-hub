
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
                        <h1>Settings</h1>
                        <div class="sub">Your profile, password and email preferences.</div>
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
                    <div class="grid2">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Profile</h2>
                                    <p>Your name and country.</p>
                                </div>
                            </div>
                            <form action="settings.html" class="form" id="profForm" method="post" novalidate="">
                                <div class="two">
                                    <div class="field">
                                        <label for="sFirst">First name</label
                                        ><input id="sFirst" maxlength="40" name="first_name" value="Ada" /><span
                                            class="ferr"
                                            data-for="sFirst"
                                        ></span>
                                    </div>
                                    <div class="field">
                                        <label for="sLast">Last name</label
                                        ><input id="sLast" maxlength="40" name="last_name" value="Okafor" /><span
                                            class="ferr"
                                            data-for="sLast"
                                        ></span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="sEmail">Email</label
                                    ><input id="sEmail" name="email" readonly="" value="ada@example.com" /><small
                                        >This is the email you log in with. To use a different one, create a new
                                        account.</small
                                    >
                                </div>
                                <div class="field">
                                    <label for="sCountry">Country</label
                                    ><select id="sCountry" name="country">
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
                                        <option selected="">Nigeria</option>
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
                                        <option>Zimbabwe</option>
                                    </select>
                                </div>
                                <div class="savebar">
                                    <button class="btn">Save profile</button
                                    ><span class="note">Member since 9 Oct 2026</span>
                                </div>
                            </form>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Change password</h2>
                                    <p>Use at least 8 characters with a number.</p>
                                </div>
                            </div>
                            <form action="settings.html" class="form" id="pwForm" method="post" novalidate="">
                                <div class="field">
                                    <label for="pCur">Current password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="current-password"
                                            id="pCur"
                                            name="current_password"
                                            type="password"
                                        /><button data-peek="pCur" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pCur"></span>
                                </div>
                                <div class="field">
                                    <label for="pNew">New password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="new-password"
                                            id="pNew"
                                            name="password"
                                            type="password"
                                        /><button data-peek="pNew" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pNew"></span>
                                </div>
                                <div class="field">
                                    <label for="pNew2">Confirm new password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="new-password"
                                            id="pNew2"
                                            name="password_confirmation"
                                            type="password"
                                        /><button data-peek="pNew2" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="pNew2"></span>
                                </div>
                                <div class="savebar"><button class="btn">Update password</button></div>
                            </form>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Email preferences</h2>
                                    <p>Choose what we send you.</p>
                                </div>
                            </div>
                            <label class="toggle"
                                ><input checked="" id="sNews" name="newsletter" type="checkbox" value="1" /> Newsletter:
                                plugin updates and new releases</label
                            >
                            <p class="note note-spaced">
                                Order receipts and download links are always sent, so you never lose a purchase.
                            </p>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Sign out</h2>
                                    <p>Log out of ToonApp Hub on this device.</p>
                                </div>
                            </div>
                            <a class="btn danger ghost" href="{{ route('login') }}" id="logoutBtn"
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
                                        d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 17l-5-5 5-5M5 12h11"
                                    ></path></svg
                                >Log out</a
                            >
                        </section>
                    </div>
                </main>
            </div>
        </div>
        
    </x-app-layout>
        <script>
            /* Customer settings: profile, password and newsletter preference. */
            (function () {
                "use strict";
                var prof = document.getElementById("profForm");
                prof.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var ok = Toon.fieldErr(
                        "sFirst",
                        prof.elements.first_name.value.trim() ? "" : "Enter your first name.",
                    );
                    ok =
                        Toon.fieldErr("sLast", prof.elements.last_name.value.trim() ? "" : "Enter your last name.") &&
                        ok;
                    if (ok) Toon.toast("Profile saved"); // DEMO: Laravel saves the profile.
                });

                var pw = document.getElementById("pwForm");
                pw.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var f = pw.elements,
                        nw = f.password.value;
                    var ok = Toon.fieldErr("pCur", f.current_password.value ? "" : "Enter your current password.");
                    ok = Toon.fieldErr("pNew", Toon.passwordRule(nw)) && ok;
                    ok =
                        Toon.fieldErr("pNew2", f.password_confirmation.value !== nw ? "Passwords don't match." : "") &&
                        ok;
                    if (!ok) return;
                    pw.reset();
                    Toon.toast("Password updated"); // DEMO: Laravel checks and saves the password.
                });

                document.getElementById("sNews").addEventListener("change", function (e) {
                    Toon.toast(e.target.checked ? "You're subscribed to the newsletter" : "You've unsubscribed");
                });
            })();
        </script>
    </body>
</html>
