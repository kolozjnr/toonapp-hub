/* Admin: store overview. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  const dayKey = (t) => new Date(t).toISOString().slice(0, 10);
  const custName = (cid) => { const c = T.db.get("customers", cid); return c ? `${c.first} ${c.last}` : "Deleted customer"; };
  function render() {
    const orders = T.orders(), revs = T.reviews(), customers = T.db.all("customers"), V = T.db.all("visits"), msgs = T.db.all("messages").filter((m) => !m.read);
    const revenue = orders.reduce((s, o) => s + (o.total || 0), 0), avg = revs.length ? revs.reduce((s, r) => s + r.stars, 0) / revs.length : 0;
    const totalV = V.reduce((s, v) => s + (v.count || 0), 0), today = dayKey(Date.now());
    const span = [...Array(14)].map((_, i) => { const k = dayKey(Date.now() - (13 - i) * 864e5); return { k, n: V.reduce((s, v) => s + ((v.days || {})[k] || 0), 0) }; });
    const maxV = Math.max(1, ...span.map((d) => d.n)), weekV = span.slice(7).reduce((s, d) => s + d.n, 0);
    const buyers = new Set(orders.map((o) => o.cid)).size, conv = V.length ? Math.round((buyers / V.length) * 100) : 0;
    const sales = {}; orders.forEach((o) => (o.items || []).forEach((i) => { (sales[i.id] = sales[i.id] || { n: 0, amt: 0 }).n++; sales[i.id].amt += i.price || 0; }));
    const top = T.products().map((p) => ({ p, s: sales[p.id] || { n: 0, amt: 0 } })).sort((a, b) => b.s.amt - a.s.amt || b.s.n - a.s.n).slice(0, 6), maxA = Math.max(1, ...top.map((t) => t.s.amt));
    const used = T.db.size(), cap = 5 * 1024 * 1024, pct = Math.min(100, Math.round((used / cap) * 100));
    const hour = new Date().getHours(), greet = hour < 12 ? "Good morning" : hour < 17 ? "Good afternoon" : "Good evening";
    $("#content").innerHTML = `
      <section class="hello"><div class="hello-l"><svg class="toon-peek" viewBox="40 50 290 300" aria-hidden="true"><path d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z" fill="#7B5CFF" stroke="#1B1530" stroke-width="8"/><path d="M95 150 C 110 110, 140 92, 165 88" stroke="#fff" stroke-opacity=".55" stroke-width="10" fill="none" stroke-linecap="round"/><ellipse cx="135" cy="190" rx="30" ry="34" fill="#fff" stroke="#1B1530" stroke-width="6"/><ellipse cx="225" cy="190" rx="30" ry="34" fill="#fff" stroke="#1B1530" stroke-width="6"/><circle cx="140" cy="196" r="13" fill="#1B1530"/><circle cx="230" cy="196" r="13" fill="#1B1530"/><ellipse cx="102" cy="240" rx="16" ry="9" fill="#FF5C8A" opacity=".85"/><ellipse cx="258" cy="240" rx="16" ry="9" fill="#FF5C8A" opacity=".85"/><path d="M150 248 Q180 284 210 248" stroke="#1B1530" stroke-width="7" fill="#FF5C8A" stroke-linejoin="round"/></svg><div><h2>${greet}, boss 👋</h2><p>${orders.length ? `${orders.length} order${orders.length > 1 ? "s" : ""} so far and ${T.products().length} plugin${T.products().length === 1 ? "" : "s"} on your shelf. Keep them coming!` : "Your store is ready. Upload a plugin and let the sales roll in."}</p></div></div>
        <a class="btn white" href="${url("admin/upload-plugin.html")}">${T.icon("upload")}Upload plugin</a></section>
      ${msgs.length ? `<div class="callout warn">${T.icon("chat")}<div><b>${msgs.length} unread message${msgs.length > 1 ? "s" : ""}</b>Customers are waiting for a reply. <a href="${url("admin/messages.html")}">Open messages</a></div></div>` : ""}
      <section class="kpis">
        ${T.dash.kpi("money", "ok", "Revenue", T.money2(revenue), "All time")}
        ${T.dash.kpi("receipt", "", "Orders", orders.length, orders.filter((o) => Date.now() - o.at < 7 * 864e5).length + " this week")}
        ${T.dash.kpi("users", "warn", "Customers", customers.length, "Registered")}
        ${T.dash.kpi("eye", "", "Visits", totalV.toLocaleString(), span[13].n + " today · " + V.length + " visitors")}
        ${T.dash.kpi("star", "warn", "Avg rating", revs.length ? avg.toFixed(1) : "–", revs.length + " review" + (revs.length === 1 ? "" : "s"))}
      </section>
      <section class="panel"><div class="panel-h"><div><h2>Visits, last 14 days</h2><p>${weekV} in the last 7 days · ${conv}% of visitors bought something</p></div></div>
        <div class="vchart" role="img" aria-label="Daily visits for the last 14 days">${span.map((d, i) => `<div class="vcol" title="${d.k}: ${d.n} visit${d.n === 1 ? "" : "s"}"><span class="vn">${d.n || ""}</span><span class="vbar ${d.k === today ? "today" : ""}" style="height:${(d.n / maxV) * 100}%"></span><span class="vd">${i % 2 === 1 || i === 13 ? new Date(d.k + "T12:00:00Z").toLocaleDateString("en-GB", { day: "numeric", month: "short" }) : ""}</span></div>`).join("")}</div>
      </section>
      <div class="grid2">
        <section class="panel"><div class="panel-h"><div><h2>Sales by plugin</h2><p>Revenue per plugin, all time.</p></div><a class="btn ghost sm" href="${url("admin/plugins.html")}">All plugins</a></div>
          ${top.length ? `<div class="bars">${top.map((t) => `<div class="barrow"><span class="nm">${esc(t.p.name)}</span><div class="track"><span style="width:${(t.s.amt / maxA) * 100}%"></span></div><span class="amt">${T.money2(t.s.amt)}</span></div>`).join("")}</div>` : T.dash.empty("box", "No plugins yet", "Upload a plugin to start selling.", `<a class="btn sm" href="${url("admin/upload-plugin.html")}">Upload plugin</a>`)}</section>
        <section class="panel"><div class="panel-h"><div><h2>Latest orders</h2><p>Most recent purchases.</p></div><a class="btn ghost sm" href="${url("admin/orders.html")}">All orders</a></div>
          <div class="list">${orders.slice(0, 5).map((o) => `<div class="row-i"><div class="grow"><b>${esc(custName(o.cid))}</b><small>${o.items.map((i) => esc(i.name)).join(", ")} · ${T.ago(o.at)}</small></div><b class="num">${T.money2(o.total)}</b></div>`).join("") || T.dash.empty("receipt", "No orders yet", "They'll show up here the moment someone buys.")}</div></section>
        <section class="panel"><div class="panel-h"><div><h2>Latest reviews</h2><p>What customers are saying.</p></div><a class="btn ghost sm" href="${url("admin/reviews.html")}">All reviews</a></div>
          <div class="list">${revs.slice(0, 4).map((r) => `<div class="row-i"><div class="grow"><b>${esc(r.name)} on ${esc(T.product(r.pid)?.name || "a removed plugin")}</b><small>${esc(r.text || "No comment")}</small></div>${T.dash.stars(r.stars)}</div>`).join("") || T.dash.empty("star", "No reviews yet", "Ratings appear here once customers leave them.")}</div></section>
        <section class="panel"><div class="panel-h"><div><h2>Store storage</h2><p>Space used in this browser for plugins, images and data.</p></div></div>
          <div class="meter"><span style="width:${pct}%;${pct > 80 ? "background:var(--bad)" : pct > 60 ? "background:var(--warn)" : ""}"></span></div>
          <div class="note">${(used / 1024 / 1024).toFixed(2)} MB of about 5 MB used (${pct}%). Cover and extra images take the most space.</div>
          <div class="savebar" style="margin-top:14px"><a class="btn ghost sm" href="${url("admin/settings.html#backup")}">${T.icon("download")}Back up store data</a></div></section>
      </div>`;
  }
  T.db.on(render); render();
})();
