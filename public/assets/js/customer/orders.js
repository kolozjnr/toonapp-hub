/* Customer dashboard: order history. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  function render() {
    const c = T.me(); if (!c) return;
    const orders = T.orders(c.id);
    if (!orders.length) {
      $("#content").innerHTML = `<section class="panel">${T.dash.empty("receipt", "No orders yet", "When you buy a plugin, the receipt shows up here.", `<a class="btn" href="${url("index.html#shop")}">Browse plugins</a>`)}</section>`;
      return;
    }
    const spent = orders.reduce((s, o) => s + (o.total || 0), 0);
    $("#content").innerHTML = `<section class="kpis">${T.dash.kpi("receipt", "", "Orders", orders.length, "")}${T.dash.kpi("money", "ok", "Total spent", T.money2(spent), "")}</section>
      <div class="tbl-wrap"><table><thead><tr><th>Order</th><th>Date</th><th>Plugins</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
      ${orders.map((o) => `<tr><td class="num"><b>${esc(o.number)}</b></td><td class="num">${T.fdate(o.at)}</td><td>${o.items.map((i) => esc(i.name)).join(", ")}</td><td class="num"><b>${T.money2(o.total)}</b></td><td><span class="badge ok">Paid</span></td>
        <td><div class="acts"><button class="btn xs ghost" data-mail="${esc(o.id)}">${T.icon("mail")}Email my links</button></div></td></tr>`).join("")}
      </tbody></table></div>
      <p class="note">“Email my links” sends the download links for that order to ${esc(c.email)} again.</p>`;
  }
  $("#content").addEventListener("click", async (e) => {
    const b = e.target.closest("[data-mail]"); if (!b) return;
    const o = T.db.get("orders", b.dataset.mail), c = T.me(); if (!o) return;
    b.disabled = true; const t = b.innerHTML; b.textContent = "Sending…";
    const r = await T.sendPurchaseEmail(o, c);
    b.disabled = false; b.innerHTML = t;
    T.toast(r.ok ? `Download links sent to ${c.email}` : r.reason === "setup" ? "Emails aren't switched on for this store yet. Your downloads are in My plugins." : "Couldn't send the email. Try again later.");
  });
  T.db.on(render); render();
})();
