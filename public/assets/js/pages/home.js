/* Landing page: plugin shelf with filters, search and sort. */
(function () {
  "use strict";
  const { $, esc, url } = T;
  const state = { cat: "All", q: "", sort: "new" };
  $("#heroApps").innerHTML = ["AE", "PR", "DR", "C4D"].map(T.hostTag).join("");

  function render() {
    const all = T.liveProducts(), cart = T.cart(), admin = T.isAdmin();
    const cats = ["All", ...new Set(all.map((p) => p.category).filter(Boolean))];
    if (!cats.includes(state.cat)) state.cat = "All";
    $("#cats").innerHTML = cats.map((c) => `<button class="chip" data-cat="${esc(c)}" aria-pressed="${state.cat === c}">${esc(c)}</button>`).join("");
    let list = all.filter((p) => (state.cat === "All" || p.category === state.cat) && (!state.q || [p.name, p.tagline, p.description, p.category].join(" ").toLowerCase().includes(state.q)));
    const rk = Object.fromEntries(list.map((p) => [p.id, T.ratingOf(p.id)]));
    list.sort({ new: (a, b) => (b.createdAt || 0) - (a.createdAt || 0), top: (a, b) => rk[b.id].avg - rk[a.id].avg || rk[b.id].n - rk[a.id].n, low: (a, b) => (a.price || 0) - (b.price || 0), high: (a, b) => (b.price || 0) - (a.price || 0) }[state.sort]);
    $("#countNote").textContent = all.length ? `${list.length} of ${all.length} plugin${all.length === 1 ? "" : "s"}` : "";
    const g = $("#grid");
    if (!all.length) { g.innerHTML = `<div class="empty"><h3>New plugins are on the way</h3><p>Nothing on the shelf yet. Check back soon.</p>${admin ? `<a class="btn purple" href="${url("admin/upload-plugin.html")}">+ Upload your first plugin</a>` : ""}</div>`; return; }
    if (!list.length) { g.innerHTML = `<div class="empty"><h3>No matches</h3><p>Try another category or clear the search.</p></div>`; return; }
    g.innerHTML = "";
    list.forEach((p) => {
      const href = url("plugin.html?id=" + encodeURIComponent(p.id)), inCart = cart.includes(p.id), r = rk[p.id];
      const a = document.createElement("article");
      a.className = "card"; a.dataset.pid = p.id;
      a.innerHTML = `
        <span class="sticker ${p.price ? "" : "free"}">${T.money(p.price)}${p.was > p.price ? `<s>$${p.was}</s>` : ""}</span>
        <a class="cover" href="${href}" aria-label="View ${esc(p.name)}"></a>
        <div class="cbody">
          <div class="cmeta"><span>${esc(p.category || "Plugin")}</span><span style="display:flex;gap:4px">${(p.hosts || []).map(T.hostTag).join("")}</span></div>
          <h3><a href="${href}" style="color:inherit;text-decoration:none">${esc(p.name)}</a></h3>
          <span class="rate">${r.n ? `${T.starsHtml(r.avg)} ${r.avg.toFixed(1)} (${r.n})` : `${T.starsHtml(0)} No ratings yet`}</span>
          <p>${esc(p.tagline || "")}</p>
          <div class="cfoot">
            ${admin ? `<a class="btn sm purple" href="${url("admin/upload-plugin.html?id=" + encodeURIComponent(p.id))}">Edit</a>` : `<button class="btn sm ${inCart ? "in" : ""}" data-add="${esc(p.id)}">${inCart ? "✓ In cart" : p.price ? "Add to cart" : "Get it free"}</button>`}
            <a class="btn sm white" href="${href}">Preview</a>
          </div>
        </div>`;
      const cov = a.querySelector(".cover"); cov.appendChild(T.coverEl(p));
      if (p.badge) cov.insertAdjacentHTML("beforeend", `<span class="flag">${esc(p.badge)}</span>`);
      if (T.ytOk(p.youtube)) cov.insertAdjacentHTML("beforeend", `<span class="vidflag">▶ Tutorial</span>`);
      g.appendChild(a);
    });
  }
  $("#cats").addEventListener("click", (e) => { const b = e.target.closest("[data-cat]"); if (b) { state.cat = b.dataset.cat; render(); } });
  $("#q").addEventListener("input", (e) => { state.q = e.target.value.trim().toLowerCase(); render(); });
  $("#sort").addEventListener("change", (e) => { state.sort = e.target.value; render(); });
  $("#grid").addEventListener("click", (e) => {
    const b = e.target.closest("[data-add]"); if (!b) return;
    const id = b.dataset.add;
    if (T.cart().includes(id)) { T.openCart(); return; }
    T.addToCart(id); T.toast(`${T.product(id)?.name || "Plugin"} added to cart`); render();
  });
  document.addEventListener("cart", render);
  T.db.on(render);
  render();
})();
