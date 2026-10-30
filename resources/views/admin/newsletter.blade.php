
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
                        <h1>Newsletter</h1>
                        <div class="sub">Send updates to your customers.</div>
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
                    <section class="callout ok callout-stack" hidden="" id="nlSent">
                        <div>
                            <b>Posted! It's live in your customers' Updates.</b>To also send it by email, open it in
                            Gmail with everyone in BCC, or copy the list into your email app.
                        </div>
                        <div class="code-box" id="nlEmails">ada@example.com, tunde@example.com</div>
                        <div class="savebar">
                            <a
                                class="btn sm"
                                href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;bcc=ada%40example.com%2Ctunde%40example.com&amp;su=Squash%20%26%20Stretch%20Pro%201.4%20is%20out&amp;body=Version%201.4%20adds%20character%20presets%20and%20a%20new%20overshoot%20slider.%0A%0ADownload%20the%20update%3A%20https%3A%2F%2Fdrive.google.com%2Ffile%2Fd%2Fexample-squash-1-4%0A%0ASquash%20%26%20Stretch%20Pro%20v1.4.0%0AWatch%20the%20tutorial%3A%20https%3A%2F%2Fwww.youtube.com%2F%40toonapphub%0AYour%20downloads%20are%20in%20My%20plugins%20on%20ToonApp%20Hub.%0A%0A%E2%80%94%20ToonApp%20Hub%0AYou're%20getting%20this%20because%20you%20asked%20for%20plugin%20updates.%20Reply%20%22unsubscribe%22%20to%20stop."
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
                                    <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                    <path d="m4 7 8 6 8-6"></path></svg
                                >Open in Gmail (2)</a
                            ><button
                                class="btn sm ghost"
                                data-copy="ada@example.com, tunde@example.com"
                                data-copy-msg="Emails copied"
                                id="cpEmails"
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
                                >Copy 2 emails</button
                            ><button class="btn sm ghost" id="cpBody" type="button">
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
                                >Copy message
                            </button>
                        </div>
                    </section>
                    <div class="form-layout">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Write an update</h2>
                                    <p>Shows in each customer's Updates, and you can email it too.</p>
                                </div>
                            </div>
                            <form action="newsletter.html" class="form" id="nlForm" method="post" novalidate="">
                                <div class="two">
                                    <div class="field">
                                        <label for="nlAud">Send to</label
                                        ><select data-subscribers="5" id="nlAud" name="audience">
                                            <option value="all">Everyone subscribed</option>
                                            <option value="buyers">Owners of one plugin</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="nlPlugin">About plugin <small>(optional)</small></label
                                        ><select id="nlPlugin" name="plugin_id">
                                            <option value="">No specific plugin</option>
                                            <option data-owners="1" value="smear">Smear Frames</option>
                                            <option data-owners="2" value="squash">Squash &amp; Stretch Pro</option>
                                            <option data-owners="2" value="boil">Line Boil FX</option>
                                            <option data-owners="1" value="shadows">Toon Shadows</option>
                                            <option data-owners="1" value="wiggle">Wiggle Text Pack</option>
                                            <option data-owners="1" value="bounce">Bounce Presets</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="nlSubject">Subject</label
                                    ><input
                                        id="nlSubject"
                                        maxlength="120"
                                        name="subject"
                                        placeholder="e.g. Squash Stretch 1.2 is out: new presets inside"
                                    />
                                </div>
                                <div class="field">
                                    <label for="nlBody">Message</label
                                    ><textarea
                                        class="min-h-170"
                                        id="nlBody"
                                        maxlength="4000"
                                        name="message"
                                        placeholder="What's new, what's fixed, and why they'll love it."
                                    ></textarea>
                                </div>
                                <div class="field">
                                    <label for="nlLink">Download link <small>(optional)</small></label
                                    ><input
                                        id="nlLink"
                                        name="download_url"
                                        placeholder="https://drive.google.com/... link to the updated file"
                                        type="url"
                                    /><small
                                        >Customers get a Download button on this update. To keep it for buyers only,
                                        choose "Owners of one plugin".</small
                                    >
                                </div>
                                <span class="note" id="nlCount">5 subscribed customers will get this.</span
                                ><span class="err" hidden="" id="nlErr"></span>
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
                                            <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                            <path d="m4 7 8 6 8-6"></path></svg
                                        >Post update
                                    </button>
                                </div>
                            </form>
                        </section>
                        <aside class="form-side">
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Sent updates</h2>
                                        <p id="nlSentCount">3 so far</p>
                                    </div>
                                </div>
                                <div class="list">
                                    <div class="row-i row-top">
                                        <div class="grow">
                                            <b>New: Smear Frames just landed</b
                                            ><small>28 Oct 2026 · Everyone · 5 subscribers</small>
                                            <div class="acts align-left mt-8">
                                                <button class="btn xs ghost" data-resend="n2" type="button">
                                                    Email again</button
                                                ><button class="btn xs danger ghost" data-del="n2" type="button">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-i row-top">
                                        <div class="grow">
                                            <b>Squash &amp; Stretch Pro 1.4 is out</b
                                            ><small
                                                >27 Oct 2026 · Owners of Squash &amp; Stretch Pro · 2 subscribers · has
                                                download</small
                                            >
                                            <div class="acts align-left mt-8">
                                                <button class="btn xs ghost" data-resend="n1" type="button">
                                                    Email again</button
                                                ><button class="btn xs danger ghost" data-del="n1" type="button">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-i row-top">
                                        <div class="grow">
                                            <b>Spring sale: 25% off Squash &amp; Stretch Pro</b
                                            ><small>19 Oct 2026 · Everyone · 5 subscribers</small>
                                            <div class="acts align-left mt-8">
                                                <button class="btn xs ghost" data-resend="n3" type="button">
                                                    Email again</button
                                                ><button class="btn xs danger ghost" data-del="n3" type="button">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </aside>
                    </div>
                </main>
            </div>
        </div>
        
    </x-app-layout>
        <script>
            /* Admin newsletter: recipient count, form checks, "posted" panel, sent list actions. */
            (function () {
                "use strict";
                var form = document.getElementById("nlForm");
                var f = form.elements;
                var err = document.getElementById("nlErr");
                var sent = document.getElementById("nlSent");

                function count() {
                    var aud = f.audience,
                        opt = f.plugin_id.selectedOptions[0];
                    var note = document.getElementById("nlCount");
                    if (aud.value === "buyers" && !f.plugin_id.value) {
                        note.textContent = "Pick the plugin whose owners should get this.";
                        return;
                    }
                    var n = aud.value === "buyers" ? +opt.dataset.owners : +aud.dataset.subscribers;
                    note.textContent = n + " subscribed customer" + (n === 1 ? "" : "s") + " will get this.";
                }
                form.addEventListener("input", count);
                form.addEventListener("change", count);

                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    Toon.showErr(err, "");
                    var link = f.download_url.value.trim();
                    if (!f.subject.value.trim()) return Toon.showErr(err, "Add a subject.");
                    if (!f.message.value.trim()) return Toon.showErr(err, "Write your message.");
                    if (f.audience.value === "buyers" && !f.plugin_id.value)
                        return Toon.showErr(err, "Pick the plugin whose owners should get this.");
                    if (link && !Toon.linkOk(link))
                        return Toon.showErr(err, "The download link should start with https://");
                    // DEMO: Laravel saves the update for customers; the panel below helps you email it.
                    document.getElementById("cpBody").dataset.copy =
                        f.subject.value.trim() +
                        "\n\n" +
                        f.message.value.trim() +
                        (link ? "\n\nDownload the update: " + link : "");
                    document.getElementById("cpBody").dataset.copyMsg = "Message copied";
                    sent.hidden = false;
                    f.subject.value = "";
                    f.message.value = "";
                    f.download_url.value = "";
                    count();
                    Toon.toast("Update posted to customers");
                    scrollTo(0, 0);
                });

                document.querySelector(".form-side").addEventListener("click", function (e) {
                    var again = e.target.closest("[data-resend]");
                    if (again) {
                        sent.hidden = false;
                        scrollTo(0, 0);
                        return;
                    }
                    var del = e.target.closest("[data-del]");
                    if (del && Toon.armed(del)) {
                        // DEMO: Laravel deletes the update.
                        del.closest(".row-i").remove();
                        var n = Toon.$$(".form-side [data-del]").length;
                        document.getElementById("nlSentCount").textContent = n + " so far";
                        Toon.toast("Update deleted");
                    }
                });
                count();
            })();
        </script>
