/* Plugin page: banner, details, tutorial, extra images, reviews, related plugins. */
(function () {
  "use strict";
  const { $, esc, url } = T;
  const id = T.param("id"), main = $("#pp");
  let pick = 0;

  function notFound() {
    main.innerHTML = `<div class="empty" style="margin-top:30px"><h3>Plugin not found</h3><p>It may have been removed or renamed.</p><a class="btn" href="${url("index.html#shop")}">Browse plugins</a></div>`;
  }
  function render() {
    const p = T.product(id);
    if (!p || (p.live === false && !T.isAdmin())) return notFound();
    document.body.dataset.pid = p.id;
    document.title = `${p.name} · ${T.storeName}`;
    const feats = (p.features || []).filter(Boolean), inCart = T.cart().includes(p.id), yt = T.ytOk(p.youtube), H = T.hueOf(p.name || "x");
    const shots = (p.shots || []).filter((s) => s && s.key).sort((a, b) => a.slot - b.slot);
    const buyLabel = T.isAdmin() ? "Edit plugin" : inCart ? "Go to cart" : p.price ? "Add to cart" : "Get it free";
    const priceHtml = `${T.money(p.price)}${p.was > p.price ? `<s>$${p.was}</s>` : ""}`;
    const more = T.liveProducts().filter((x) => x.id !== p.id).slice(0, 3);
    main.innerHTML = `
      <nav class="crumbs"><a class="btn sm white" href="${url("index.html#shop")}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>Back to shop</a>${p.live === false ? `<span class="tagpill">Hidden from customers</span>` : ""}</nav>
      <section class="pp-hero" style="--h:${H}">
        <span class="blob b1" aria-hidden="true"></span><span class="blob b2" aria-hidden="true"></span>
        <span class="spark s1" aria-hidden="true">★</span><span class="spark s2" aria-hidden="true">✦</span><span class="spark s3" aria-hidden="true">★</span>
        <div class="pp-cover" id="pdMedia">${p.badge ? `<span class="flag" style="z-index:1">${esc(p.badge)}</span>` : ""}</div>
        <div class="pp-head">
          <div class="cmeta" style="justify-content:start;gap:8px"><span>${esc(p.category || "Plugin")}</span>${(p.hosts || []).map(T.hostTag).join("")}</div>
          <h1>${esc(p.name)}</h1>
          <span class="rate" id="pdRate"></span>
          ${p.tagline ? `<p class="pp-tag">${esc(p.tagline)}</p>` : ""}
          <div class="pp-cta">
            <span class="pp-price">${priceHtml}</span>
            <button class="btn pink" id="pdBuy">${buyLabel}</button>
            ${yt ? `<a class="btn white" href="${esc(p.youtube)}" target="_blank" rel="noopener noreferrer"><svg width="16" height="16" viewBox="0 0 24 24"><path d="M7 4.5v15l13-7.5z" fill="#E5334B" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>Watch tutorial</a>` : ""}
          </div>
        </div>
      </section>
      <div class="pp-body">
        <div class="pp-main">
          ${p.description ? `<section class="pp-card"><h2>About this plugin</h2><p class="pp-desc">${esc(p.description)}</p></section>` : ""}
          ${feats.length ? `<section class="pp-card"><h2>What you get</h2><div class="fgrid">${feats.map((f) => `<div class="fcard"><span class="fstar" aria-hidden="true">★</span><span>${esc(f)}</span></div>`).join("")}</div></section>` : ""}
          ${yt ? `<a class="yt pp-yt" href="${esc(p.youtube)}" target="_blank" rel="noopener noreferrer"><span class="play"><svg width="22" height="22" viewBox="0 0 24 24"><path d="M7 4.5v15l13-7.5z" fill="#E5334B" stroke="#1B1530" stroke-width="2" stroke-linejoin="round"/></svg></span><span><b>Watch the step-by-step tutorial</b><small>See ${esc(p.name)} in action on YouTube</small></span></a>` : ""}
          ${shots.length ? `<section class="pp-card"><h2>Inside the plugin</h2><div class="shots-list" id="pdShots">${shots.map((s, i) => `<div class="shot ${i % 2 ? "flip" : ""}"><button class="shot-img" data-zoom="${esc(s.key)}" aria-label="Enlarge image: ${esc(s.title || p.name)}">Loading…</button><div class="shot-txt">${s.title ? `<h4>${esc(s.title)}</h4>` : ""}${s.text ? `<p>${esc(s.text)}</p>` : ""}</div></div>`).join("")}</div></section>` : ""}
          <section class="pp-card" id="reviews"><div class="reviews" id="pdReviews"></div></section>
        </div>
        <aside class="pp-side">
          <div class="pp-card buybox">
            <span class="note" style="text-transform:uppercase;letter-spacing:.06em;font-weight:900">One-time price</span>
            <span class="big-price">${priceHtml}</span>
            <button class="btn pink" id="pdBuy2">${buyLabel}</button>
            <dl class="specs">
              <dt>Works with</dt><dd>${esc(T.hostNames(p.hosts)) || "See description"}</dd>
              ${p.compat ? `<dt>Requirements</dt><dd>${esc(p.compat)}</dd>` : ""}
              ${p.version ? `<dt>Version</dt><dd>${esc(p.version)}</dd>` : ""}
              <dt>License</dt><dd>1 user, commercial use</dd>
            </dl>
            <ul class="perks"><li>Instant download after payment</li><li>Free updates for this version</li><li>Step-by-step tutorial support</li></ul>
          </div>
        </aside>
      </div>
      ${more.length ? `<section class="pp-more"><h2>You might also like</h2><div class="more-grid" id="ppMore"></div></section>` : ""}`;
    $("#pdMedia").prepend(T.coverEl(p));
    const mg = $("#ppMore");
    if (mg) more.forEach((m) => {
      const a = document.createElement("a"); a.className = "more-card"; a.dataset.pid = m.id; a.href = url("plugin.html?id=" + encodeURIComponent(m.id));
      a.innerHTML = `<span class="mc-cover"></span><span class="mc-body"><b>${esc(m.name)}</b><span class="mc-price">${T.money(m.price)}</span></span>`;
      a.querySelector(".mc-cover").appendChild(T.coverEl(m)); mg.appendChild(a);
    });
    shots.forEach((s) => {
      const g = T.db.get("gallery", s.key), b = main.querySelector(`[data-zoom="${CSS.escape(s.key)}"]`); if (!b) return;
      if (g && g.img) { const im = new Image(); im.src = g.img; im.alt = s.title || ""; b.textContent = ""; b.appendChild(im); } else { b.textContent = "Image unavailable"; b.disabled = true; }
    });
    const buy = () => {
      if (T.isAdmin()) return T.go("admin/upload-plugin.html?id=" + encodeURIComponent(p.id));
      if (!T.cart().includes(p.id)) { T.addToCart(p.id); T.toast(`${p.name} added to cart`); }
      T.openCart();
    };
    $("#pdBuy").onclick = buy; $("#pdBuy2").onclick = buy;
    renderReviews(p);
  }

  function renderReviews(p) {
    const box = $("#pdReviews"), r = T.ratingOf(p.id), c = T.me(), mine = c && r.list.find((x) => x.cid === c.id);
    $("#pdRate").innerHTML = r.n ? `${T.starsHtml(r.avg)} ${r.avg.toFixed(1)} · ${r.n} rating${r.n > 1 ? "s" : ""}` : `${T.starsHtml(0)} No ratings yet`;
    const dist = [5, 4, 3, 2, 1].map((s) => [s, r.list.filter((x) => x.stars === s).length]);
    const draft = box.querySelector("#rvText")?.value;
    let form;
    if (T.isAdmin()) form = `<div class="note">You're the admin, so you manage reviews from the dashboard.</div>`;
    else if (!c) form = `<div class="mini"><span>Bought it? Log in to leave a rating.</span><a class="btn xs" href="${url("login.html?next=" + encodeURIComponent("plugin.html?id=" + p.id + "#reviews"))}">Log in</a></div>`;
    else {
      if (!pick) pick = mine?.stars || 0;
      form = `<form class="form" id="rvForm" novalidate>
        <div class="field"><span class="lab">${mine ? "Update your rating" : "Rate this plugin"}</span>
          <div class="pick" role="radiogroup" aria-label="Stars">${[1, 2, 3, 4, 5].map((i) => `<button type="button" data-star="${i}" class="${i <= pick ? "on" : ""}" aria-label="${i} star${i > 1 ? "s" : ""}">★</button>`).join("")}</div></div>
        <div class="field"><label for="rvText">Your review <small>(optional)</small></label><textarea id="rvText" maxlength="600" style="min-height:70px" placeholder="What did you make with it?">${esc(draft ?? mine?.text ?? "")}</textarea></div>
        <span class="err" id="rvErr" hidden></span>
        <button class="btn sm" id="rvBtn">${mine ? "Update review" : "Post review"}</button></form>`;
    }
    box.innerHTML = `<h3>Ratings & reviews</h3>
      ${r.n ? `<div class="rsum"><div><div class="big">${r.avg.toFixed(1)}</div>${T.starsHtml(r.avg)}</div><div class="dist">${dist.map(([s, n]) => `<div><span>${s}</span><div class="track"><span style="width:${r.n ? (n / r.n) * 100 : 0}%"></span></div><span>${n}</span></div>`).join("")}</div></div>` : ""}
      ${form}
      ${r.list.map((x) => `<div class="rv"><div class="top"><span>${esc(x.name)}${T.owns(x.cid, p.id) ? `<span class="vbadge">Verified buyer</span>` : ""}</span>${T.starsHtml(x.stars)}</div>${x.text ? `<p>${esc(x.text)}</p>` : ""}<span class="note">${T.fdate(x.at)}</span></div>`).join("") || `<div class="note">Be the first to rate it.</div>`}`;
    const f = $("#rvForm");
    if (f) f.onsubmit = (e) => {
      e.preventDefault();
      if (!pick) return T.showErr($("#rvErr"), "Tap the stars to choose a rating.");
      try {
        T.db.set("reviews", `${c.id}_${p.id}`, { pid: p.id, cid: c.id, name: `${c.first} ${(c.last || "")[0] || ""}.`.trim(), stars: pick, text: $("#rvText").value.trim().slice(0, 600), at: Date.now() });
        T.toast("Thanks for your rating!"); $("#rvText").value = ""; renderReviews(p);
      } catch (x) { T.showErr($("#rvErr"), x.message || "Couldn't save just now. Try again."); }
    };
  }

  main.addEventListener("click", (e) => {
    const s = e.target.closest("[data-star]");
    if (s) { pick = +s.dataset.star; main.querySelectorAll("[data-star]").forEach((b) => b.classList.toggle("on", +b.dataset.star <= pick)); return; }
    const z = e.target.closest("[data-zoom]");
    if (z) { const g = T.db.get("gallery", z.dataset.zoom); if (!g) return; $("#lbImg").src = g.img; $("#lbCap").textContent = z.getAttribute("aria-label").replace(/^Enlarge image: /, ""); $("#lightbox").hidden = false; }
  });
  $("#lightbox").addEventListener("click", () => ($("#lightbox").hidden = true));
  addEventListener("keydown", (e) => { if (e.key === "Escape") $("#lightbox").hidden = true; });
  document.addEventListener("cart", () => { const p = T.product(id); if (p) { const l = T.isAdmin() ? "Edit plugin" : T.cart().includes(p.id) ? "Go to cart" : p.price ? "Add to cart" : "Get it free"; ["#pdBuy", "#pdBuy2"].forEach((s) => { const b = $(s); if (b) b.textContent = l; }); } });
  render();
  if (location.hash === "#reviews") setTimeout(() => $("#reviews")?.scrollIntoView({ behavior: "smooth" }), 100);
})();
