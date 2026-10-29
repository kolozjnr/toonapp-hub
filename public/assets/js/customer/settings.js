/* Customer dashboard: profile, email preferences, password. */
(function () {
  "use strict";
  if (!T.dash.ready) return;
  const { $, esc } = T;
  const c = T.me();
  $("#content").innerHTML = `<div class="grid2">
    <section class="panel"><div class="panel-h"><div><h2>Profile</h2><p>Your name and country.</p></div></div>
      <form class="form" id="profForm" novalidate>
        <div class="two"><div class="field"><label for="sFirst">First name</label><input id="sFirst" maxlength="40" value="${esc(c.first)}"><span class="ferr" data-for="sFirst"></span></div>
          <div class="field"><label for="sLast">Last name</label><input id="sLast" maxlength="40" value="${esc(c.last)}"><span class="ferr" data-for="sLast"></span></div></div>
        <div class="field"><label for="sEmail">Email</label><input id="sEmail" value="${esc(c.email)}" readonly><small>This is the email you log in with. To use a different one, create a new account.</small></div>
        <div class="field"><label for="sCountry">Country</label><select id="sCountry">${T.countryOptions(c.country)}</select></div>
        <div class="savebar"><button class="btn">Save profile</button><span class="note">Member since ${T.fdate(c.joined)}</span></div>
      </form></section>
    <section class="panel"><div class="panel-h"><div><h2>Change password</h2><p>Use at least 8 characters with a number.</p></div></div>
      <form class="form" id="pwForm" novalidate>
        <div class="field"><label for="pCur">Current password</label><div class="pw"><input id="pCur" type="password" autocomplete="current-password"><button type="button" data-peek="pCur">Show</button></div><span class="ferr" data-for="pCur"></span></div>
        <div class="field"><label for="pNew">New password</label><div class="pw"><input id="pNew" type="password" autocomplete="new-password"><button type="button" data-peek="pNew">Show</button></div><span class="ferr" data-for="pNew"></span></div>
        <div class="field"><label for="pNew2">Confirm new password</label><div class="pw"><input id="pNew2" type="password" autocomplete="new-password"><button type="button" data-peek="pNew2">Show</button></div><span class="ferr" data-for="pNew2"></span></div>
        <div class="savebar"><button class="btn">Update password</button></div>
      </form></section>
    <section class="panel"><div class="panel-h"><div><h2>Email preferences</h2><p>Choose what we send you.</p></div></div>
      <label class="toggle"><input type="checkbox" id="sNews" ${c.news !== false ? "checked" : ""}> Newsletter: plugin updates and new releases</label>
      <p class="note" style="margin:12px 0 0">Order receipts and download links are always sent, so you never lose a purchase.</p></section>
    <section class="panel"><div class="panel-h"><div><h2>Sign out</h2><p>Log out of ToonApp Hub on this device.</p></div></div>
      <button class="btn danger ghost" id="logoutBtn">${T.icon("out")}Log out</button></section>
  </div>`;
  T.peek($("#content"));
  $("#profForm").onsubmit = (e) => {
    e.preventDefault();
    const f = $("#sFirst").value.trim(), l = $("#sLast").value.trim();
    let ok = T.fieldErr("sFirst", f ? "" : "Enter your first name.");
    ok = T.fieldErr("sLast", l ? "" : "Enter your last name.") && ok;
    if (!ok) return;
    try { T.db.update("customers", c.id, { first: f, last: l, country: $("#sCountry").value }); T.toast("Profile saved"); setTimeout(() => location.reload(), 600); }
    catch (x) { T.toast(x.message || "Couldn't save."); }
  };
  $("#pwForm").onsubmit = async (e) => {
    e.preventDefault();
    const me = T.me(), cur = $("#pCur").value, nw = $("#pNew").value;
    let ok = T.fieldErr("pCur", cur ? "" : "Enter your current password.");
    ok = T.fieldErr("pNew", T.pwRule(nw)) && ok;
    ok = T.fieldErr("pNew2", $("#pNew2").value !== nw ? "Passwords don't match." : "") && ok;
    if (!ok) return;
    const h = await T.hashPw(cur, me.pw.salt);
    if (h.hash !== me.pw.hash) return T.fieldErr("pCur", "That's not your current password.");
    try { T.db.update("customers", me.id, { pw: await T.hashPw(nw) }); e.target.reset(); T.toast("Password updated"); }
    catch (x) { T.toast(x.message || "Couldn't save."); }
  };
  $("#sNews").onchange = (e) => { try { T.db.update("customers", c.id, { news: e.target.checked }); T.toast(e.target.checked ? "You're subscribed to the newsletter" : "You've unsubscribed"); } catch (x) { e.target.checked = !e.target.checked; } };
  $("#logoutBtn").onclick = () => T.logout();
})();
