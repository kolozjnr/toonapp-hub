
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
                        <h1>Edit plugin</h1>
                        <div class="sub">Squash &amp; Stretch Pro</div>
                    </div>
                    <div class="top-right">
                        <a class="btn ghost sm store-link" href="../index.html"
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
                    <form
                        action="plugins.html"
                        class="form-layout"
                        enctype="multipart/form-data"
                        id="pf"
                        method="post"
                        novalidate=""
                    >
                        <div class="form-main">
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Basics</h2>
                                        <p>What customers see first.</p>
                                    </div>
                                </div>
                                <div class="form">
                                    <div class="two">
                                        <div class="field">
                                            <label for="fName">Plugin name</label
                                            ><input
                                                id="fName"
                                                maxlength="60"
                                                name="name"
                                                placeholder="e.g. Squash Stretch Pro"
                                                value="Squash &amp; Stretch Pro"
                                            /><span class="ferr" data-for="fName"></span>
                                        </div>
                                        <div class="field">
                                            <label for="fTag">One-line pitch</label
                                            ><input
                                                id="fTag"
                                                maxlength="90"
                                                name="tagline"
                                                placeholder="What it does in one sentence"
                                                value="Give any layer a bouncy, cartoony squash and stretch in one click."
                                            />
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label for="fDesc">Description</label
                                        ><textarea
                                            id="fDesc"
                                            maxlength="2000"
                                            name="description"
                                            placeholder="What it does, who it's for and how it works."
                                        >
Squash &amp; Stretch Pro adds classic cartoon squash and stretch to any layer. It keeps volume, follows your motion and gives you simple sliders for bounce, delay and overshoot, so your animation feels alive in seconds.</textarea
                                        >
                                    </div>
                                    <div class="field">
                                        <label for="fFeat">Key features</label
                                        ><textarea
                                            class="min-h-90"
                                            id="fFeat"
                                            maxlength="800"
                                            name="features"
                                            placeholder="One feature per line"
                                        >
One-click squash and stretch on any layer
Keeps the layer's volume as it deforms
Bounce, delay and overshoot sliders
Presets for balls, characters and text
Works with 2D and 3D layers</textarea
                                        >
                                    </div>
                                    <div class="field">
                                        <label for="fYt">YouTube tutorial link</label
                                        ><input
                                            id="fYt"
                                            name="youtube_url"
                                            placeholder="https://www.youtube.com/watch?v=..."
                                            type="url"
                                            value="https://www.youtube.com/@toonapphub"
                                        /><small>Customers see a "Watch tutorial" button on the plugin page.</small
                                        ><span class="ferr" data-for="fYt"></span>
                                    </div>
                                </div>
                            </section>
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Cover image</h2>
                                        <p>
                                            Shown on the shelf and at the top of the plugin page. Landscape works best.
                                        </p>
                                    </div>
                                </div>
                                <div aria-label="Choose cover image" class="drop" id="drop" role="button" tabindex="0">
                                    <span hidden="" id="dropTxt"
                                        >Drop an image here or click to choose<br /><small>PNG or JPG</small></span
                                    ><img alt="Cover preview" src="../assets/images/plugins/squash-stretch-pro.png" />
                                </div>
                                <div class="savebar mt-10">
                                    <button class="link" id="coverRm" type="button">Remove cover</button>
                                </div>
                                <input
                                    accept="image/png,image/jpeg,image/webp,image/gif"
                                    hidden=""
                                    id="coverFile"
                                    name="cover"
                                    type="file"
                                />
                            </section>
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Extra images <span class="badge plain">Optional, up to 4</span></h2>
                                        <p>
                                            Show the plugin in action. Each image gets its own title and explanation on
                                            the plugin page.
                                        </p>
                                    </div>
                                </div>
                                <div class="shot-slots" id="slots">
                                    <div class="slot">
                                        <input
                                            accept="image/png,image/jpeg,image/webp,image/gif"
                                            data-slot-file="0"
                                            hidden=""
                                            id="shotFile0"
                                            name="images[0]"
                                            type="file"
                                        />
                                        <div
                                            aria-label="Choose image 1"
                                            class="drop"
                                            data-slot="0"
                                            role="button"
                                            tabindex="0"
                                        >
                                            <span hidden="">+ Image 1<br /><small>Click or drop</small></span
                                            ><img
                                                alt="Image 1 preview"
                                                src="../assets/images/plugins/squash-stretch-pro-1.png"
                                            />
                                        </div>
                                        <input
                                            aria-label="Image 1 title"
                                            id="sTitle0"
                                            maxlength="60"
                                            name="image_titles[0]"
                                            placeholder="Title, e.g. One-click setup"
                                            value="One-click setup"
                                        />
                                        <textarea
                                            aria-label="Image 1 details"
                                            id="sText0"
                                            maxlength="600"
                                            name="image_texts[0]"
                                            placeholder="Explain what this image shows"
                                        >
