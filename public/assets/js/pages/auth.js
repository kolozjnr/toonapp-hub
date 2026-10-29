/* Log in, register (with email code), forgot password and reset password pages. */
(function () {
  "use strict";
  const { $, esc, url } = T;
  const page = document.body.dataset.page;
  const next = T.safeNext(T.param("next"));
  T.peek();

  /* carry ?next= between auth pages */
  if (next) T.$$('a[href="login.html"], a[href="register.html"]').forEach((a) => (a.href = a.getAttribute("href") + "?next=" + encodeURIComponent(next)));
  const afterLogin = (role) => location.assign(url(role === "admin" ? (next && next.startsWith("admin/") ? next : "admin/dashboard") : next && !next.startsWith("admin/") ? next : "customer/dashboard"));

  /* already logged in? skip the form */
  if ((page === "login" || page === "register") && (T.me() || T.isAdmin())) { afterLogin(T.isAdmin() ? "admin" : "customer"); return; }

  /* ---------- log in ---------- */
  if (page === "login") {
    $("#loginForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = $("#lEmail").value.trim(), pw = $("#lPass").value, err = $("#loginErr");
      let ok = T.fieldErr("lEmail", T.emailOk(email) ? "" : "Enter the email you signed up with.");
      ok = T.fieldErr("lPass", pw ? "" : "Enter your password.") && ok;
      if (!ok) return;
      const btn = $("#loginBtn"); btn.disabled = true; btn.textContent = "Logging in…"; T.showErr(err, "");
      try {
        const r = await T.login(email, pw);
        if (r.error) { T.showErr(err, r.error); return; }
        afterLogin(r.role);
      } catch (x) { T.showErr(err, "Couldn't log in just now. Try again."); }
      finally { btn.disabled = false; btn.textContent = "Log in"; }
    });
    $("#forgotLink").addEventListener("click", () => { try { sessionStorage.setItem("toonhub.forgotEmail", $("#lEmail").value.trim()); } catch (e) {} });
  }

  /* ---------- register ---------- */
  if (page === "register") {
    $("#rCountry").innerHTML = T.countryOptions("");
    let pending = null, code = null;
    const step = (verify) => {
      $("#signupForm").hidden = verify; $("#verifyBox").hidden = !verify;
      $("#authTitle").textContent = verify ? "Confirm your email" : "Create your account";
      $("#authNote").textContent = verify ? "One last step before your account is ready." : "It takes less than a minute.";
    };
    $("#signupForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const v = (id) => $("#" + id).value.trim(), pw = $("#rPass").value, email = v("rEmail").toLowerCase();
      let ok = true;
      ok = T.fieldErr("rFirst", v("rFirst") ? "" : "Enter your first name.") && ok;
      ok = T.fieldErr("rLast", v("rLast") ? "" : "Enter your last name.") && ok;
      ok = T.fieldErr("rEmail", !T.emailOk(email) ? "Enter a valid email, like you@example.com." : T.findCustomer(email) || email === String(T.adminCreds().email || "").toLowerCase() ? "There's already an account with this email. Log in instead." : "") && ok;
      ok = T.fieldErr("rPass", T.pwRule(pw)) && ok;
      ok = T.fieldErr("rPass2", $("#rPass2").value !== pw ? "Passwords don't match." : !$("#rPass2").value ? "Type your password again." : "") && ok;
      ok = T.fieldErr("rCountry", v("rCountry") ? "" : "Choose your country.") && ok;
      if (!ok) return;
      pending = { first: v("rFirst"), last: v("rLast"), email, country: v("rCountry"), news: $("#rNews").checked, pw };
      const btn = $("#signupBtn"); btn.disabled = true; btn.textContent = "Sending code…";
      await sendCode();
      btn.disabled = false; btn.textContent = "Create account";
    });
    async function sendCode() {
      code = { value: T.newCode(), exp: Date.now() + 10 * 60e3, tries: 0 };
      step(true); T.showErr($("#vErr"), ""); T.fieldErr("vCode", ""); $("#vCode").value = "";
      $("#vSentTo").innerHTML = `Sending a 6-digit code to <b>${esc(pending.email)}</b>…`;
      const res = await T.sendEmail("verify", { to_email: pending.email, to_name: pending.first, code: code.value });
      $("#vSentTo").innerHTML = res.ok ? `We sent a 6-digit code to <b>${esc(pending.email)}</b>. Enter it below. It expires in 10 minutes.` : `Confirm <b>${esc(pending.email)}</b> with the 6-digit code.`;
      const demo = $("#vDemo"); demo.hidden = res.ok; demo.innerHTML = T.codeNote(res, code.value);
      T.cooldown($("#vResend"), 30);
      setTimeout(() => $("#vCode").focus(), 60);
    }
    $("#vResend").onclick = sendCode;
    $("#vBack").onclick = () => { step(false); setTimeout(() => $("#rEmail").focus(), 50); };
    $("#vCode").addEventListener("input", (e) => { e.target.value = e.target.value.replace(/\D/g, "").slice(0, 6); if (e.target.value.length === 6) $("#vBtn").click(); });
    $("#vBtn").onclick = async () => {
      const entered = $("#vCode").value.trim();
      if (!pending || !code) return;
      if (Date.now() > code.exp) return T.fieldErr("vCode", "This code has expired. Tap Resend code for a new one.");
      if (code.tries >= 5) return T.fieldErr("vCode", "Too many tries. Tap Resend code for a new one.");
      if (entered !== code.value) { code.tries++; return T.fieldErr("vCode", entered.length < 6 ? "Enter all 6 digits." : "That code isn't right. Check your email and try again."); }
      T.fieldErr("vCode", "");
      const btn = $("#vBtn"); btn.disabled = true; btn.textContent = "Creating account…";
      try {
        const pw = await T.hashPw(pending.pw);
        const id = T.db.add("customers", { first: pending.first, last: pending.last, email: pending.email, country: pending.country, news: pending.news, pw, verified: true, joined: Date.now(), hidden: [] });
        T.setSession({ role: "customer", id });
        T.toast(`Email confirmed. Welcome to ${T.storeName}, ${pending.first}!`);
        setTimeout(() => afterLogin("customer"), 500);
      } catch (x) { T.showErr($("#vErr"), x.message || "Couldn't create your account. Try again."); btn.disabled = false; btn.textContent = "Confirm email and create account"; }
    };
  }

  /* ---------- forgot password ---------- */
  if (page === "forgot") {
    try { const e = sessionStorage.getItem("toonhub.forgotEmail"); if (e) $("#fEmail").value = e; } catch (x) {}
    $("#forgotForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = $("#fEmail").value.trim().toLowerCase(), err = $("#fErr"); T.showErr(err, "");
      if (!T.fieldErr("fEmail", T.emailOk(email) ? "" : "Enter the email you signed up with.")) return;
      const c = T.findCustomer(email);
      if (!c) return T.showErr(err, "We couldn't find an account with that email. Check the spelling or create an account.");
      const btn = $("#fBtn"); btn.disabled = true; btn.textContent = "Sending code…";
      const code = T.newCode();
      const res = await T.sendEmail("reset", { to_email: email, to_name: c.first, code });
      try { sessionStorage.setItem("toonhub.reset", JSON.stringify({ email, code, exp: Date.now() + 10 * 60e3, tries: 0, sent: res.ok, reason: res.reason || "" })); } catch (x) {}
      location.assign(url("reset-password.html"));
    });
  }

  /* ---------- reset password ---------- */
  if (page === "reset") {
    const load = () => { try { return JSON.parse(sessionStorage.getItem("toonhub.reset")); } catch (e) { return null; } };
    const save = (r) => { try { sessionStorage.setItem("toonhub.reset", JSON.stringify(r)); } catch (e) {} };
    let R = load();
    if (!R) { $("#resetForm").innerHTML = `<p class="verify-text">Start by entering your email so we can send you a code.</p><a class="btn pink" href="${url("forgot-password.html")}">Send me a reset code</a>`; return; }
    const show = () => {
      $("#rsSentTo").innerHTML = R.sent ? `We sent a 6-digit code to <b>${esc(R.email)}</b>. It expires in 10 minutes.` : `Reset the password for <b>${esc(R.email)}</b>.`;
      const demo = $("#rsDemo"); demo.hidden = R.sent; demo.innerHTML = T.codeNote({ ok: R.sent, reason: R.reason }, R.code);
    };
    show(); setTimeout(() => $("#rsCode").focus(), 60);
    $("#rsCode").addEventListener("input", (e) => (e.target.value = e.target.value.replace(/\D/g, "").slice(0, 6)));
    $("#rsResend").onclick = async () => {
      const c = T.findCustomer(R.email); if (!c) return;
      R.code = T.newCode(); R.exp = Date.now() + 10 * 60e3; R.tries = 0;
      const res = await T.sendEmail("reset", { to_email: R.email, to_name: c.first, code: R.code });
      R.sent = res.ok; R.reason = res.reason || ""; save(R); show(); T.cooldown($("#rsResend"), 30);
      T.toast(res.ok ? "New code sent" : "New code ready");
    };
    T.cooldown($("#rsResend"), 30);
    $("#resetForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const code = $("#rsCode").value.trim(), pw = $("#rsPass").value, err = $("#rsErr"); T.showErr(err, "");
      let ok = true;
      if (Date.now() > R.exp) ok = T.fieldErr("rsCode", "This code has expired. Tap Resend code.") && false;
      else if (R.tries >= 5) ok = T.fieldErr("rsCode", "Too many tries. Tap Resend code.") && false;
      else if (code !== R.code) { R.tries++; save(R); ok = T.fieldErr("rsCode", code.length < 6 ? "Enter all 6 digits." : "That code isn't right.") && false; }
      else T.fieldErr("rsCode", "");
      ok = T.fieldErr("rsPass", T.pwRule(pw)) && ok;
      ok = T.fieldErr("rsPass2", $("#rsPass2").value !== pw ? "Passwords don't match." : "") && ok;
      if (!ok) return;
      const c = T.findCustomer(R.email);
      if (!c) return T.showErr(err, "That account no longer exists.");
      const btn = $("#rsBtn"); btn.disabled = true; btn.textContent = "Saving…";
      try {
        T.db.update("customers", c.id, { pw: await T.hashPw(pw) });
        try { sessionStorage.removeItem("toonhub.reset"); } catch (x) {}
        T.setSession({ role: "customer", id: c.id });
        T.toast(`Password updated. Welcome back, ${c.first}!`);
        setTimeout(() => location.assign(url("customer/dashboard.html")), 500);
      } catch (x) { T.showErr(err, x.message || "Couldn't save just now. Try again."); btn.disabled = false; btn.textContent = "Save new password"; }
    });
  }
})();
