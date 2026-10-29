/* Admin: all orders. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc } = T;
  let q = "";
  function render() {
    const all = T.orders(), cust = (cid) => T.db.get("customers", cid);
    const list = all.filter((o) => { if (!q) return true; const c = cust(o.cid); return [o.number, c?.first, c?.last, c?.email, ...o.items.map((i) => i.name)].join(" ").toLowerCase().includes(q); });
    const revenue = all.reduce((s, o) => s + (o.total || 0), 0), week = all.filter((o) => Date.now() - o.at < 7 * 864e5);
    $("#content").innerHTML = `<section class="kpis">${T.dash.kpi("receipt", "", "Orders", all.length, week.length + " this week")}${T.dash.kpi("money", "ok", "Revenue", T.money2(revenue), T.money2(week.reduce((s, o) => s + o.total, 0)) + " this week")}${T.dash.kpi("money", "warn", "Average order", T.money2(all.length ? revenue / all.length : 0), "")}</section>
      <div class="toolbar"><label class="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="q" type="search" placeholder="Search by order, customer or plugin" value="${esc(q)}" aria-label="Search orders"></label></div>
      ${all.length ? `<div class="tbl-wrap"><table><thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Plugins</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
        ${list.map((o) => { const c = cust(o.cid); return `<tr><td class="num"><b>${esc(o.number)}</b></td><td class="num">${T.fdate(o.at)}<span class="sub">${new Date(o.at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</span></td>
          <td>${c ? `<b>${esc(c.first)} ${esc(c.last)}</b><span class="sub">${esc(c.email)}</span>` : `<span class="note">Deleted customer</span>`}</td>
          <td>${o.items.map((i) => esc(i.name)).join(", ")}</td><td class="num"><b>${T.money2(o.total)}</b></td><td><span class="badge ok">Paid</span></td>
          <td><div class="acts">${c ? `<button class="btn xs ghost" data-resend="${esc(o.id)}">${T.icon("mail")}Resend links</button>` : ""}</div></td></tr>`; }).join("") || `<tr><td colspan="7" class="note">No orders match your search.</td></tr>`}
        </tbody></table></div>` : `<section class="panel">${T.dash.empty("receipt", "No orders yet", "When a customer checks out, the order lands here with their name, email and what they bought.")}</section>`}`;
    const qi = $("#q"); qi.oninput = (e) => { q = e.target.value.trim().toLowerCase(); render(); const n = $("#q"); n.focus(); n.setSelectionRange(n.value.length, n.value.length); };
  }
  $("#content").addEventListener("click", async (e) => {
    const b = e.target.closest("[data-resend]"); if (!b) return;
    const o = T.db.get("orders", b.dataset.resend), c = T.db.get("customers", o.cid);
    b.disabled = true; const t = b.innerHTML; b.textContent = "Sending…";
    const r = await T.sendPurchaseEmail(o, c); b.disabled = false; b.innerHTML = t;
    T.toast(r.ok ? `Download links sent to ${c.email}` : r.reason === "setup" ? "Set up email sending in Settings first." : "Couldn't send the email. Check Settings → Email sending.");
  });
  T.db.on(render); render();
})();