Select a layer and press Apply. Squash &amp; Stretch Pro adds the controls and keyframes for you.</textarea
                                        >
                                        <button class="link self-start" data-clearslot="0" type="button">
                                            Remove image
                                        </button>
                                    </div>
                                    <div class="slot">
                                        <input
                                            accept="image/png,image/jpeg,image/webp,image/gif"
                                            data-slot-file="1"
                                            hidden=""
                                            id="shotFile1"
                                            name="images[1]"
                                            type="file"
                                        />
                                        <div
                                            aria-label="Choose image 2"
                                            class="drop"
                                            data-slot="1"
                                            role="button"
                                            tabindex="0"
                                        >
                                            <span hidden="">+ Image 2<br /><small>Click or drop</small></span
                                            ><img
                                                alt="Image 2 preview"
                                                src="../assets/images/plugins/squash-stretch-pro-2.png"
                                            />
                                        </div>
                                        <input
                                            aria-label="Image 2 title"
                                            id="sTitle1"
                                            maxlength="60"
                                            name="image_titles[1]"
                                            placeholder="Title, e.g. One-click setup"
                                            value="Bounce and overshoot sliders"
                                        />
                                        <textarea
                                            aria-label="Image 2 details"
                                            id="sText1"
                                            maxlength="600"
                                            name="image_texts[1]"
                                            placeholder="Explain what this image shows"
                                        >
Dial in how far the layer squashes on impact and how much it overshoots on the way back.</textarea
                                        >
                                        <button class="link self-start" data-clearslot="1" type="button">
                                            Remove image
                                        </button>
                                    </div>
                                    <div class="slot">
                                        <input
                                            accept="image/png,image/jpeg,image/webp,image/gif"
                                            data-slot-file="2"
                                            hidden=""
                                            id="shotFile2"
                                            name="images[2]"
                                            type="file"
                                        />
                                        <div
                                            aria-label="Choose image 3"
                                            class="drop"
                                            data-slot="2"
                                            role="button"
                                            tabindex="0"
                                        >
                                            <span hidden="">+ Image 3<br /><small>Click or drop</small></span
                                            ><img
                                                alt="Image 3 preview"
                                                src="../assets/images/plugins/squash-stretch-pro-3.png"
                                            />
                                        </div>
                                        <input
                                            aria-label="Image 3 title"
                                            id="sTitle2"
                                            maxlength="60"
                                            name="image_titles[2]"
                                            placeholder="Title, e.g. One-click setup"
                                            value="Character presets"
                                        />
                                        <textarea
                                            aria-label="Image 3 details"
                                            id="sText2"
                                            maxlength="600"
                                            name="image_texts[2]"
                                            placeholder="Explain what this image shows"
                                        >
Start from presets made for balls, characters and text, then tweak them to taste.</textarea
                                        >
                                        <button class="link self-start" data-clearslot="2" type="button">
                                            Remove image
                                        </button>
                                    </div>
                                    <div class="slot">
                                        <input
                                            accept="image/png,image/jpeg,image/webp,image/gif"
                                            data-slot-file="3"
                                            hidden=""
                                            id="shotFile3"
                                            name="images[3]"
                                            type="file"
                                        />
                                        <div
                                            aria-label="Choose image 4"
                                            class="drop"
                                            data-slot="3"
                                            role="button"
                                            tabindex="0"
                                        >
                                            <span hidden="">+ Image 4<br /><small>Click or drop</small></span
                                            ><img
                                                alt="Image 4 preview"
                                                src="../assets/images/plugins/squash-stretch-pro-4.png"
                                            />
                                        </div>
                                        <input
                                            aria-label="Image 4 title"
                                            id="sTitle3"
                                            maxlength="60"
                                            name="image_titles[3]"
                                            placeholder="Title, e.g. One-click setup"
                                            value="Volume stays the same"
                                        />
                                        <textarea
                                            aria-label="Image 4 details"
                                            id="sText3"
                                            maxlength="600"
                                            name="image_texts[3]"
                                            placeholder="Explain what this image shows"
                                        >
