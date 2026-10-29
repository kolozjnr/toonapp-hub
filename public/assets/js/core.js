/* ToonApp Hub core: storage, accounts, cart, email and shared helpers.
   Loaded on every page, after config.js. Exposes one global: T. */
(function () {
  "use strict";
  const CFG = window.TOON_CONFIG || {};
  const T = (window.T = {});
  T.storeName = CFG.storeName || "ToonApp Hub";

  /* ---------- paths ---------- */
  T.root = document.body.dataset.root || "";
  T.url = (p) => T.root + p;
  T.go = (p) => location.assign(T.url(p));
  T.param = (k) => new URLSearchParams(location.search).get(k);

  /* ---------- small helpers ---------- */
  T.$ = (s, el = document) => el.querySelector(s);
  T.$$ = (s, el = document) => [...el.querySelectorAll(s)];
  T.esc = (s) => String(s ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
  T.money = (n) => (!n ? "Free" : "$" + (Math.round(n * 100) / 100).toFixed(n % 1 ? 2 : 0));
  T.money2 = (n) => "$" + (n || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  T.fdate = (t) => (t ? new Date(t).toLocaleDateString("en-GB", { day: "numeric", month: "short", year: "numeric" }) : "");
  T.ago = (t) => {
    const s = (Date.now() - t) / 1000;
    if (s < 60) return "just now";
    if (s < 3600) return Math.floor(s / 60) + " min ago";
    if (s < 86400) return Math.floor(s / 3600) + " h ago";
    if (s < 86400 * 7) return Math.floor(s / 86400) + " d ago";
    return T.fdate(t);
  };
  T.emailOk = (e) => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(e || "");
  T.ytOk = (u) => /^https?:\/\/(www\.|m\.)?(youtube\.com|youtu\.be)\//i.test(u || "");
  T.linkOk = (u) => /^https?:\/\/\S+$/.test(u || "");
  T.pwRule = (pw) => (pw.length < 8 ? "Use at least 8 characters." : !/\d/.test(pw) || !/[a-z]/i.test(pw) ? "Mix letters and at least one number." : "");
  T.newId = () => Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
  T.reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;

  T.HOSTS = { AE: ["After Effects", "#6B4EFF"], PR: ["Premiere Pro", "#B04BD8"], DR: ["DaVinci Resolve", "#E3652B"], C4D: ["Cinema 4D", "#1F7AE0"], BL: ["Blender", "#E8871E"], FCP: ["Final Cut Pro", "#2F2F3A"] };
  T.hostTag = (h) => (T.HOSTS[h] ? `<span class="host" style="background:${T.HOSTS[h][1]}" title="${T.HOSTS[h][0]}">${h}</span>` : "");
  T.hostNames = (hs) => (hs || []).map((h) => T.HOSTS[h]?.[0]).filter(Boolean).join(", ");
  T.starsHtml = (v) => `<span class="stars" aria-label="${(v || 0).toFixed(1)} out of 5">${[1, 2, 3, 4, 5].map((i) => `<i class="${v >= i - 0.25 ? "" : "off"}">★</i>`).join("")}</span>`;
  T.COUNTRIES = "Afghanistan|Albania|Algeria|Andorra|Angola|Antigua and Barbuda|Argentina|Armenia|Australia|Austria|Azerbaijan|Bahamas|Bahrain|Bangladesh|Barbados|Belarus|Belgium|Belize|Benin|Bhutan|Bolivia|Bosnia and Herzegovina|Botswana|Brazil|Brunei|Bulgaria|Burkina Faso|Burundi|Cabo Verde|Cambodia|Cameroon|Canada|Central African Republic|Chad|Chile|China|Colombia|Comoros|Congo|Costa Rica|Côte d'Ivoire|Croatia|Cuba|Cyprus|Czechia|DR Congo|Denmark|Djibouti|Dominica|Dominican Republic|Ecuador|Egypt|El Salvador|Equatorial Guinea|Eritrea|Estonia|Eswatini|Ethiopia|Fiji|Finland|France|Gabon|Gambia|Georgia|Germany|Ghana|Greece|Grenada|Guatemala|Guinea|Guinea-Bissau|Guyana|Haiti|Honduras|Hungary|Iceland|India|Indonesia|Iran|Iraq|Ireland|Israel|Italy|Jamaica|Japan|Jordan|Kazakhstan|Kenya|Kiribati|Kuwait|Kyrgyzstan|Laos|Latvia|Lebanon|Lesotho|Liberia|Libya|Liechtenstein|Lithuania|Luxembourg|Madagascar|Malawi|Malaysia|Maldives|Mali|Malta|Marshall Islands|Mauritania|Mauritius|Mexico|Micronesia|Moldova|Monaco|Mongolia|Montenegro|Morocco|Mozambique|Myanmar|Namibia|Nauru|Nepal|Netherlands|New Zealand|Nicaragua|Niger|Nigeria|North Korea|North Macedonia|Norway|Oman|Pakistan|Palau|Palestine|Panama|Papua New Guinea|Paraguay|Peru|Philippines|Poland|Portugal|Qatar|Romania|Russia|Rwanda|Saint Kitts and Nevis|Saint Lucia|Saint Vincent and the Grenadines|Samoa|San Marino|São Tomé and Príncipe|Saudi Arabia|Senegal|Serbia|Seychelles|Sierra Leone|Singapore|Slovakia|Slovenia|Solomon Islands|Somalia|South Africa|South Korea|South Sudan|Spain|Sri Lanka|Sudan|Suriname|Sweden|Switzerland|Syria|Taiwan|Tajikistan|Tanzania|Thailand|Timor-Leste|Togo|Tonga|Trinidad and Tobago|Tunisia|Turkey|Turkmenistan|Tuvalu|Uganda|Ukraine|United Arab Emirates|United Kingdom|United States|Uruguay|Uzbekistan|Vanuatu|Vatican City|Venezuela|Vietnam|Yemen|Zambia|Zimbabwe".split("|");
  T.countryOptions = (sel) => `<option value="">Choose your country</option>` + T.COUNTRIES.map((c) => `<option${c === sel ? " selected" : ""}>${T.esc(c)}</option>`).join("");

  /* ---------- storage (this browser) ---------- */
  const KEY = "toonhub.db.v1";
  const listeners = new Set();
  const read = () => { try { return JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { return {}; } };
  let data = read();
  function persist() {
    try { localStorage.setItem(KEY, JSON.stringify(data)); }
    catch (e) { data = read(); throw { code: "quota", message: "Storage is full. Delete some plugins or use smaller images, then try again." }; }
    listeners.forEach((fn) => { try { fn(); } catch (x) { console.error(x); } });
  }
  addEventListener("storage", (e) => { if (e.key === KEY) { data = read(); listeners.forEach((fn) => fn()); } });
  const clone = (o) => JSON.parse(JSON.stringify(o));
  T.db = {
    all(col) { const c = data[col] || {}; return Object.keys(c).map((id) => ({ id, ...c[id] })); },
    get(col, id) { const c = data[col] || {}; return c[id] ? { id, ...c[id] } : null; },
    set(col, id, doc) { const d = clone(doc); delete d.id; (data[col] = data[col] || {})[id] = d; persist(); return id; },
    update(col, id, patch) { const c = (data[col] = data[col] || {}); if (!c[id]) throw { code: "missing", message: "That item no longer exists." }; c[id] = { ...c[id], ...clone(patch) }; persist(); },
    del(col, id) { if (data[col]) delete data[col][id]; persist(); },
    add(col, doc) { const id = T.newId(); this.set(col, id, doc); return id; },
    on(fn) { listeners.add(fn); return () => listeners.delete(fn); },
    size() { try { return (localStorage.getItem(KEY) || "").length; } catch (e) { return 0; } }
  };

  /* first visit: two example plugins so the shelf isn't empty */
  if (!data.meta || !data.meta.seeded) {
    const now = Date.now();
    data.products = data.products || {};
    if (!Object.keys(data.products).length) {
      data.products["example-1"] = { name: "Example: Squash & Stretch", tagline: "Sample listing. Edit it into your own plugin or delete it.", description: "This is an example so you can see how a plugin looks on your shelf. Open the admin dashboard, then Plugins, then Edit to replace it with your own plugin.", features: ["Your first feature goes here", "Your second feature goes here", "One feature per line in the upload form"], price: 29, was: 39, category: "Plugin", badge: "Sale", hosts: ["AE"], version: "1.0.0", compat: "After Effects 2024 and newer", live: true, cover: null, youtube: null, shots: [], createdAt: now, updatedAt: now };
      data.products["example-2"] = { name: "Example: Bounce Presets", tagline: "Sample free listing. Replace it with your own.", description: "An example of a free plugin listing. Set the price to 0 to give a plugin away for free.", features: ["Shows how a free plugin looks", "Edit or delete it from your dashboard"], price: 0, was: null, category: "Preset pack", badge: "Free", hosts: ["AE", "PR"], version: "1.0.0", compat: "After Effects and Premiere Pro 2023+", live: true, cover: null, youtube: null, shots: [], createdAt: now - 1000, updatedAt: now - 1000 };
    }
    data.meta = { seeded: true };
    try { localStorage.setItem(KEY, JSON.stringify(data)); } catch (e) {}
  }

  /* ---------- passwords + sessions ---------- */
  T.hashPw = async function (pw, saltB64) {
    const enc = new TextEncoder();
    const salt = saltB64 ? Uint8Array.from(atob(saltB64), (c) => c.charCodeAt(0)) : crypto.getRandomValues(new Uint8Array(16));
    const key = await crypto.subtle.importKey("raw", enc.encode(pw), "PBKDF2", false, ["deriveBits"]);
    const bits = await crypto.subtle.deriveBits({ name: "PBKDF2", salt, iterations: 150000, hash: "SHA-256" }, key, 256);
    return { salt: btoa(String.fromCharCode(...salt)), hash: btoa(String.fromCharCode(...new Uint8Array(bits))) };
  };
  const SKEY = "toonhub.session";
  T.session = () => { try { return JSON.parse(localStorage.getItem(SKEY)) || null; } catch (e) { return null; } };
  T.setSession = (s) => { try { localStorage.setItem(SKEY, JSON.stringify({ ...s, at: Date.now() })); } catch (e) {} };
  T.logout = (to) => { try { localStorage.removeItem(SKEY); } catch (e) {} location.assign(T.url(to || "index.html")); };
  T.adminCreds = () => { const o = T.db.get("settings", "admin"); return o && o.hash ? o : CFG.admin || {}; };
  T.isAdmin = () => T.session()?.role === "admin";
  T.me = () => { const s = T.session(); return s && s.role === "customer" ? T.db.get("customers", s.id) : null; };
  T.findCustomer = (email) => T.db.all("customers").find((c) => c.email === String(email).trim().toLowerCase()) || null;
  T.login = async function (email, pw) {
    email = String(email).trim().toLowerCase();
    const a = T.adminCreds();
    if (a.email && email === String(a.email).toLowerCase()) {
      const h = await T.hashPw(pw, a.salt);
      if (h.hash !== a.hash) return { error: "Wrong password. Try again." };
      T.setSession({ role: "admin" });
      return { role: "admin" };
    }
    const c = T.findCustomer(email);
    if (!c) return { error: "There's no account with that email. Check the spelling or create an account." };
    const h = await T.hashPw(pw, c.pw.salt);
    if (h.hash !== c.pw.hash) return { error: "Wrong password. Try again, or reset it." };
    T.setSession({ role: "customer", id: c.id });
    return { role: "customer" };
  };
  T.safeNext = (n) => (n && /^[a-z0-9\-_/]+(\?[\w=&%.-]*)?(#[\w-]*)?$/i.test(n) && !n.startsWith("/") ? n : null);
  T.requireCustomer = () => { if (T.me()) return true; location.replace(T.url("login?next=" + encodeURIComponent(T.here()))); return false; };
  T.requireAdmin = () => { if (T.isAdmin()) return true; location.replace(T.url("login?next=" + encodeURIComponent(T.here()))); return false; };
  T.here = () => { const parts = location.pathname.split("/").filter(Boolean); const depth = (T.root.match(/\.\.\//g) || []).length; const p = parts.slice(-(depth + 1)).join("/") || ""; return p + location.search; };

  /* ---------- cart ---------- */
  const CKEY = "toonhub.cart";
  T.cart = () => { try { const c = JSON.parse(localStorage.getItem(CKEY) || "[]"); return Array.isArray(c) ? c.filter((id) => { const p = T.db.get("products", id); return p && p.live !== false; }) : []; } catch (e) { return []; } };
  T.setCart = (ids) => { try { localStorage.setItem(CKEY, JSON.stringify([...new Set(ids)])); } catch (e) {} document.dispatchEvent(new CustomEvent("cart")); };
  T.addToCart = (id) => { const c = T.cart(); if (!c.includes(id)) T.setCart([...c, id]); };
  T.removeFromCart = (id) => T.setCart(T.cart().filter((x) => x !== id));

  /* ---------- store data ---------- */
  T.products = () => T.db.all("products").sort((a, b) => (b.createdAt || 0) - (a.createdAt || 0));
  T.liveProducts = () => T.products().filter((p) => p.live !== false);
  T.product = (id) => T.db.get("products", id);
  T.reviews = (pid) => T.db.all("reviews").filter((r) => !pid || r.pid === pid).sort((a, b) => (b.at || 0) - (a.at || 0));
  T.ratingOf = (pid) => { const rs = T.reviews(pid); return { n: rs.length, avg: rs.length ? rs.reduce((s, r) => s + r.stars, 0) / rs.length : 0, list: rs }; };
  T.orders = (cid) => T.db.all("orders").filter((o) => !cid || o.cid === cid).sort((a, b) => b.at - a.at);
  T.owns = (cid, pid) => T.orders(cid).some((o) => (o.items || []).some((i) => i.id === pid));
  T.library = (cid) => {
    const seen = new Map();
    T.orders(cid).slice().reverse().forEach((o) => (o.items || []).forEach((i) => { if (!seen.has(i.id)) seen.set(i.id, { id: i.id, name: i.name, at: o.at, order: o.number }); }));
    return [...seen.values()].sort((a, b) => b.at - a.at);
  };
  T.downloadUrl = (pid) => { const f = T.db.get("files", pid); return f && T.linkOk(f.url) ? f.url : ""; };
  T.updatesFor = (c) => {
    if (!c) return [];
    const hidden = new Set(c.hidden || []);
    return T.db.all("news").filter((n) => !hidden.has(n.id) && (n.audience !== "buyers" || (n.plugin && T.owns(c.id, n.plugin)))).sort((a, b) => b.at - a.at);
  };
  T.unreadFor = (c) => (c ? T.updatesFor(c).filter((n) => n.at > (c.seenNews || c.joined || 0)).length : 0);
  T.contact = () => T.db.get("settings", "contact") || {};
  T.recordVisit = () => {
    if (T.isAdmin()) return;
    try { const last = +sessionStorage.getItem("toonhub.visit") || 0; if (Date.now() - last < 30 * 60e3) return; sessionStorage.setItem("toonhub.visit", String(Date.now())); } catch (e) {}
    let vid; try { vid = localStorage.getItem("toonhub.vid") || "v" + T.newId(); localStorage.setItem("toonhub.vid", vid); } catch (e) { vid = "v-anon"; }
    const d = T.db.get("visits", vid) || {}, day = new Date().toISOString().slice(0, 10), cutoff = new Date(Date.now() - 60 * 864e5).toISOString().slice(0, 10), days = {};
    Object.entries(d.days || {}).forEach(([k, v]) => { if (k >= cutoff) days[k] = v; });
    days[day] = (days[day] || 0) + 1;
    try { T.db.set("visits", vid, { count: (d.count || 0) + 1, days, first: d.first || Date.now(), last: Date.now() }); } catch (e) {}
  };

  /* ---------- email (EmailJS) ---------- */
  T.emailCfg = () => T.db.get("settings", "email") || {};
  T.sendEmail = async function (kind, params) {
    const c = T.emailCfg(), tpl = c["tpl_" + kind];
    if (!c.publicKey || !c.serviceId || !tpl) return { ok: false, reason: "setup" };
    try {
      const r = await fetch("https://api.emailjs.com/api/v1.0/email/send", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ service_id: c.serviceId, template_id: tpl, user_id: c.publicKey, template_params: { store_name: T.storeName, ...params } }) });
      if (!r.ok) return { ok: false, reason: "failed", detail: await r.text().catch(() => "") };
      return { ok: true };
    } catch (e) { return { ok: false, reason: "failed", detail: String((e && e.message) || e) }; }
  };
  T.newCode = () => String(100000 + (crypto.getRandomValues(new Uint32Array(1))[0] % 900000));
  T.codeNote = (res, code) => (res.ok ? "" : res.reason === "setup" ? `Email sending isn't switched on for this store yet, so here's your code:<b>${code}</b>` : `We couldn't send the email just now. Here's your code so you can continue:<b>${code}</b>`);
  T.sendPurchaseEmail = async function (order, customer) {
    const links = order.items.map((i) => ({ i, url: T.downloadUrl(i.id) }));
    return T.sendEmail("purchase", {
      to_email: customer.email, to_name: customer.first, order_id: order.number, total: T.money(order.total),
      items: links.map(({ i, url }) => `${i.name}: ${url || "download link coming soon, check My plugins"}`).join("\n"),
      items_html: links.map(({ i, url }) => `<p><b>${T.esc(i.name)}</b><br>${url ? `<a href="${T.esc(url)}">Download ${T.esc(i.name)}</a>` : "Download link coming soon. Check My plugins on the store."}</p>`).join("")
    });
  };

  /* ---------- cover art + images ---------- */
  T.hueOf = (s) => { let h = 0; for (const c of String(s)) h = (h * 31 + c.charCodeAt(0)) % 360; return h; };
  T.artCanvas = (p) => {
    const cv = document.createElement("canvas"); cv.width = 640; cv.height = 400;
    const c = cv.getContext("2d"), H = T.hueOf(p.name || "x");
    c.fillStyle = `hsl(${H} 90% 72%)`; c.fillRect(0, 0, 640, 400);
    c.fillStyle = `hsl(${(H + 40) % 360} 95% 80%)`;
    for (let y = 0; y < 400; y += 26) for (let x = ((y / 26) % 2) * 13; x < 640; x += 26) { c.beginPath(); c.arc(x, y, 4, 0, 7); c.fill(); }
    c.lineWidth = 10; c.strokeStyle = "#1B1530"; c.fillStyle = `hsl(${(H + 180) % 360} 85% 65%)`;
    c.beginPath(); c.arc(320, 215, 110, 0, 7); c.fill(); c.stroke();
    [[280, 195], [360, 195]].forEach(([x, y]) => { c.fillStyle = "#fff"; c.beginPath(); c.ellipse(x, y, 22, 26, 0, 0, 7); c.fill(); c.lineWidth = 6; c.stroke(); c.fillStyle = "#1B1530"; c.beginPath(); c.arc(x + 4, y + 4, 10, 0, 7); c.fill(); });
    c.lineWidth = 8; c.beginPath(); c.arc(320, 240, 34, 0.2 * Math.PI, 0.8 * Math.PI); c.stroke();
    return cv;
  };
  T.coverEl = (p) => { if (p && p.cover) { const i = new Image(); i.src = p.cover; i.alt = ""; i.loading = "lazy"; return i; } return T.artCanvas(p || {}); };
  T.compressImage = (file, maxLen = 160000) => new Promise((res, rej) => {
    if (!file || !file.type.startsWith("image/")) return rej(new Error("type"));
    const img = new Image(), url = URL.createObjectURL(file);
    img.onload = () => {
      let w = Math.min(1100, img.width), h = Math.round((img.height * w) / img.width);
      const cv = document.createElement("canvas"), c = cv.getContext("2d");
      const draw = () => { cv.width = w; cv.height = h; c.fillStyle = "#fff"; c.fillRect(0, 0, w, h); c.drawImage(img, 0, 0, w, h); };
      draw(); let q = 0.82, out = cv.toDataURL("image/jpeg", q);
      while (out.length > maxLen && q > 0.45) { q -= 0.08; out = cv.toDataURL("image/jpeg", q); }
      if (out.length > maxLen) { w = 760; h = Math.round((img.height * w) / img.width); draw(); out = cv.toDataURL("image/jpeg", 0.7); }
      URL.revokeObjectURL(url); res(out);
    };
    img.onerror = () => { URL.revokeObjectURL(url); rej(new Error("read")); };
    img.src = url;
  });

  /* ---------- UI helpers ---------- */
  T.toast = (m) => {
    let t = T.$("#toast");
    if (!t) { t = document.createElement("div"); t.id = "toast"; t.className = "toast"; t.setAttribute("role", "status"); document.body.appendChild(t); }
    t.textContent = m; t.classList.add("on"); clearTimeout(t._h); t._h = setTimeout(() => t.classList.remove("on"), 2600);
  };
  T.fieldErr = (id, msg) => {
    const el = document.getElementById(id); if (!el) return !msg;
    el.setAttribute("aria-invalid", msg ? "true" : "false");
    const s = document.querySelector(`[data-for="${id}"]`); if (s) s.textContent = msg || "";
    return !msg;
  };
  T.showErr = (el, msg) => { el.textContent = msg || ""; el.hidden = !msg; };
  T.peek = (root = document) => root.addEventListener("click", (e) => {
    const b = e.target.closest("[data-peek]"); if (!b) return;
    const i = document.getElementById(b.dataset.peek); i.type = i.type === "password" ? "text" : "password"; b.textContent = i.type === "password" ? "Show" : "Hide";
  });
  T.armed = (btn, label = "Sure?") => {
    if (btn.dataset.armed) return true;
    btn.dataset.armed = "1"; const t = btn.textContent; btn.textContent = label;
    setTimeout(() => { if (btn.isConnected) { btn.dataset.armed = ""; btn.textContent = t; } }, 3000);
    return false;
  };
  T.cooldown = (btn, secs) => {
    btn.disabled = true; const label = btn.dataset.label || (btn.dataset.label = btn.textContent); let n = secs;
    btn.textContent = `${label} (${n}s)`;
    const t = setInterval(() => { n--; if (n <= 0 || !btn.isConnected) { clearInterval(t); btn.disabled = false; btn.textContent = label; } else btn.textContent = `${label} (${n}s)`; }, 1000);
  };
  T.copy = (text, okMsg = "Copied") => navigator.clipboard?.writeText(text).then(() => T.toast(okMsg)).catch(() => T.toast("Couldn't copy. Select the text and copy it manually."));
})();
