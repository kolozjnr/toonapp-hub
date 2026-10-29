/* Admin: registered customers. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc } = T;
  let q = "";
  function render() {
    const all = T.db.all("customers").sort((a, b) => (b.joined || 0) - (a.joined || 0));
    const list = all.filter((c) => !q || [c.first, c.last, c.email, c.country].join(" ").toLowerCase().includes(q));
    const subs = all.filter((c) => c.news !== false).length, buyers = all.filter((c) => T.orders(c.id).length).length;
    $("#content").innerHTML = `<section class="kpis">${T.dash.kpi("users", "", "Customers", all.length, "Registered")}${T.dash.kpi("receipt", "ok", "Buyers", buyers, "Placed at least one order")}${T.dash.kpi("mail", "warn", "Newsletter", subs, "Subscribed")}</section>
      <div class="toolbar"><label class="search"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg><input id="q" type="search" placeholder="Search by name, email or country" value="${esc(q)}" aria-label="Search customers"></label>
        ${all.length ? `<button class="btn ghost sm" id="copyAll" style="margin-left:auto">${T.icon("copy")}Copy subscriber emails</button>` : ""}</div>
      ${all.length ? `<div class="tbl-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Country</th><th>Newsletter</th><th>Joined</th><th>Orders</th><th>Spent</th></tr></thead><tbody>
        ${list.map((c) => { const os = T.orders(c.id); return `<tr><td><b>${esc(c.first)} ${esc(c.last)}</b>${c.verified ? `<span class="sub">Email confirmed</span>` : ""}</td><td>${esc(c.email)}</td><td>${esc(c.country)}</td><td>${c.news === false ? `<span class="badge">No</span>` : `<span class="badge ok">Yes</span>`}</td><td class="num">${T.fdate(c.joined)}</td><td class="num">${os.length}</td><td class="num">${T.money2(os.reduce((s, o) => s + (o.total || 0), 0))}</td></tr>`; }).join("") || `<tr><td colspan="7" class="note">No customers match your search.</td></tr>`}
        </tbody></table></div>` : `<section class="panel">${T.dash.empty("users", "No customers yet", "Everyone who registers shows up here with their name, email and country.")}</section>`}`;
    const qi = $("#q"); qi.oninput = (e) => { q = e.target.value.trim().toLowerCase(); render(); const n = $("#q"); n.focus(); n.setSelectionRange(n.value.length, n.value.length); };
    const ca = $("#copyAll"); if (ca) ca.onclick = () => T.copy(all.filter((c) => c.news !== false).map((c) => c.email).join(", "), "Subscriber emails copied");
  }
  T.db.on(render); render();
})();
