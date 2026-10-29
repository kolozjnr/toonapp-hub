/* Admin: upload a new plugin, or edit one with ?id=. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  const editId = T.param("id"), prev = editId ? T.product(editId) : null;
  if (editId && !prev) { $("#content").innerHTML = `<section class="panel">${T.dash.empty("box", "Plugin not found", "It may have been deleted.", `<a class="btn" href="${url("admin/plugins.html")}">Back to plugins</a>`)}</section>`; return; }
  if (prev) { document.title = `Edit ${prev.name} · ToonApp Hub`; $(".top h1").textContent = "Edit plugin"; const s = $(".top .sub"); if (s) s.textContent = prev.name; }
  const p = prev || { hosts: ["AE"], live: true, category: "Plugin" };
  let cover = p.cover || null;
  const slots = [0, 1, 2, 3].map((i) => { const sh = (p.shots || []).find((x) => x.slot === i); const g = sh ? T.db.get("gallery", sh.key) : null; return { img: g ? g.img : null, had: !!sh, changed: false, title: sh?.title || "", text: sh?.text || "" }; });
  const file = T.db.get("files", editId || "") || {};

  $("#content").innerHTML = `<form class="form-layout" id="pf" novalidate>
    <div style="display:flex;flex-direction:column;gap:20px;min-width:0">
      <section class="panel"><div class="panel-h"><div><h2>Basics</h2><p>What customers see first.</p></div></div>
        <div class="form">
          <div class="two">
            <div class="field"><label for="fName">Plugin name</label><input id="fName" maxlength="60" placeholder="e.g. Squash Stretch Pro" value="${esc(p.name || "")}"><span class="ferr" data-for="fName"></span></div>
            <div class="field"><label for="fTag">One-line pitch</label><input id="fTag" maxlength="90" placeholder="What it does in one sentence" value="${esc(p.tagline || "")}"></div>
          </div>
          <div class="field"><label for="fDesc">Description</label><textarea id="fDesc" maxlength="2000" placeholder="What it does, who it's for and how it works.">${esc(p.description || "")}</textarea></div>
          <div class="field"><label for="fFeat">Key features</label><textarea id="fFeat" maxlength="800" style="min-height:90px" placeholder="One feature per line">${esc((p.features || []).join("\n"))}</textarea></div>
          <div class="field"><label for="fYt">YouTube tutorial link</label><input id="fYt" type="url" placeholder="https://www.youtube.com/watch?v=..." value="${esc(p.youtube || "")}"><small>Customers see a "Watch tutorial" button on the plugin page.</small><span class="ferr" data-for="fYt"></span></div>
        </div></section>
      <section class="panel"><div class="panel-h"><div><h2>Cover image</h2><p>Shown on the shelf and at the top of the plugin page. Landscape works best.</p></div></div>
        <div class="drop" id="drop" tabindex="0" role="button" aria-label="Choose cover image"><span id="dropTxt">Drop an image here or click to choose<br><small>PNG or JPG</small></span></div>
        <div class="savebar" style="margin-top:10px"><button type="button" class="link" id="coverRm" hidden>Remove cover</button></div>
        <input type="file" id="coverFile" accept="image/png,image/jpeg,image/webp,image/gif" hidden></section>
      <section class="panel"><div class="panel-h"><div><h2>Extra images <span class="badge plain">Optional, up to 4</span></h2><p>Show the plugin in action. Each image gets its own title and explanation on the plugin page.</p></div></div>
        <div class="shot-slots" id="slots"></div><input type="file" id="shotFile" accept="image/png,image/jpeg,image/webp,image/gif" hidden></section>
    </div>
    <aside class="form-side">
      <section class="panel"><div class="panel-h"><div><h2>Publish</h2></div></div>
        <div class="form">
          <label class="toggle"><input type="checkbox" id="fLive" ${p.live !== false ? "checked" : ""}> Show in the store</label>
          <span class="err" id="pErr" hidden></span>
          <button class="btn" id="saveBtn">${prev ? "Save changes" : "Publish plugin"}</button>
          ${prev ? `<a class="btn ghost" href="${url("plugin.html?id=" + encodeURIComponent(prev.id))}">View in store</a><button type="button" class="btn danger ghost" id="delBtn">Delete plugin</button>` : ""}
        </div></section>
      <section class="panel"><div class="panel-h"><div><h2>Pricing</h2></div></div>
        <div class="form">
          <div class="field"><label for="fPrice">Price (USD)</label><input id="fPrice" type="number" min="0" step="0.01" placeholder="0 for free" value="${prev ? p.price || 0 : ""}"><span class="ferr" data-for="fPrice"></span></div>
          <div class="field"><label for="fWas">Old price <small>(optional, shows a sale)</small></label><input id="fWas" type="number" min="0" step="0.01" placeholder="Leave empty if no sale" value="${p.was || ""}"></div>
        </div></section>
      <section class="panel"><div class="panel-h"><div><h2>Details</h2></div></div>
        <div class="form">
          <div class="field"><label for="fCat">Category</label><select id="fCat">${["Plugin", "Script", "Preset pack", "Template", "Extension"].map((c) => `<option${c === p.category ? " selected" : ""}>${c}</option>`).join("")}</select></div>
          <div class="field"><label for="fBadge">Badge</label><select id="fBadge">${["", "New", "Bestseller", "Sale", "Free"].map((b) => `<option value="${b}"${b === (p.badge || "") ? " selected" : ""}>${b || "None"}</option>`).join("")}</select></div>
          <div class="field"><span class="lab">Works with</span><div class="chips">${Object.entries(T.HOSTS).map(([k, v]) => `<label><input type="checkbox" value="${k}" ${(p.hosts || []).includes(k) ? "checked" : ""}> ${v[0]}</label>`).join("")}</div><span class="ferr" data-for="hosts"></span></div>
          <div class="field"><label for="fVer">Version</label><input id="fVer" maxlength="20" placeholder="e.g. 1.0.0" value="${esc(p.version || "")}"></div>
          <div class="field"><label for="fCompat">Requirements</label><input id="fCompat" maxlength="60" placeholder="e.g. AE 2024+, Win & macOS" value="${esc(p.compat || "")}"></div>
        </div></section>
      <section class="panel"><div class="panel-h"><div><h2>Download</h2><p>Given to customers after they buy, by email and in their dashboard.</p></div></div>
        <div class="field"><label for="fLink">Download link</label><input id="fLink" type="url" placeholder="Google Drive, Dropbox…" value="${esc(file.url || "")}"><span class="ferr" data-for="fLink"></span></div></section>
    </aside></form>`;

  /* cover */
  function paintCover() { const d = $("#drop"); d.querySelector("img")?.remove(); $("#dropTxt").hidden = !!cover; $("#coverRm").hidden = !cover; if (cover) { const i = new Image(); i.src = cover; i.alt = "Cover preview"; d.appendChild(i); } }
  async function takeCover(f) { try { cover = await T.compressImage(f); paintCover(); } catch (e) { T.toast("Choose a PNG, JPG or WebP image."); } }
  const drop = $("#drop");
  drop.onclick = () => $("#coverFile").click();
  drop.onkeydown = (e) => { if (e.key === "Enter" || e.key === " ") { e.preventDefault(); $("#coverFile").click(); } };
  $("#coverFile").onchange = (e) => { takeCover(e.target.files[0]); e.target.value = ""; };
  ["dragover", "dragenter"].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.add("over"); }));
  ["dragleave", "drop"].forEach((ev) => drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.remove("over"); }));
  drop.addEventListener("drop", (e) => takeCover(e.dataTransfer.files[0]));
  $("#coverRm").onclick = () => { cover = null; paintCover(); };
  paintCover();

  /* extra image slots */
  let target = 0;
  const slotDrop = (s, i) => (s.img ? `<img src="${s.img}" alt="Image ${i + 1} preview">` : `<span>+ Image ${i + 1}<br><small>Click or drop</small></span>`);
  $("#slots").innerHTML = slots.map((s, i) => `<div class="slot">
      <div class="drop" tabindex="0" role="button" data-slot="${i}" aria-label="Choose image ${i + 1}">${slotDrop(s, i)}</div>
      <input id="sTitle${i}" maxlength="60" placeholder="Title, e.g. One-click setup" value="${esc(s.title)}" aria-label="Image ${i + 1} title">
      <textarea id="sText${i}" maxlength="600" placeholder="Explain what this image shows" aria-label="Image ${i + 1} details">${esc(s.text)}</textarea>
      <button type="button" class="link" data-clearslot="${i}" style="align-self:start" ${s.img ? "" : "hidden"}>Remove image</button></div>`).join("");
  const paintSlot = (i) => { const d = $(`[data-slot="${i}"]`); d.innerHTML = slotDrop(slots[i], i); $(`[data-clearslot="${i}"]`).hidden = !slots[i].img; };
  async function takeShot(i, f) { try { slots[i].img = await T.compressImage(f, 140000); slots[i].changed = true; paintSlot(i); } catch (e) { T.toast("Choose a PNG, JPG or WebP image."); } }
  const S = $("#slots");
  S.addEventListener("input", (e) => { const m = e.target.id.match(/^s(Title|Text)(\d)$/); if (m) slots[+m[2]][m[1].toLowerCase()] = e.target.value; });
  S.addEventListener("click", (e) => {
    const c = e.target.closest("[data-clearslot]"); if (c) { const i = +c.dataset.clearslot; slots[i].img = null; slots[i].changed = true; paintSlot(i); return; }
    const d = e.target.closest("[data-slot]"); if (d) { target = +d.dataset.slot; $("#shotFile").click(); }
  });
  S.addEventListener("keydown", (e) => { const d = e.target.closest("[data-slot]"); if (d && (e.key === "Enter" || e.key === " ")) { e.preventDefault(); target = +d.dataset.slot; $("#shotFile").click(); } });
  S.addEventListener("dragover", (e) => { const d = e.target.closest("[data-slot]"); if (d) { e.preventDefault(); d.classList.add("over"); } });
  S.addEventListener("dragleave", (e) => { const d = e.target.closest("[data-slot]"); if (d) d.classList.remove("over"); });
  S.addEventListener("drop", (e) => { const d = e.target.closest("[data-slot]"); if (d) { e.preventDefault(); d.classList.remove("over"); takeShot(+d.dataset.slot, e.dataTransfer.files[0]); } });
  $("#shotFile").onchange = (e) => { takeShot(target, e.target.files[0]); e.target.value = ""; };

  /* save */
  $("#pf").addEventListener("submit", (e) => { e.preventDefault(); save(); });
  function save() {
    const err = $("#pErr"); T.showErr(err, "");
    const name = $("#fName").value.trim(), price = parseFloat($("#fPrice").value), was = parseFloat($("#fWas").value), yt = $("#fYt").value.trim(), link = $("#fLink").value.trim();
    const hosts = T.$$(".chips input:checked").map((i) => i.value);
    let ok = T.fieldErr("fName", name ? "" : "Give your plugin a name.");
    ok = T.fieldErr("fPrice", $("#fPrice").value === "" || isNaN(price) || price < 0 ? "Set a price. Use 0 for a free plugin." : "") && ok;
    ok = T.fieldErr("fYt", yt && !T.ytOk(yt) ? "Use a youtube.com or youtu.be link." : "") && ok;
    ok = T.fieldErr("fLink", link && !T.linkOk(link) ? "The link should start with https://" : "") && ok;
    const hostErr = document.querySelector('[data-for="hosts"]'); hostErr.textContent = hosts.length ? "" : "Pick at least one app."; ok = ok && hosts.length > 0;
    const orphan = slots.findIndex((s) => !s.img && (s.title.trim() || s.text.trim()));
    if (orphan >= 0) { T.showErr(err, `Image ${orphan + 1} has text but no picture. Add an image or clear its text.`); ok = false; }
    if (!ok) { if (!err.textContent) T.showErr(err, "Fix the highlighted fields."); return; }
    const id = editId || T.newId();
    const doc = {
      name, tagline: $("#fTag").value.trim(), description: $("#fDesc").value.trim(), youtube: yt || null,
      features: $("#fFeat").value.split("\n").map((s) => s.trim()).filter(Boolean).slice(0, 12),
      price: Math.round(price * 100) / 100, was: !isNaN(was) && was > price ? Math.round(was * 100) / 100 : null,
      category: $("#fCat").value, badge: $("#fBadge").value, hosts, version: $("#fVer").value.trim(), compat: $("#fCompat").value.trim(),
      live: $("#fLive").checked, cover: cover || null, createdAt: prev?.createdAt || Date.now(), updatedAt: Date.now(),
      shots: slots.map((s, i) => (s.img ? { slot: i, key: `${id}-${i}`, title: s.title.trim().slice(0, 60), text: s.text.trim().slice(0, 600) } : null)).filter(Boolean)
    };
    try {
      slots.forEach((s, i) => { const key = `${id}-${i}`; if (s.img && (s.changed || !s.had)) T.db.set("gallery", key, { pid: id, img: s.img }); else if (!s.img && s.had) T.db.del("gallery", key); });
      T.db.set("products", id, doc);
      if (link) T.db.set("files", id, { url: link }); else T.db.del("files", id);
      T.toast(prev ? "Changes saved" : doc.live ? "Plugin published to your store" : "Plugin saved as hidden");
      setTimeout(() => location.assign(url("admin/plugins.html")), 700);
    } catch (x) { T.showErr(err, x.code === "quota" ? "Your store storage is full. Use smaller images or delete old plugins, then try again." : x.message || "Couldn't save. Try again."); }
  }
  const del = $("#delBtn");
  if (del) del.onclick = () => {
    if (!T.armed(del, "Tap again to delete for good")) return;
    (prev.shots || []).forEach((s) => T.db.del("gallery", s.key)); T.db.del("files", prev.id); T.db.del("products", prev.id);
    T.toast("Plugin deleted"); setTimeout(() => location.assign(url("admin/plugins.html")), 500);
  };
})();
