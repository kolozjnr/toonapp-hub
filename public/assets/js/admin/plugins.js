/* Admin: list of plugins with edit / hide / delete. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  let q = "";
  function render() {
    const orders = T.orders(), sales = {};
    orders.forEach((o) => (o.items || []).forEach((i) => { (sales[i.id] = sales[i.id] || { n: 0, amt: 0 }).n++; sales[i.id].amt += i.price || 0; }));
    const all = T.products(), ps = all.filter((p) => !q || [p.name, p.category, p.tagline].join(" ").toLowerCase().includes(q));
    $("#content").innerHTML = `<div class="toolbar">
        <label class="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="q" type="search" placeholder="Search plugins" value="${esc(q)}" aria-label="Search plugins"></label>
        <span class="note">${all.length} plugin${all.length === 1 ? "" : "s"} · ${all.filter((p) => p.live !== false).length} live</span>
        <a class="btn" style="margin-left:auto" href="${url("admin/upload-plugin.html")}">${T.icon("upload")}Upload plugin</a></div>
      ${all.length ? `<div class="tbl-wrap"><table><thead><tr><th></th><th>Plugin</th><th>Price</th><th>Status</th><th>Sales</th><th>Rating</th><th>Tutorial</th><th></th></tr></thead><tbody>
        ${ps.map((p) => { const r = T.ratingOf(p.id), s = sales[p.id] || { n: 0, amt: 0 }; return `<tr>
          <td><div class="thumb" data-thumb="${esc(p.id)}"></div></td>
          <td><b>${esc(p.name)}</b><span class="sub">${esc(p.category || "Plugin")} · ${esc((p.hosts || []).join(", "))}${p.version ? " · v" + esc(p.version) : ""}</span></td>
          <td class="num">${T.money(p.price)}${p.was > p.price ? ` <s class="note">$${p.was}</s>` : ""}</td>
          <td>${p.live === false ? `<span class="badge">Hidden</span>` : `<span class="badge ok">Live</span>`}</td>
          <td class="num">${s.n} · ${T.money2(s.amt)}</td>
          <td class="num">${r.n ? `★ ${r.avg.toFixed(1)} (${r.n})` : "–"}</td>
          <td>${T.ytOk(p.youtube) ? `<a href="${esc(p.youtube)}" target="_blank" rel="noopener noreferrer">YouTube ↗</a>` : `<span class="badge warn">Missing</span>`}</td>
          <td><div class="acts"><a class="btn xs ghost" href="${url("plugin.html?id=" + encodeURIComponent(p.id))}">View</a><a class="btn xs soft" href="${url("admin/upload-plugin.html?id=" + encodeURIComponent(p.id))}">Edit</a><button class="btn xs ghost" data-toggle="${esc(p.id)}">${p.live === false ? "Show" : "Hide"}</button><button class="btn xs danger ghost" data-del="${esc(p.id)}">Delete</button></div></td></tr>`; }).join("")}
        </tbody></table></div>` : `<section class="panel">${T.dash.empty("box", "No plugins yet", "Upload your first plugin and it appears in the store straight away.", `<a class="btn" href="${url("admin/upload-plugin.html")}">Upload plugin</a>`)}</section>`}`;
    T.$$("[data-thumb]").forEach((el) => el.appendChild(T.coverEl(T.product(el.dataset.thumb))));
    const qi = $("#q"); qi.oninput = (e) => { q = e.target.value.trim().toLowerCase(); render(); const n = $("#q"); n.focus(); n.setSelectionRange(n.value.length, n.value.length); };
  }
  $("#content").addEventListener("click", (e) => {
    const t = e.target.closest("[data-toggle]");
    if (t) { const p = T.product(t.dataset.toggle); T.db.update("products", p.id, { live: p.live === false }); T.toast(p.live === false ? "Plugin is live" : "Plugin hidden from customers"); return; }
    const d = e.target.closest("[data-del]");
    if (d) {
      if (!T.armed(d, "Sure?")) return;
      const p = T.product(d.dataset.del);
      (p.shots || []).forEach((s) => T.db.del("gallery", s.key));
      T.db.del("files", p.id); T.db.del("products", p.id);
      T.toast("Plugin deleted");
    }
  });
  T.db.on(render); render();
})();
