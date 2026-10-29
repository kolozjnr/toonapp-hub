/* Admin: contact details, email sending, admin login, backup. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc } = T;
  const c = T.contact(), em = T.emailCfg(), adm = T.adminCreds();
  const connected = em.publicKey && em.serviceId;
  $("#content").innerHTML = `<div class="grid2">
    <section class="panel" id="contact"><div class="panel-h"><div><h2>Contact details</h2><p>Shown on your Contact page. Leave a field empty to hide it.</p></div></div>
      <form class="form" id="ciForm" novalidate>
        <div class="two"><div class="field"><label for="ciEmail">Support email</label><input id="ciEmail" type="email" value="${esc(c.email || "")}" placeholder="support@tooabihub.com"></div>
          <div class="field"><label for="ciWa">WhatsApp number</label><input id="ciWa" value="${esc(c.whatsapp || "")}" placeholder="+234 801 234 5678"></div></div>
        <div class="two"><div class="field"><label for="ciIg">Instagram link</label><input id="ciIg" type="url" value="${esc(c.instagram || "")}" placeholder="https://instagram.com/yourname"></div>
          <div class="field"><label for="ciYt">YouTube channel link</label><input id="ciYt" type="url" value="${esc(c.youtube || "")}" placeholder="https://youtube.com/@yourchannel"></div></div>
        <div class="field"><label for="ciHours">Support hours</label><input id="ciHours" value="${esc(c.hours || "")}" maxlength="80" placeholder="Mon–Fri, 9am–6pm (WAT)"></div>
        <span class="err" id="ciErr" hidden></span><div class="savebar"><button class="btn">Save contact details</button></div></form></section>

    <section class="panel" id="email"><div class="panel-h"><div><h2>Email sending ${connected ? `<span class="badge ok">Connected</span>` : `<span class="badge warn">Not set up</span>`}</h2><p>Sends sign-up codes, password reset codes and download links, using your free EmailJS account.</p></div></div>
      <form class="form" id="emForm" novalidate>
        <div class="two"><div class="field"><label for="emKey">Public key</label><input id="emKey" value="${esc(em.publicKey || "")}" placeholder="Account → General"></div>
          <div class="field"><label for="emSvc">Service ID</label><input id="emSvc" value="${esc(em.serviceId || "")}" placeholder="service_ab12cd"></div></div>
        <div class="two"><div class="field"><label for="emVerify">Sign-up code template ID</label><input id="emVerify" value="${esc(em.tpl_verify || "")}" placeholder="template_verify"></div>
          <div class="field"><label for="emReset">Password reset template ID</label><input id="emReset" value="${esc(em.tpl_reset || "")}" placeholder="template_reset"></div></div>
        <div class="field"><label for="emPurchase">Purchase / download links template ID</label><input id="emPurchase" value="${esc(em.tpl_purchase || "")}" placeholder="template_purchase"></div>
        <div class="field"><label for="emTest">Send a test email to</label><input id="emTest" type="email" value="${esc(c.email || "")}" placeholder="you@example.com"></div>
        <span class="err" id="emErr" hidden></span>
        <div class="savebar"><button class="btn">Save email settings</button><button type="button" class="btn ghost" id="emTestBtn">Send test email</button></div>
        <details><summary class="note" style="cursor:pointer;font-weight:700">How to set this up</summary><ol class="steps" style="margin-top:8px">
          <li>Create a free account at emailjs.com and add a Gmail service. Copy the Service ID.</li>
          <li>Copy your Public Key from Account → General.</li>
          <li>Create three templates (sign-up code, password reset, purchase). Set "To Email" to {{to_email}}. The README in your site files has the exact text.</li>
          <li>Paste the IDs here, save, then send a test email.</li></ol></details>
      </form></section>

    <section class="panel" id="admin"><div class="panel-h"><div><h2>Admin login</h2><p>The email and password you use on the Log in page to reach this dashboard.</p></div></div>
      <form class="form" id="adForm" novalidate>
        <div class="field"><label for="adEmail">Admin email</label><input id="adEmail" type="email" value="${esc(adm.email || "")}"><span class="ferr" data-for="adEmail"></span></div>
        <div class="field"><label for="adCur">Current password</label><div class="pw"><input id="adCur" type="password" autocomplete="current-password"><button type="button" data-peek="adCur">Show</button></div><span class="ferr" data-for="adCur"></span></div>
        <div class="two"><div class="field"><label for="adNew">New password</label><div class="pw"><input id="adNew" type="password" autocomplete="new-password"><button type="button" data-peek="adNew">Show</button></div><span class="ferr" data-for="adNew"></span></div>
          <div class="field"><label for="adNew2">Confirm new password</label><div class="pw"><input id="adNew2" type="password" autocomplete="new-password"><button type="button" data-peek="adNew2">Show</button></div><span class="ferr" data-for="adNew2"></span></div></div>
        <div class="savebar"><button class="btn">${T.icon("shield")}Update admin login</button></div>
        <div class="callout" id="cfgOut" hidden><div style="min-width:0;width:100%"><b>One more step so it works on every device</b>Replace <code>assets/js/config.js</code> on your host with this new file.
          <div class="savebar" style="margin-top:10px"><button type="button" class="btn sm" id="cfgDl">${T.icon("download")}Download config.js</button><button type="button" class="btn sm ghost" id="cfgCopy">${T.icon("copy")}Copy</button></div></div></div>
      </form></section>

    <section class="panel" id="backup"><div class="panel-h"><div><h2>Backup</h2><p>Your store data lives in this browser. Download a backup, and restore it here or in another browser.</p></div></div>
      <div class="savebar"><button class="btn" id="bkDl">${T.icon("download")}Download backup</button><button class="btn ghost" id="bkUp">${T.icon("upload")}Restore from backup</button><input type="file" id="bkFile" accept="application/json,.json" hidden></div>
      <p class="note" style="margin:12px 0 0">Restoring replaces the plugins, customers, orders and settings in this browser with the ones in the file.</p></section>
  </div>`;
  T.peek($("#content"));
  if (location.hash) setTimeout(() => document.querySelector(location.hash)?.scrollIntoView(), 50);

  $("#ciForm").onsubmit = (e) => {
    e.preventDefault(); const err = $("#ciErr"), v = (id) => $("#" + id).value.trim(); T.showErr(err, "");
    const d = { email: v("ciEmail"), whatsapp: v("ciWa"), instagram: v("ciIg"), youtube: v("ciYt"), hours: v("ciHours") };
    if (d.email && !T.emailOk(d.email)) return T.showErr(err, "That email doesn't look right.");
    if (d.whatsapp && d.whatsapp.replace(/\D/g, "").length < 8) return T.showErr(err, "Add the WhatsApp number with country code, like +234 801 234 5678.");
    for (const [k, l] of [["instagram", "Instagram"], ["youtube", "YouTube"]]) if (d[k] && !T.linkOk(d[k])) return T.showErr(err, `The ${l} link should start with https://`);
    try { T.db.set("settings", "contact", d); T.toast("Contact details saved"); } catch (x) { T.showErr(err, x.message); }
  };
  $("#emForm").onsubmit = (e) => {
    e.preventDefault(); const v = (id) => $("#" + id).value.trim();
    try { T.db.set("settings", "email", { publicKey: v("emKey"), serviceId: v("emSvc"), tpl_verify: v("emVerify"), tpl_reset: v("emReset"), tpl_purchase: v("emPurchase") }); T.toast("Email settings saved"); }
    catch (x) { T.showErr($("#emErr"), x.message); }
  };
  $("#emTestBtn").onclick = async () => {
    const err = $("#emErr"), to = $("#emTest").value.trim(); T.showErr(err, "");
    if (!T.emailOk(to)) return T.showErr(err, "Enter the email address to send the test to.");
    const b = $("#emTestBtn"); b.disabled = true; b.textContent = "Sending…";
    const res = await T.sendEmail("verify", { to_email: to, to_name: "there", code: "123456" });
    b.disabled = false; b.textContent = "Send test email";
    if (res.ok) T.toast(`Test email sent to ${to}. Check your inbox.`);
    else T.showErr(err, res.reason === "setup" ? "Save your public key, service ID and sign-up template ID first." : /fetch|network/i.test(res.detail || "") ? "Couldn't reach EmailJS. Check your internet connection and try again." : `EmailJS didn't accept it${res.detail ? ": " + res.detail.slice(0, 160) : ""}. Check the IDs and try again.`);
  };

  let cfgText = "";
  $("#adForm").onsubmit = async (e) => {
    e.preventDefault();
    const email = $("#adEmail").value.trim().toLowerCase(), cur = $("#adCur").value, nw = $("#adNew").value, a = T.adminCreds();
    let ok = T.fieldErr("adEmail", T.emailOk(email) ? (T.findCustomer(email) ? "A customer already uses this email." : "") : "Enter a valid email.");
    ok = T.fieldErr("adCur", cur ? "" : "Enter your current password.") && ok;
    ok = T.fieldErr("adNew", nw ? T.pwRule(nw) : "") && ok;
    ok = T.fieldErr("adNew2", nw && $("#adNew2").value !== nw ? "Passwords don't match." : "") && ok;
    if (!ok) return;
    if ((await T.hashPw(cur, a.salt)).hash !== a.hash) return T.fieldErr("adCur", "That's not the current admin password.");
    const h = nw ? await T.hashPw(nw) : { salt: a.salt, hash: a.hash };
    const creds = { email, salt: h.salt, hash: h.hash };
    try { T.db.set("settings", "admin", creds); } catch (x) { return T.toast(x.message); }
    cfgText = `/* ToonApp Hub settings.\n   The admin logs in on the normal Log in page with this email and their password.\n   Only a scrambled (hashed) form of the password is stored here. */\nwindow.TOON_CONFIG = {\n  storeName: ${JSON.stringify(T.storeName)},\n  admin: {\n    email: ${JSON.stringify(email)},\n    salt: ${JSON.stringify(h.salt)},\n    hash: ${JSON.stringify(h.hash)}\n  }\n};\n`;
    $("#cfgOut").hidden = false; $("#adCur").value = $("#adNew").value = $("#adNew2").value = "";
    T.toast("Admin login updated in this browser");
  };
  const download = (name, text, type) => { const a = document.createElement("a"); a.href = URL.createObjectURL(new Blob([text], { type })); a.download = name; document.body.appendChild(a); a.click(); setTimeout(() => { URL.revokeObjectURL(a.href); a.remove(); }, 500); };
  $("#cfgDl").onclick = () => download("config.js", cfgText, "text/javascript");
  $("#cfgCopy").onclick = () => T.copy(cfgText, "config.js copied");

  $("#bkDl").onclick = () => { let raw = "{}"; try { raw = localStorage.getItem("toonhub.db.v1") || "{}"; } catch (e) {} download(`toonapp-hub-backup-${new Date().toISOString().slice(0, 10)}.json`, raw, "application/json"); };
  $("#bkUp").onclick = () => $("#bkFile").click();
  $("#bkFile").onchange = (e) => {
    const f = e.target.files[0]; e.target.value = ""; if (!f) return;
    const r = new FileReader();
    r.onload = () => {
      try { const d = JSON.parse(r.result); if (!d || typeof d !== "object" || !d.products) throw 0; localStorage.setItem("toonhub.db.v1", JSON.stringify(d)); T.toast("Backup restored"); setTimeout(() => location.reload(), 700); }
      catch (x) { T.toast("That file isn't a ToonApp Hub backup."); }
    };
    r.readAsText(f);
  };
})();
