/* Admin: write and send updates to customers. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc, url } = T;
  const draft = { audience: "all", plugin: "", subject: "", body: "", link: "" };
  let sent = null;
  const recipients = (aud, plugin) => T.db.all("customers").filter((c) => c.news !== false && c.email && (aud !== "buyers" || (plugin && T.owns(c.id, plugin))));
  const emailText = (n) => {
    const p = n.plugin ? T.product(n.plugin) : null;
    return `${n.body}${n.link ? `\n\nDownload the update: ${n.link}` : ""}${p ? `\n\n${p.name}${p.version ? " v" + p.version : ""}${T.ytOk(p.youtube) ? "\nWatch the tutorial: " + p.youtube : ""}\nYour downloads are in My plugins on ToonApp Hub.` : ""}\n\n— ToonApp Hub\nYou're getting this because you asked for plugin updates. Reply "unsubscribe" to stop.`;
  };
  function render() {
    const posts = T.db.all("news").sort((a, b) => b.at - a.at), ps = T.products();
    let sentHtml = "";
    if (sent) {
      const list = recipients(sent.audience, sent.plugin), gm = T.dash.gmail("", sent.subject, emailText(sent), list.map((c) => c.email).join(","));
      sentHtml = `<section class="callout ok" style="flex-direction:column;gap:10px"><div><b>Posted! It's live in your customers' Updates.</b>To also send it by email, open it in Gmail with everyone in BCC, or copy the list into your email app.</div>
        ${list.length ? `<div class="code-box" id="nlEmails">${esc(list.map((c) => c.email).join(", "))}</div>
        <div class="savebar">${gm.length < 7500 ? `<a class="btn sm" href="${esc(gm)}" target="_blank" rel="noopener noreferrer">${T.icon("mail")}Open in Gmail (${list.length})</a>` : ""}<button class="btn sm ghost" id="cpEmails">${T.icon("copy")}Copy ${list.length} email${list.length > 1 ? "s" : ""}</button><button class="btn sm ghost" id="cpBody">${T.icon("copy")}Copy message</button></div>` : `<div>No subscribed customers match this audience yet.</div>`}</section>`;
    }
    $("#content").innerHTML = `${sentHtml}<div class="form-layout">
      <section class="panel"><div class="panel-h"><div><h2>Write an update</h2><p>Shows in each customer's Updates, and you can email it too.</p></div></div>
        <form class="form" id="nlForm" novalidate>
          <div class="two">
            <div class="field"><label for="nlAud">Send to</label><select id="nlAud"><option value="all">Everyone subscribed</option><option value="buyers">Owners of one plugin</option></select></div>
            <div class="field"><label for="nlPlugin">About plugin <small>(optional)</small></label><select id="nlPlugin"><option value="">No specific plugin</option>${ps.map((p) => `<option value="${esc(p.id)}">${esc(p.name)}</option>`).join("")}</select></div>
          </div>
          <div class="field"><label for="nlSubject">Subject</label><input id="nlSubject" maxlength="120" placeholder="e.g. Squash Stretch 1.2 is out: new presets inside"></div>
          <div class="field"><label for="nlBody">Message</label><textarea id="nlBody" maxlength="4000" style="min-height:170px" placeholder="What's new, what's fixed, and why they'll love it."></textarea></div>
          <div class="field"><label for="nlLink">Download link <small>(optional)</small></label><input id="nlLink" type="url" placeholder="https://drive.google.com/... link to the updated file"><small>Customers get a Download button on this update. To keep it for buyers only, choose "Owners of one plugin".</small></div>
          <span class="note" id="nlCount"></span><span class="err" id="nlErr" hidden></span>
          <div class="savebar"><button class="btn">${T.icon("mail")}Post update</button></div>
        </form></section>
      <aside class="form-side"><section class="panel"><div class="panel-h"><div><h2>Sent updates</h2><p>${posts.length} so far</p></div></div>
        <div class="list">${posts.map((n) => { const pl = n.plugin ? T.product(n.plugin) : null, r = recipients(n.audience, n.plugin).length; return `<div class="row-i" style="align-items:flex-start"><div class="grow"><b>${esc(n.subject)}</b><small>${T.fdate(n.at)} · ${n.audience === "buyers" ? "Owners of " + esc(pl?.name || "a removed plugin") : "Everyone"} · ${r} subscriber${r === 1 ? "" : "s"}${n.link ? " · has download" : ""}</small>
          <div class="acts" style="justify-content:flex-start;margin-top:8px"><button class="btn xs ghost" data-resend="${esc(n.id)}">Email again</button><button class="btn xs danger ghost" data-del="${esc(n.id)}">Delete</button></div></div></div>`; }).join("") || `<p class="note">Nothing sent yet. Your first update will show up here.</p>`}</div></section></aside></div>`;
    $("#nlAud").value = draft.audience; $("#nlPlugin").value = draft.plugin; $("#nlSubject").value = draft.subject; $("#nlBody").value = draft.body; $("#nlLink").value = draft.link;
    const count = () => {
      Object.assign(draft, { audience: $("#nlAud").value, plugin: $("#nlPlugin").value, subject: $("#nlSubject").value, body: $("#nlBody").value, link: $("#nlLink").value });
      const r = recipients(draft.audience, draft.plugin);
      $("#nlCount").textContent = draft.audience === "buyers" && !draft.plugin ? "Pick the plugin whose owners should get this." : `${r.length} subscribed customer${r.length === 1 ? "" : "s"} will get this.`;
    };
    ["nlAud", "nlPlugin", "nlSubject", "nlBody", "nlLink"].forEach((id) => $("#" + id).addEventListener("input", count)); count();
    $("#nlForm").onsubmit = (e) => {
      e.preventDefault(); const err = $("#nlErr"); T.showErr(err, "");
      const link = draft.link.trim();
      if (!draft.subject.trim()) return T.showErr(err, "Add a subject.");
      if (!draft.body.trim()) return T.showErr(err, "Write your message.");
      if (draft.audience === "buyers" && !draft.plugin) return T.showErr(err, "Pick the plugin whose owners should get this.");
      if (link && !T.linkOk(link)) return T.showErr(err, "The download link should start with https://");
      const doc = { subject: draft.subject.trim(), body: draft.body.trim(), plugin: draft.plugin || null, audience: draft.audience, link: link || null, at: Date.now() };
      try { doc.id = T.db.add("news", doc); sent = doc; Object.assign(draft, { subject: "", body: "", link: "" }); T.toast("Update posted to customers"); render(); scrollTo(0, 0); }
      catch (x) { T.showErr(err, x.message || "Couldn't post it. Try again."); }
    };
    if (sent) {
      const list = recipients(sent.audience, sent.plugin);
      const ce = $("#cpEmails"); if (ce) ce.onclick = () => T.copy(list.map((c) => c.email).join(", "), "Emails copied");
      const cb = $("#cpBody"); if (cb) cb.onclick = () => T.copy(`${sent.subject}\n\n${emailText(sent)}`, "Message copied");
    }
  }
  $("#content").addEventListener("click", (e) => {
    const r = e.target.closest("[data-resend]"); if (r) { sent = T.db.get("news", r.dataset.resend); render(); scrollTo(0, 0); return; }
    const d = e.target.closest("[data-del]"); if (d && T.armed(d)) { T.db.del("news", d.dataset.del); if (sent && sent.id === d.dataset.del) sent = null; T.toast("Update deleted"); render(); }
  });
  render();
})();
