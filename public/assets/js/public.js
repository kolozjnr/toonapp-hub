/* Shared parts of the public store pages: header, footer, cart drawer. */
(function () {
  "use strict";
  const { $, esc, url } = T;
  const ICON = {
    cart: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"/><circle cx="10" cy="20.5" r="1.3"/><circle cx="18" cy="20.5" r="1.3"/></svg>`,
    bell: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9a6 6 0 1 1 12 0c0 6 2.5 7.5 2.5 7.5h-17S6 15 6 9z"/><path d="M10 20a2.2 2.2 0 0 0 4 0"/></svg>`,
    folder: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round"><path d="M3 6.5A1.5 1.5 0 0 1 4.5 5H9l2 2.5h8.5A1.5 1.5 0 0 1 21 9v9.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18.5z"/><path d="M12 11v5m-2.5-2.5L12 16l2.5-2.5" stroke-linecap="round"/></svg>`,
    user: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/></svg>`,
    grid: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>`
  };
  const page = document.body.dataset.page || "";

  /* ---------- header ---------- */
  const header = document.createElement("header");
  header.className = "bar";
  document.body.prepend(header);
  function renderHeader() {
    const c = T.me(), admin = T.isAdmin(), n = T.cart().length;
    let right = "";
    if (admin) {
      right = `<a class="btn sm purple" href="${url("/admin/dashboard"))}">${ICON.grid}<span class="hide-sm">Admin dashboard</span></a>
        <button class="btn sm white" id="hdrLogout">Log out</button>`;
    } else if (c) {
      const unread = T.unreadFor(c);
      right = `<a class="btn sm white icon-btn" href="${url("/customer/updates"))}" aria-label="Updates" style="position:relative">${ICON.bell}${unread ? `<span class="cart-n dot">${unread}</span>` : ""}</a>
        <a class="btn sm white" id="userBtn" href="${url("/customer/dashboard"))}" title="Open my dashboard"><span class="avatar">${esc(((c.first[0] || "") + (c.last[0] || "")).toUpperCase())}</span><span class="hide-sm">${esc(c.first)}</span></a>
        <button class="btn sm" id="cartBtn" aria-label="Open cart">${ICON.cart}<span class="cart-n">${n}</span></button>`;
    } else {
      right = `<a class="btn sm white" id="loginLink" href="${url("/login")}">${ICON.user}<span class="hide-sm">Log in</span></a>
        <a class="btn sm pink hide-xs" id="acctBtn" href="${url("/register")}">Sign up</a>
        <button class="btn sm" id="cartBtn" aria-label="Open cart">${ICON.cart}<span class="cart-n">${n}</span></button>`;
    }
    header.innerHTML = `<div class="wrap"><div class="bar-in">
      <a class="logo" href="${url("/")}" aria-label="${esc(T.storeName)} home"><img class="logo-ic" src="${url("assets/img/logo.png")}" width="40" height="40" alt=""><span>ToonApp <b class="hub">Hub</b></span></a>
      <nav class="nav" aria-label="Main">
        <a href="${url("/?shop")}">Plugins</a><a href="${url("index.html#how")}">How it works</a><a href="${url("index.html#faq")}">FAQ</a>
        <a href="${url("contact.html")}"${page === "contact" ? ' aria-current="page"' : ""} data-goto="contact">Contact</a>
      </nav>
      <div class="bar-right">${right}</div></div></div>`;
    $("#cartBtn", header)?.addEventListener("click", openCart);
    $("#hdrLogout", header)?.addEventListener("click", () => T.logout());
  }

  /* ---------- footer ---------- */
  const footer = document.createElement("footer");
  footer.className = "site-foot";
  footer.innerHTML = `<div class="wrap foot-in"><span>Â© ${new Date().getFullYear()} ${esc(T.storeName)}</span><span><a href="${url("contact.html")}">Contact us</a> Â· Made for animators</span></div>`;
  document.body.appendChild(footer);

  /* ---------- cart drawer ---------- */
  const scrim = document.createElement("div"); scrim.className = "scrim";
  const drawer = document.createElement("aside"); drawer.className = "drawer"; drawer.setAttribute("aria-label", "Cart");
  drawer.innerHTML = `<div class="dh"><h3>Your cart</h3><button class="x" data-close aria-label="Close">Ã—</button></div><div class="dbody" id="cartBody"></div><div class="dfoot" id="cartFoot"></div>`;
  document.body.append(scrim, drawer);
  function closeCart() { drawer.classList.remove("on"); scrim.classList.remove("on"); }
  function openCart() { renderCart(); drawer.classList.add("on"); scrim.classList.add("on"); }
  T.openCart = openCart; T.closeCart = closeCart;
  scrim.addEventListener("click", closeCart);
  drawer.addEventListener("click", (e) => { if (e.target.closest("[data-close]")) closeCart(); });
  addEventListener("keydown", (e) => { if (e.key === "Escape") closeCart(); });
  function renderCart() {
    const items = T.cart().map(T.product).filter(Boolean), total = items.reduce((s, p) => s + (p.price || 0), 0);
    const body = $("#cartBody"), foot = $("#cartFoot");
    if (!items.length) {
      body.innerHTML = `<div class="empty flat"><h3>Cart's empty</h3><p>Add a plugin and it'll wait here for you.</p></div>`;
      foot.innerHTML = `<a class="btn" href="${url("/?shop")}" data-close>Browse plugins</a>`; return;
    }
    body.innerHTML = "";
    items.forEach((p) => {
      const d = document.createElement("div"); d.className = "li";
      d.innerHTML = `<a class="th" href="${url("/plugin?id=" + encodeURIComponent(p.id))}"></a><div style="min-width:0"><b>${esc(p.name)}</b><small>${esc(p.category || "Plugin")}</small><br><button class="link" data-rm="${esc(p.id)}">Remove</button></div><span class="p">${T.money(p.price)}</span>`;
      d.querySelector(".th").appendChild(T.coverEl(p));
      body.appendChild(d);
    });
    const c = T.me();
    foot.innerHTML = `<div class="row total"><span>Total</span><span>${T.money(total)}</span></div>
      <a class="btn pink" id="toCheckout" href="${url(c ? "checkout.html" : "login.html?next=checkout.html")}">${c ? "Checkout" : "Log in to checkout"}</a>
      ${c ? "" : `<span class="note" style="text-align:center">New here? <a class="link" href="${url("register.html?next=checkout.html")}">Create an account</a></span>`}
      <span class="note" style="text-align:center">One-time payment Â· Instant download</span>`;
  }
  drawer.addEventListener("click", (e) => { const b = e.target.closest("[data-rm]"); if (b) { T.removeFromCart(b.dataset.rm); renderCart(); } });

  /* ---------- keep in sync ---------- */
  document.addEventListener("cart", () => { renderHeader(); if (drawer.classList.contains("on")) renderCart(); });
  T.db.on(() => renderHeader());
  renderHeader();
  T.recordVisit();
  T.refreshHeader = renderHeader;
})();
