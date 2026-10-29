/* Admin: reviews with remove. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  function render() {
    const rs = T.reviews(), avg = rs.length ? rs.reduce((s, r) => s + r.stars, 0) / rs.length : 0;
    $("#content").innerHTML = `<section class="kpis">${T.dash.kpi("star", "warn", "Average rating", rs.length ? avg.toFixed(1) + " / 5" : "–", "")}${T.dash.kpi("chat", "", "Reviews", rs.length, rs.filter((r) => r.text).length + " with comments")}</section>
      ${rs.length ? `<div class="tbl-wrap"><table><thead><tr><th>Plugin</th><th>Customer</th><th>Rating</th><th>Review</th><th>Date</th><th></th></tr></thead><tbody>
        ${rs.map((r) => { const p = T.product(r.pid), c = T.db.get("customers", r.cid); return `<tr><td>${p ? `<a href="${url("plugin.html?id=" + encodeURIComponent(p.id) + "#reviews")}"><b>${esc(p.name)}</b></a>` : `<span class="note">Removed plugin</span>`}</td>
          <td><b>${esc(c ? c.first + " " + c.last : r.name)}</b>${T.owns(r.cid, r.pid) ? `<span class="sub">Verified buyer</span>` : ""}</td><td>${T.dash.stars(r.stars)}</td><td style="min-width:220px">${esc(r.text || "–")}</td><td class="num">${T.fdate(r.at)}</td>
          <td><div class="acts"><button class="btn xs danger ghost" data-del="${esc(r.id)}">Remove</button></div></td></tr>`; }).join("")}
        </tbody></table></div>` : `<section class="panel">${T.dash.empty("star", "No reviews yet", "Customer ratings appear here. You can remove any that break your rules.")}</section>`}`;
  }
  $("#content").addEventListener("click", (e) => { const b = e.target.closest("[data-del]"); if (b && T.armed(b)) { T.db.del("reviews", b.dataset.del); T.toast("Review removed"); } });
  T.db.on(render); render();
})();
