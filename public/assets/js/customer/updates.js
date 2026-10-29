/* Customer dashboard: updates / notifications. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  const c0 = T.me(), seenBefore = c0.seenNews || c0.joined || 0;
  try { T.db.update("customers", c0.id, { seenNews: Date.now() }); } catch (e) {}
  function render() {
    const c = T.me(); if (!c) return;
    const list = T.updatesFor(c);
    if (!list.length) {
      $("#content").innerHTML = `<section class="panel">${T.dash.empty("bell", "You're all caught up", "News about plugin updates and new releases will show up here and in your inbox.")}</section>`;
      return;
    }
    $("#content").innerHTML = `<section class="panel"><div class="panel-h"><div><h2>${list.length} update${list.length > 1 ? "s" : ""}</h2><p>Remove the ones you don't need.</p></div><button class="btn ghost sm" id="clearAll">Clear all</button></div>
      <div class="list">${list.map((n) => {
        const p = n.plugin ? T.product(n.plugin) : null, isNew = n.at > seenBefore;
        return `<div class="row-i" style="align-items:flex-start">
          <span class="kpi-dot" aria-hidden="true">${T.icon(n.link ? "download" : "bell")}</span>
          <div class="grow"><b>${esc(n.subject)} ${isNew ? `<span class="badge brand">New</span>` : ""}</b><small>${T.fdate(n.at)}${p ? " · " + esc(p.name) : ""}</small><p>${esc(n.body)}</p>
            <div class="acts" style="justify-content:flex-start;margin-top:10px;flex-wrap:wrap">
              ${T.linkOk(n.link) ? `<a class="btn sm" href="${esc(n.link)}" target="_blank" rel="noopener noreferrer">${T.icon("download")}Download update</a>` : ""}
              ${p && p.live !== false ? `<a class="btn sm ghost" href="${url("plugin.html?id=" + encodeURIComponent(p.id))}">View plugin</a>` : ""}
              ${p && T.ytOk(p.youtube) ? `<a class="btn sm ghost" href="${esc(p.youtube)}" target="_blank" rel="noopener noreferrer">${T.icon("play")}Tutorial</a>` : ""}
            </div></div>
          <button class="btn xs ghost" data-dismiss="${esc(n.id)}" aria-label="Remove this update">Remove</button></div>`;
      }).join("")}</div></section>`;
    $("#clearAll").onclick = (e) => { if (!T.armed(e.currentTarget, "Tap again to clear all")) return; hide(list.map((n) => n.id)); T.toast("All updates cleared"); };
  }
  function hide(ids) { const c = T.me(); try { T.db.update("customers", c.id, { hidden: [...new Set([...(c.hidden || []), ...ids])].slice(-500) }); } catch (x) { T.toast(x.message || "Couldn't save that."); } }
  $("#content").addEventListener("click", (e) => { const b = e.target.closest("[data-dismiss]"); if (b) hide([b.dataset.dismiss]); });
  T.db.on(render); render();
})();
