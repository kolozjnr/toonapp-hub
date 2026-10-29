/* Admin: inbox for Contact page messages. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc } = T;
  let filter = "all";
  function render() {
    const all = T.db.all("messages").sort((a, b) => b.at - a.at), unread = all.filter((m) => !m.read).length;
    const list = filter === "unread" ? all.filter((m) => !m.read) : all;
    $("#content").innerHTML = `<section class="panel"><div class="panel-h"><div><h2>Inbox ${unread ? `<span class="badge bad">${unread} new</span>` : ""}</h2><p>Reply by email. Customers see your reply in their inbox.</p></div>
        <div class="savebar"><button class="btn sm ${filter === "all" ? "" : "ghost"}" data-f="all">All (${all.length})</button><button class="btn sm ${filter === "unread" ? "" : "ghost"}" data-f="unread">Unread (${unread})</button></div></div>
      <div class="list">${list.map((m) => `<div class="row-i" style="align-items:flex-start">
        <span class="kpi-dot" aria-hidden="true">${T.icon("chat")}</span>
        <div class="grow"><b>${esc(m.name)} ${m.read ? "" : `<span class="badge bad">New</span>`} <span class="badge plain">${esc(m.topic)}</span></b><small>${esc(m.email)} · ${T.fdate(m.at)} ${new Date(m.at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}</small><p>${esc(m.text)}</p>
          <div class="acts" style="justify-content:flex-start;margin-top:10px;flex-wrap:wrap">
            <a class="btn xs" href="${esc(T.dash.gmail(m.email, "Re: " + m.topic))}" target="_blank" rel="noopener noreferrer" data-read="${esc(m.id)}">${T.icon("mail")}Reply in Gmail</a>
            <button class="btn xs ghost" data-copy="${esc(m.email)}">${T.icon("copy")}Copy email</button>
            <button class="btn xs ghost" data-toggle="${esc(m.id)}">${m.read ? "Mark unread" : "Mark read"}</button>
            <button class="btn xs danger ghost" data-del="${esc(m.id)}">Delete</button></div></div></div>`).join("") || T.dash.empty("chat", filter === "unread" ? "No unread messages" : "No messages yet", "When a customer uses the Contact page, their message lands here.")}</div></section>`;
  }
  $("#content").addEventListener("click", (e) => {
    const f = e.target.closest("[data-f]"); if (f) { filter = f.dataset.f; render(); return; }
    const r = e.target.closest("[data-read]"); if (r) { T.db.update("messages", r.dataset.read, { read: true }); return; }
    const c = e.target.closest("[data-copy]"); if (c) { T.copy(c.dataset.copy, "Email copied"); return; }
    const t = e.target.closest("[data-toggle]"); if (t) { const m = T.db.get("messages", t.dataset.toggle); T.db.update("messages", m.id, { read: !m.read }); return; }
    const d = e.target.closest("[data-del]"); if (d && T.armed(d)) { T.db.del("messages", d.dataset.del); T.toast("Message deleted"); }
  });
  T.db.on(render); render();
})();
