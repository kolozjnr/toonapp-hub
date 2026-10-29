/* Contact page: contact details from admin settings + message form. */
(function () {
  "use strict";
  const { $, esc } = T;
  const IC = {
    mail: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>`,
    wa: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"><path d="M4 20l1.4-4.2A8 8 0 1 1 8.4 18.7z"/><path d="M9 9.5c.5 2.5 2.5 4.5 5 5l1.2-1.3 2 1-.5 1.6c-4 .4-8.4-4-8-8l1.6-.5 1 2z" stroke-width="1.6"/></svg>`,
    ig: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1" fill="currentColor"/></svg>`,
    yt: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"><rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.5v5l4.5-2.5z" fill="currentColor"/></svg>`,
    clock: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/></svg>`
  };
  function renderCards() {
    const c = T.contact(), cards = [];
    if (c.email) cards.push(`<div class="ccard"><span class="ic" style="background:var(--yellow)">${IC.mail}</span><span class="tx"><small>Email</small><span class="v">${esc(c.email)}</span></span><button class="btn xs white" data-copy="${esc(c.email)}">Copy</button></div>`);
    if (c.whatsapp) cards.push(`<a class="ccard" href="https://wa.me/${c.whatsapp.replace(/\D/g, "")}" target="_blank" rel="noopener noreferrer"><span class="ic" style="background:var(--mint)">${IC.wa}</span><span class="tx"><small>WhatsApp</small><span class="v">${esc(c.whatsapp)}</span></span></a>`);
    if (T.linkOk(c.instagram)) cards.push(`<a class="ccard" href="${esc(c.instagram)}" target="_blank" rel="noopener noreferrer"><span class="ic" style="background:var(--pink)">${IC.ig}</span><span class="tx"><small>Instagram</small><span class="v">${esc(c.instagram.replace(/^https?:\/\/(www\.)?/, ""))}</span></span></a>`);
    if (T.linkOk(c.youtube)) cards.push(`<a class="ccard" href="${esc(c.youtube)}" target="_blank" rel="noopener noreferrer"><span class="ic" style="background:var(--red);color:#fff">${IC.yt}</span><span class="tx"><small>YouTube</small><span class="v">Watch all our tutorials</span></span></a>`);
    if (c.hours) cards.push(`<div class="ccard"><span class="ic" style="background:var(--soft);color:var(--fg)">${IC.clock}</span><span class="tx"><small>Support hours</small><span class="v">${esc(c.hours)}</span></span></div>`);
    $("#contactCards").innerHTML = cards.join("") || (T.isAdmin() ? `<p class="note">Add your email, WhatsApp and social links in Admin → Settings and they'll show here.</p>` : `<p class="note">Use the form and we'll get back to you by email.</p>`);
  }
  $("#contactCards").addEventListener("click", (e) => { const b = e.target.closest("[data-copy]"); if (b) T.copy(b.dataset.copy, "Email copied"); });
  const m = T.me();
  if (m) { $("#cName").value = `${m.first} ${m.last}`; $("#cEmail").value = m.email; }
  $("#contactForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const err = $("#cErr"); T.showErr(err, "");
    if (T.isAdmin()) return T.showErr(err, "You're the admin. Customers send messages here, and you read them in Admin → Messages.");
    const name = $("#cName").value.trim(), email = $("#cEmail").value.trim(), text = $("#cMsg").value.trim();
    if (!name) return T.showErr(err, "Add your name.");
    if (!T.emailOk(email)) return T.showErr(err, "Add a valid email so we can reply.");
    if (text.length < 5) return T.showErr(err, "Write your message.");
    try {
      T.db.add("messages", { name, email: email.toLowerCase(), topic: $("#cTopic").value, text, read: false, at: Date.now(), cid: T.me()?.id || null });
      $("#cMsg").value = ""; T.toast("Message sent! We'll reply by email.");
    } catch (x) { T.showErr(err, x.message || "Couldn't send just now. Try again."); }
  });
  T.db.on(renderCards);
  renderCards();
})();