The layer keeps its volume as it squashes, so the motion reads as cartoony instead of stretched.</textarea
                                        >
                                        <button class="link self-start" data-clearslot="3" type="button">
                                            Remove image
                                        </button>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <aside class="form-side">
                            <section class="panel">
                                <div class="panel-h">
                                    <div><h2>Publish</h2></div>
                                </div>
                                <div class="form">
                                    <label class="toggle"
                                        ><input checked="" id="fLive" name="is_live" type="checkbox" value="1" /> Show
                                        in the store</label
                                    >
                                    <span class="err" hidden="" id="pErr"></span>
                                    <button class="btn" id="saveBtn">Save changes</button>
                                    <a class="btn ghost" href="../plugin.html">View in store</a
                                    ><button class="btn danger ghost" id="delBtn" type="button">Delete plugin</button>
                                </div>
                            </section>
                            <section class="panel">
                                <div class="panel-h">
                                    <div><h2>Pricing</h2></div>
                                </div>
                                <div class="form">
                                    <div class="field">
                                        <label for="fPrice">Price (USD)</label
                                        ><input
                                            id="fPrice"
                                            min="0"
                                            name="price"
                                            placeholder="0 for free"
                                            step="0.01"
                                            type="number"
                                            value="29"
                                        /><span class="ferr" data-for="fPrice"></span>
                                    </div>
                                    <div class="field">
                                        <label for="fWas">Old price <small>(optional, shows a sale)</small></label
                                        ><input
                                            id="fWas"
                                            min="0"
                                            name="old_price"
                                            placeholder="Leave empty if no sale"
                                            step="0.01"
                                            type="number"
                                            value="39"
                                        />
                                    </div>
                                </div>
                            </section>
                            <section class="panel">
                                <div class="panel-h">
                                    <div><h2>Details</h2></div>
                                </div>
                                <div class="form">
                                    <div class="field">
                                        <label for="fCat">Category</label
                                        ><select id="fCat" name="category">
                                            <option selected="">Plugin</option>
                                            <option>Script</option>
                                            <option>Preset pack</option>
                                            <option>Template</option>
                                            <option>Extension</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label for="fBadge">Badge</label
                                        ><select id="fBadge" name="badge">
                                            <option value="">None</option>
                                            <option value="New">New</option>
                                            <option value="Bestseller">Bestseller</option>
                                            <option selected="" value="Sale">Sale</option>
                                            <option value="Free">Free</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <span class="lab">Works with</span>
                                        <div class="chips">
                                            <label
                                                ><input checked="" name="hosts[]" type="checkbox" value="AE" /> After
                                                Effects</label
                                            ><label
                                                ><input name="hosts[]" type="checkbox" value="PR" /> Premiere Pro</label
                                            ><label
                                                ><input name="hosts[]" type="checkbox" value="DR" /> DaVinci
                                                Resolve</label
                                            ><label
                                                ><input name="hosts[]" type="checkbox" value="C4D" /> Cinema 4D</label
                                            ><label><input name="hosts[]" type="checkbox" value="BL" /> Blender</label
                                            ><label
                                                ><input name="hosts[]" type="checkbox" value="FCP" /> Final Cut
                                                Pro</label
                                            >
                                        </div>
                                        <span class="ferr" data-for="hosts"></span>
                                    </div>
                                    <div class="field">
                                        <label for="fVer">Version</label
                                        ><input
                                            id="fVer"
                                            maxlength="20"
                                            name="version"
                                            placeholder="e.g. 1.0.0"
                                            value="1.4.0"
                                        />
                                    </div>
                                    <div class="field">
                                        <label for="fCompat">Requirements</label
                                        ><input
                                            id="fCompat"
                                            maxlength="60"
                                            name="requirements"
                                            placeholder="e.g. AE 2024+, Win &amp; macOS"
                                            value="After Effects 2023 and newer, Windows and macOS"
                                        />
                                    </div>
                                </div>
                            </section>
                            <section class="panel">
                                <div class="panel-h">
                                    <div>
                                        <h2>Download</h2>
                                        <p>Given to customers after they buy, by email and in their dashboard.</p>
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="fLink">Download link</label
                                    ><input
                                        id="fLink"
                                        name="download_url"
                                        placeholder="Google Drive, Dropbox…"
                                        type="url"
                                        value="https://drive.google.com/file/d/example-squash"
                                    /><span class="ferr" data-for="fLink"></span>
                                </div>
                            </section>
                        </aside>
                    </form>
                </main>
            </div>
        </div>
       
    </x-app-layout>
        <script>
            /* Upload / edit plugin: cover and extra image previews, form checks.
   The files stay in the <input type="file"> fields, so the form can post them. */
            (function () {
                "use strict";
                var form = document.getElementById("pf");
                var f = form.elements;

                /* Show a preview of the chosen image inside a drop zone. */
                function preview(zone, file, alt) {
                    if (!file || !/^image\//.test(file.type)) {
                        Toon.toast("Choose a PNG, JPG or WebP image.");
                        return false;
                    }
                    var img = zone.querySelector("img");
                    if (!img) {
                        img = document.createElement("img");
                        zone.appendChild(img);
                    }
                    img.alt = alt;
                    img.src = URL.createObjectURL(file);
                    var label = zone.querySelector("span");
                    if (label) label.hidden = true;
                    return true;
                }
                function clearPreview(zone) {
                    var img = zone.querySelector("img");
                    if (img) img.remove();
                    var label = zone.querySelector("span");
                    if (label) label.hidden = false;
                }
                /* Click, keyboard and drag-and-drop for a drop zone tied to a file input. */
                function wire(zone, input, onPick) {
                    zone.addEventListener("click", function () {
                        input.click();
                    });
                    zone.addEventListener("keydown", function (e) {
                        if (e.key === "Enter" || e.key === " ") {
                            e.preventDefault();
                            input.click();
                        }
                    });
                    ["dragover", "dragenter"].forEach(function (ev) {
                        zone.addEventListener(ev, function (e) {
                            e.preventDefault();
                            zone.classList.add("over");
                        });
                    });
                    ["dragleave", "drop"].forEach(function (ev) {
                        zone.addEventListener(ev, function (e) {
                            e.preventDefault();
                            zone.classList.remove("over");
                        });
                    });
                    zone.addEventListener("drop", function (e) {
                        if (e.dataTransfer.files.length) {
                            input.files = e.dataTransfer.files;
                            onPick();
                        }
                    });
                    input.addEventListener("change", onPick);
                }

                /* cover */
                var drop = document.getElementById("drop"),
                    coverInput = document.getElementById("coverFile"),
                    coverRm = document.getElementById("coverRm");
                wire(drop, coverInput, function () {
                    if (preview(drop, coverInput.files[0], "Cover preview")) coverRm.hidden = false;
                    else coverInput.value = "";
                });
                coverRm.addEventListener("click", function () {
                    coverInput.value = "";
                    clearPreview(drop);
                    coverRm.hidden = true;
                });

                /* extra images */
                Toon.$$(".slot").forEach(function (slot) {
                    var zone = slot.querySelector("[data-slot]"),
                        input = slot.querySelector("[data-slot-file]"),
                        rm = slot.querySelector("[data-clearslot]");
                    var n = +zone.dataset.slot + 1;
                    wire(zone, input, function () {
                        if (preview(zone, input.files[0], "Image " + n + " preview")) rm.hidden = false;
                        else input.value = "";
                    });
                    rm.addEventListener("click", function () {
                        input.value = "";
                        clearPreview(zone);
                        rm.hidden = true;
                    });
                });

                /* checks before saving */
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    var err = document.getElementById("pErr");
                    Toon.showErr(err, "");
                    var price = parseFloat(f.price.value),
                        yt = f.youtube_url.value.trim(),
                        link = f.download_url.value.trim();
                    var ok = Toon.fieldErr("fName", f.name.value.trim() ? "" : "Give your plugin a name.");
                    ok =
                        Toon.fieldErr(
                            "fPrice",
                            f.price.value === "" || isNaN(price) || price < 0
                                ? "Set a price. Use 0 for a free plugin."
                                : "",
                        ) && ok;
                    ok =
                        Toon.fieldErr("fYt", yt && !Toon.youtubeOk(yt) ? "Use a youtube.com or youtu.be link." : "") &&
                        ok;
                    ok =
                        Toon.fieldErr(
                            "fLink",
                            link && !Toon.linkOk(link) ? "The link should start with https://" : "",
                        ) && ok;
                    var hosts = Toon.$$(".chips input:checked").length;
                    document.querySelector('[data-for="hosts"]').textContent = hosts ? "" : "Pick at least one app.";
                    ok = ok && hosts > 0;
                    Toon.$$(".slot").forEach(function (slot, i) {
                        var hasImg = !!slot.querySelector("[data-slot] img");
                        var hasText =
                            slot.querySelector("input:not([type=file])").value.trim() ||
                            slot.querySelector("textarea").value.trim();
                        if (ok && !hasImg && hasText) {
                            Toon.showErr(
                                err,
                                "Image " + (i + 1) + " has text but no picture. Add an image or clear its text.",
                            );
                            ok = false;
                        }
                    });
                    if (!ok) {
                        if (err.hidden) Toon.showErr(err, "Fix the highlighted fields.");
                        return;
                    }
                    // DEMO: in Laravel this form posts the plugin and its files to your store route.
                    Toon.toast(f.is_live.checked ? "Plugin published to your store" : "Plugin saved as hidden");
                    setTimeout(function () {
                        location.assign(form.getAttribute("action"));
                    }, 700);
                });

                /* edit page only: delete */
                var del = document.getElementById("delBtn");
                if (del)
                    del.addEventListener("click", function () {
                        if (!Toon.armed(del, "Tap again to delete for good")) return;
                        // DEMO: Laravel deletes the plugin.
                        Toon.toast("Plugin deleted");
                        setTimeout(function () {
                            location.assign("plugins.html");
                        }, 500);
                    });
            })();
        </script>
