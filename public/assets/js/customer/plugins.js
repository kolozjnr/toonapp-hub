/* Customer dashboard: My plugins (downloads). */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  let q = "";
  function render() {
    const c = T.me(); if (!c) return;
    const lib = T.library(c.id);
    if (!lib.length) {
      $("#content").innerHTML = `<section class="panel">${T.dash.empty("box", "You don't own any plugins yet", "Plugins you buy or claim for free appear here with their download links.", `<a class="btn" href="${url("index.html#shop")}">Browse plugins</a>`)}</section>`;
      return;
    }
    $("#content").innerHTML = `<div class="toolbar"><label class="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="q" type="search" placeholder="Search my plugins" value="${esc(q)}" aria-label="Search my plugins"></label><span class="note">${lib.length} plugin${lib.length > 1 ? "s" : ""}</span></div>
      <div class="ptiles" id="tiles"></div>`;
    const grid = $("#tiles");
    lib.filter((l) => !q || (T.product(l.id)?.name || l.name).toLowerCase().includes(q)).forEach((l) => {
      const p = T.product(l.id) || { name: l.name }, dl = T.downloadUrl(l.id), t = document.createElement("article");
      t.className = "ptile";
      t.innerHTML = `<span class="pc"></span><div class="pb">
        <h3>${esc(p.name)}</h3>
        <span class="meta">${p.version ? "Version " + esc(p.version) + " · " : ""}Bought ${T.fdate(l.at)} · Order ${esc(l.order || "")}</span>
        ${p.hosts ? `<span class="meta">Works with ${esc(T.hostNames(p.hosts))}</span>` : ""}
        <div class="pa">
          ${dl ? `<a class="btn sm" href="${esc(dl)}" target="_blank" rel="noopener noreferrer">${T.icon("download")}Download</a>` : `<span class="badge warn" style="flex:1;justify-content:center">Download link coming soon</span>`}
          ${T.ytOk(p.youtube) ? `<a class="btn sm ghost" href="${esc(p.youtube)}" target="_blank" rel="noopener noreferrer">${T.icon("play")}Tutorial</a>` : ""}
          ${p.id ? `<a class="btn sm ghost" href="${url("plugin.html?id=" + encodeURIComponent(p.id) + "#reviews")}">${T.icon("star")}Rate</a>` : ""}
        </div></div>`;
      t.querySelector(".pc").appendChild(T.coverEl(p)); grid.appendChild(t);
    });
    const qi = $("#q"); qi.oninput = (e) => { q = e.target.value.trim().toLowerCase(); render(); const n = $("#q"); n.focus(); n.setSelectionRange(n.value.length, n.value.length); };
  }
  T.db.on(render); render();
})();
