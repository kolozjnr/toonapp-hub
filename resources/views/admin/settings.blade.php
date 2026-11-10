
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
                        <div class="sub">Contact details, email sending, admin login and backups.</div>
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
                            ><span class="tx"><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <div class="grid2">
                        <section class="panel" id="contact">
                            <div class="panel-h">
                                <div>
                                    <h2>Contact details</h2>
                                    <p>Shown on your Contact page. Leave a field empty to hide it.</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.settings.contact') }}" class="form" id="ciForm" method="post" novalidate="">
                                @csrf
                                @method('PUT')
                                <div class="two">
                                    <div class="field">
                                        <label for="ciEmail">Support email</label
                                        ><input
                                            id="ciEmail"
                                            name="support_email"
                                            placeholder="support@tooabihub.com"
                                            type="email"
                                            value="{{ old('support_email', $settings['support_email']) }}"
                                        />
                                    </div>
                                    <div class="field">
                                        <label for="ciWa">WhatsApp number</label
                                        ><input
                                            id="ciWa"
                                            name="whatsapp"
                                            placeholder="+234 801 234 5678"
                                            value="{{ old('whatsapp', $settings['whatsapp']) }}"
                                        />
                                    </div>
                                </div>
                                <div class="two">
                                    <div class="field">
                                        <label for="ciIg">Instagram link</label
                                        ><input
                                            id="ciIg"
                                            name="instagram_url"
                                            placeholder="https://instagram.com/yourname"
                                            type="url"
                                            value="{{ old('instagram_url', $settings['instagram_url']) }}"
                                        />
                                    </div>
                                    <div class="field">
                                        <label for="ciYt">YouTube channel link</label
                                        ><input
                                            id="ciYt"
                                            name="youtube_url"
                                            placeholder="https://youtube.com/@yourchannel"
                                            type="url"
                                            value="{{ old('youtube_url', $settings['youtube_url']) }}"
                                        />
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="ciHours">Support hours</label
                                    ><input
                                        id="ciHours"
                                        maxlength="80"
                                        name="support_hours"
                                        placeholder="Mon–Fri, 9am–6pm (WAT)"
                                        value="{{ old('support_hours', $settings['support_hours']) }}"
                                    />
                                </div>
                                <span class="err" hidden="" id="ciErr"></span>
                                <div class="savebar"><button class="btn">Save contact details</button></div>
                            </form>
                        </section>
                        <section class="panel" id="email">
                            <div class="panel-h">
                                <div>
                                    <h2>
                                        Email sending
                                        <span class="badge {{ $emailReady ? 'ok' : 'warn' }}"
                                            >{{ $emailReady ? 'Set up' : 'Not set up' }}</span
                                        >
                                    </h2>
                                    <p>
                                        Sends sign-up codes, password reset codes and download links, using your free
                                        EmailJS account.
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin.settings.email') }}" class="form" id="emForm" method="post" novalidate="">
                                @csrf
                                @method('PUT')
                                <div class="two">
                                    <div class="field">
                                        <label for="emKey">Public key</label
                                        ><input
                                            id="emKey"
                                            name="emailjs_public_key"
                                            placeholder="Account → General"
                                            value="{{ old('emailjs_public_key', $settings['emailjs_public_key']) }}"
                                        />
                                    </div>
                                    <div class="field">
                                        <label for="emSvc">Service ID</label
                                        ><input
                                            id="emSvc"
                                            name="emailjs_service_id"
                                            placeholder="service_ab12cd"
                                            value="{{ old('emailjs_service_id', $settings['emailjs_service_id']) }}"
                                        />
                                    </div>
                                </div>
                                <div class="two">
                                    <div class="field">
                                        <label for="emVerify">Sign-up code template ID</label
                                        ><input
                                            id="emVerify"
                                            name="template_verify"
                                            placeholder="template_verify"
                                            value="{{ old('template_verify', $settings['template_verify']) }}"
                                        />
                                    </div>
                                    <div class="field">
                                        <label for="emReset">Password reset template ID</label
                                        ><input
                                            id="emReset"
                                            name="template_reset"
                                            placeholder="template_reset"
                                            value="{{ old('template_reset', $settings['template_reset']) }}"
                                        />
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="emPurchase">Purchase / download links template ID</label
                                    ><input
                                        id="emPurchase"
                                        name="template_purchase"
                                        placeholder="template_purchase"
                                        value="{{ old('template_purchase', $settings['template_purchase']) }}"
                                    />
                                </div>
                                <div class="field">
                                    <label for="emTest">Send a test email to</label
                                    ><input
                                        id="emTest"
                                        name="test_email"
                                        placeholder="you@example.com"
                                        type="email"
                                        value="{{ old('test_email', 'support@tooabihub.com') }}"
                                    />
                                </div>
                                <span class="err" hidden="" id="emErr"></span>
                                <div class="savebar">
                                    <button class="btn">Save email settings</button
                                    ><button
                                        class="btn ghost"
                                        id="emTestBtn"
                                        type="submit"
                                        formaction="{{ route('admin.settings.email.test') }}"
                                        formmethod="post"
                                    >
                                        Send test email
                                    </button>
                                </div>
                                <details>
                                    <summary class="note summary-link">How to set this up</summary>
                                    <ol class="steps mt-8">
                                        <li>
                                            Create a free account at emailjs.com and add a Gmail service. Copy the
                                            Service ID.
                                        </li>
                                        <li>Copy your Public Key from Account → General.</li>
                                        <li>
                                            Create three templates (sign-up code, password reset, purchase). Set "To
                                            Email" to @{{to_email}}. The README in your site files has the exact text.
                                        </li>
                                        <li>Paste the IDs here, save, then send a test email.</li>
                                    </ol>
                                </details>
                            </form>
                        </section>
                        <section class="panel" id="admin">
                            <div class="panel-h">
                                <div>
                                    <h2>Admin login</h2>
                                    <p>The email and password you use on the Log in page to reach this dashboard.</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.settings.login') }}" class="form" id="adForm" method="post" novalidate="">
                                @csrf
                                @method('PUT')
                                <div class="field">
                                    <label for="adEmail">Admin email</label
                                    ><input
                                        id="adEmail"
                                        name="email"
                                        type="email"
                                        value="{{ old('email', auth()->user()->email) }}"
                                    /><span class="ferr" data-for="adEmail"></span>
                                </div>
                                <div class="field">
                                    <label for="adCur">Current password</label>
                                    <div class="pw">
                                        <input
                                            autocomplete="current-password"
                                            id="adCur"
                                            name="current_password"
                                            type="password"
                                        /><button data-peek="adCur" type="button">Show</button>
                                    </div>
                                    <span class="ferr" data-for="adCur"></span>
                                </div>
                                <div class="two">
                                    <div class="field">
                                        <label for="adNew">New password</label>
                                        <div class="pw">
                                            <input
                                                autocomplete="new-password"
                                                id="adNew"
                                                name="password"
                                                type="password"
                                            /><button data-peek="adNew" type="button">Show</button>
                                        </div>
                                        <span class="ferr" data-for="adNew"></span>
                                    </div>
                                    <div class="field">
                                        <label for="adNew2">Confirm new password</label>
                                        <div class="pw">
                                            <input
                                                autocomplete="new-password"
                                                id="adNew2"
                                                name="password_confirmation"
                                                type="password"
                                            /><button data-peek="adNew2" type="button">Show</button>
                                        </div>
                                        <span class="ferr" data-for="adNew2"></span>
                                    </div>
                                </div>
                                <div class="savebar">
                                    <button class="btn">
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
                                            <path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"></path></svg
                                        >Update admin login
                                    </button>
                                </div>
                            </form>
                        </section>
                        <section class="panel" id="backup">
                            <div class="panel-h">
                                <div>
                                    <h2>Backup</h2>
                                    <p>
                                        Download a copy of your plugins, customers, orders and settings, or restore one
                                        from a backup file.
                                    </p>
                                </div>
                            </div>
                            <div class="savebar">
                                <a class="btn" href="{{ route('admin.settings.backup') }}" id="bkDl">
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
                                        <path d="M12 4v11m-5-5 5 5 5-5"></path>
                                        <path d="M4 19h16"></path></svg
                                    >Download backup</a
                                ><button class="btn ghost" id="bkUp" type="button">
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
                                        <path d="M12 16V4m-5 5 5-5 5 5"></path>
                                        <path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"></path></svg
                                    >Restore from backup</button
                                ><form
                                    action="{{ route('admin.settings.restore') }}"
                                    enctype="multipart/form-data"
                                    hidden=""
                                    id="bkForm"
                                    method="post"
                                >
                                    @csrf
                                    <input
                                        accept="application/json,.json"
                                        id="bkFile"
                                        name="backup"
                                        type="file"
                                    />
                                </form>
                            </div>
                            <p class="note note-spaced">
                                Restoring replaces the plugins, customers, orders and settings with the ones in the
                                file.
                            </p>
                        </section>
                    </div>
                </main>
            </div>
        </div>
       
        @push('scripts')
            <script>
                /* Admin settings: check each panel before it posts to Laravel. */
                (function () {
                    "use strict";

                    var ci = document.getElementById("ciForm");
                    ci.addEventListener("submit", function (e) {
                        var f = ci.elements,
                            err = document.getElementById("ciErr");
                        Toon.showErr(err, "");
                        if (f.support_email.value.trim() && !Toon.emailOk(f.support_email.value.trim())) {
                            e.preventDefault();
                            return Toon.showErr(err, "That email doesn't look right.");
                        }
                        if (f.whatsapp.value.trim() && f.whatsapp.value.replace(/\D/g, "").length < 8) {
                            e.preventDefault();
                            return Toon.showErr(
                                err,
                                "Add the WhatsApp number with country code, like +234 801 234 5678.",
                            );
                        }
                        if (f.instagram_url.value.trim() && !Toon.linkOk(f.instagram_url.value.trim())) {
                            e.preventDefault();
                            return Toon.showErr(err, "The Instagram link should start with https://");
                        }
                        if (f.youtube_url.value.trim() && !Toon.linkOk(f.youtube_url.value.trim())) {
                            e.preventDefault();
                            return Toon.showErr(err, "The YouTube link should start with https://");
                        }
                    });

                    var em = document.getElementById("emForm");
                    em.addEventListener("submit", function (e) {
                        var err = document.getElementById("emErr");
                        Toon.showErr(err, "");
                        var testing = e.submitter && e.submitter.id === "emTestBtn";
                        if (testing && !Toon.emailOk(em.elements.test_email.value.trim())) {
                            e.preventDefault();
                            Toon.showErr(err, "Enter the email address to send the test to.");
                        }
                    });

                    var ad = document.getElementById("adForm");
                    ad.addEventListener("submit", function (e) {
                        var f = ad.elements,
                            nw = f.password.value;
                        var ok = Toon.fieldErr("adEmail", Toon.emailOk(f.email.value.trim()) ? "" : "Enter a valid email.");
                        ok = Toon.fieldErr("adCur", f.current_password.value ? "" : "Enter your current password.") && ok;
                        ok = Toon.fieldErr("adNew", nw ? Toon.passwordRule(nw) : "") && ok;
                        ok =
                            Toon.fieldErr(
                                "adNew2",
                                nw && f.password_confirmation.value !== nw ? "Passwords don't match." : "",
                            ) && ok;
                        if (!ok) e.preventDefault();
                    });

                    var file = document.getElementById("bkFile");
                    document.getElementById("bkUp").addEventListener("click", function () {
                        file.click();
                    });
                    file.addEventListener("change", function () {
                        var chosen = file.files[0];
                        if (!chosen) return;
                        if (!/\.json$/i.test(chosen.name)) {
                            file.value = "";
                            return Toon.toast("Choose a JSON backup file.");
                        }
                        var sure = window.confirm(
                            "Restoring replaces the plugins, customers and orders in this store with the ones in this file. Continue?",
                        );
                        if (!sure) {
                            file.value = "";
                            return;
                        }
                        document.getElementById("bkForm").submit();
                    });

                    if (location.hash) {
                        var target = document.querySelector(location.hash);
                        if (target)
                            setTimeout(function () {
                                target.scrollIntoView();
                            }, 50);
                    }
                })();
            </script>
        @endpush
    </x-app-layout>
