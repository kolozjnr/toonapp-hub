/* Customer dashboard: overview. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  function render() {
    const c = T.me(); if (!c) return;
    const lib = T.library(c.id), orders = T.orders(c.id), spent = orders.reduce((s, o) => s + (o.total || 0), 0), ups = T.updatesFor(c), unread = T.unreadFor(c);
    const owned = new Set(lib.map((l) => l.id));
    const recs = T.liveProducts().filter((p) => !owned.has(p.id)).slice(0, 3);
    const hour = new Date().getHours(), greet = hour < 12 ? "Good morning" : hour < 17 ? "Good afternoon" : "Good evening";
    $("#content").innerHTML = `
      <section class="hello"><div class="hello-l"><svg class="toon-peek" viewBox="40 50 290 300" aria-hidden="true"><path d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z" fill="#7B5CFF" stroke="#1B1530" stroke-width="8"/><path d="M95 150 C 110 110, 140 92, 165 88" stroke="#fff" stroke-opacity=".55" stroke-width="10" fill="none" stroke-linecap="round"/><ellipse cx="135" cy="190" rx="30" ry="34" fill="#fff" stroke="#1B1530" stroke-width="6"/><ellipse cx="225" cy="190" rx="30" ry="34" fill="#fff" stroke="#1B1530" stroke-width="6"/><circle cx="140" cy="196" r="13" fill="#1B1530"/><circle cx="230" cy="196" r="13" fill="#1B1530"/><ellipse cx="102" cy="240" rx="16" ry="9" fill="#FF5C8A" opacity=".85"/><ellipse cx="258" cy="240" rx="16" ry="9" fill="#FF5C8A" opacity=".85"/><path d="M150 248 Q180 284 210 248" stroke="#1B1530" stroke-width="7" fill="#FF5C8A" stroke-linejoin="round"/></svg><div><h2>${greet}, ${esc(c.first)} 👋</h2><p>${lib.length ? `You own ${lib.length} plugin${lib.length > 1 ? "s" : ""}. Download them any time from My plugins.` : "Welcome to ToonApp Hub. Your purchases will appear here."}</p></div></div>
        <a class="btn white" href="${url(lib.length ? "customer/plugins.html" : "index.html#shop")}">${lib.length ? "Go to My plugins" : "Browse plugins"}</a></section>
      <section class="kpis">
        ${T.dash.kpi("box", "", "Plugins owned", lib.length, lib.length ? "Ready to download" : "None yet")}
        ${T.dash.kpi("receipt", "ok", "Orders", orders.length, orders.length ? "Last on " + T.fdate(orders[0].at) : "No orders yet")}
        ${T.dash.kpi("money", "warn", "Total spent", T.money2(spent), "All time")}
        ${T.dash.kpi("bell", unread ? "bad" : "", "Unread updates", unread, ups.length + " in total")}
      </section>
      <div class="grid2">
        <section class="panel"><div class="panel-h"><div><h2>Recent plugins</h2><p>Your latest purchases.</p></div><a class="btn ghost sm" href="${url("customer/plugins.html")}">View all</a></div>
          <div class="list" id="recent">${lib.length ? "" : T.dash.empty("box", "No plugins yet", "When you buy or claim a plugin it shows up here, ready to download.", `<a class="btn sm" href="${url("index.html#shop")}">Browse plugins</a>`)}</div></section>
        <section class="panel"><div class="panel-h"><div><h2>Latest updates</h2><p>News about your plugins.</p></div><a class="btn ghost sm" href="${url("customer/updates.html")}">View all</a></div>
          <div class="list">${ups.slice(0, 4).map((n) => `<div class="row-i"><span class="badge ${n.at > (c.seenNews || c.joined || 0) ? "brand" : "plain"}">${n.at > (c.seenNews || c.joined || 0) ? "New" : T.ago(n.at)}</span><div class="grow"><b>${esc(n.subject)}</b><small>${esc((n.body || "").slice(0, 90))}${(n.body || "").length > 90 ? "…" : ""}</small></div></div>`).join("") || T.dash.empty("bell", "No updates yet", "News about plugin updates and new releases will appear here.")}</div></section>
      </div>
      ${recs.length ? `<section class="panel"><div class="panel-h"><div><h2>Recommended for you</h2><p>Plugins you don't have yet.</p></div><a class="btn ghost sm" href="${url("index.html#shop")}">Visit the shop</a></div><div class="ptiles" id="recs"></div></section>` : ""}`;
    const rec = $("#recent");
    lib.slice(0, 4).forEach((l) => {
      const p = T.product(l.id) || { name: l.name }, dl = T.downloadUrl(l.id), row = document.createElement("div");
      row.className = "row-i";
      row.innerHTML = `<span class="thumb"></span><div class="grow"><b>${esc(p.name)}</b><small>${p.version ? "v" + esc(p.version) + " · " : ""}Bought ${T.fdate(l.at)}</small></div>${dl ? `<a class="btn sm" href="${esc(dl)}" target="_blank" rel="noopener noreferrer">${T.icon("download")}Download</a>` : `<span class="badge warn">Link coming soon</span>`}`;
      row.querySelector(".thumb").appendChild(T.coverEl(p)); rec.appendChild(row);
    });
    const rg = $("#recs");
    recs.forEach((p) => {
      const t = document.createElement("article"); t.className = "ptile";
      t.innerHTML = `<a class="pc" href="${url("plugin.html?id=" + encodeURIComponent(p.id))}"></a><div class="pb"><h3>${esc(p.name)}</h3><span class="meta">${esc(p.category || "Plugin")} · ${T.money(p.price)}</span><div class="pa"><a class="btn sm ghost" href="${url("plugin.html?id=" + encodeURIComponent(p.id))}">View plugin</a></div></div>`;
      t.querySelector(".pc").appendChild(T.coverEl(p)); rg?.appendChild(t);
    });
  }
  T.db.on(render); render();
})();
