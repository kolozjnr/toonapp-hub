/* Checkout page: order summary, card form (simulated), success + download email. */
(function () {
  "use strict";
  const { $, esc, url } = T;
  if (T.isAdmin()) { $("#checkout").innerHTML = `<div class="empty"><h3>Admins can't check out</h3><p>Log out and use a customer account to test buying.</p><a class="btn" href="${url("admin/dashboard.html")}">Back to dashboard</a></div>`; return; }
  if (!T.requireCustomer()) return;
  const box = $("#checkout");

  function render() {
    const c = T.me(), items = T.cart().map(T.product).filter(Boolean), total = items.reduce((s, p) => s + (p.price || 0), 0);
    if (!items.length) { box.innerHTML = `<div class="empty"><h3>Your cart is empty</h3><p>Add a plugin first, then come back here to pay.</p><a class="btn" href="${url("index.html#shop")}">Browse plugins</a></div>`; return; }
    box.innerHTML = `<div class="co">
      <section class="panel2">
        <h3>Payment</h3>
        <form class="form" id="payForm" novalidate>
          <div class="mini"><span><b>${esc(c.first)} ${esc(c.last)}</b><small>${esc(c.email)} · ${esc(c.country)}</small></span></div>
          ${total ? `<div class="field"><label for="pName">Name on card</label><input id="pName" autocomplete="cc-name" value="${esc(c.first + " " + c.last)}"></div>
          <div class="field"><label for="pCard">Card number</label><input id="pCard" inputmode="numeric" autocomplete="cc-number" maxlength="19" placeholder="4242 4242 4242 4242"></div>
          <div class="two"><div class="field"><label for="pExp">Expiry</label><input id="pExp" autocomplete="cc-exp" placeholder="MM/YY" maxlength="5"></div><div class="field"><label for="pCvc">CVC</label><input id="pCvc" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="123"></div></div>` : `<p class="note">Everything in your cart is free. No card needed.</p>`}
          <span class="err" id="payErr" hidden></span>
          <button class="btn pink" id="payBtn">${total ? `Pay ${T.money(total)}` : "Get my plugins"}</button>
          <span class="note">Preview checkout: no card is charged. Download links are emailed to ${esc(c.email)} after payment.</span>
        </form>
      </section>
      <aside class="co-sum panel2">
        <h3>Order summary</h3>
        <div class="co-list" id="coList"></div>
        <div class="row total"><span>Total</span><span>${T.money(total)}</span></div>
      </aside></div>`;
    const list = $("#coList");
    items.forEach((p) => {
      const d = document.createElement("div"); d.className = "li";
      d.innerHTML = `<div class="th"></div><div style="min-width:0"><b>${esc(p.name)}</b><small>${esc(p.category || "Plugin")}</small><br><button class="link" type="button" data-rm="${esc(p.id)}">Remove</button></div><span class="p">${T.money(p.price)}</span>`;
      d.querySelector(".th").appendChild(T.coverEl(p)); list.appendChild(d);
    });
    list.addEventListener("click", (e) => { const b = e.target.closest("[data-rm]"); if (b) { T.removeFromCart(b.dataset.rm); render(); } });
    const card = $("#pCard"); if (card) card.addEventListener("input", (e) => (e.target.value = e.target.value.replace(/\D/g, "").slice(0, 16).replace(/(.{4})/g, "$1 ").trim()));
    const ex = $("#pExp"); if (ex) ex.addEventListener("input", (e) => { let v = e.target.value.replace(/\D/g, "").slice(0, 4); if (v.length > 2) v = v.slice(0, 2) + "/" + v.slice(2); e.target.value = v; });
    $("#payForm").addEventListener("submit", (e) => { e.preventDefault(); pay(items, total); });
  }

  async function pay(items, total) {
    const err = $("#payErr"); let m = "";
    if (total && $("#pCard").value.replace(/\s/g, "").length < 15) m = "That card number looks too short.";
    else if (total && !/^(0[1-9]|1[0-2])\/\d{2}$/.test($("#pExp").value)) m = "Expiry should look like 08/28.";
    else if (total && !/^\d{3,4}$/.test($("#pCvc").value)) m = "CVC is the 3 or 4 digits on the back of the card.";
    if (m) return T.showErr(err, m);
    const btn = $("#payBtn"); btn.disabled = true; btn.textContent = "Processing…";
    const c = T.me();
    const order = { cid: c.id, number: "T" + Date.now().toString(36).toUpperCase(), at: Date.now(), total: Math.round(total * 100) / 100, status: "paid", items: items.map((p) => ({ id: p.id, name: p.name, price: p.price || 0 })) };
    try { order.id = T.db.add("orders", order); }
    catch (x) { btn.disabled = false; btn.textContent = total ? `Pay ${T.money(total)}` : "Get my plugins"; return T.showErr(err, x.message || "Couldn't place the order. Try again."); }
    T.setCart([]);
    btn.textContent = "Sending your download links…";
    const res = await T.sendPurchaseEmail(order, c);
    $("#coTitle").textContent = "Thank you!";
    box.innerHTML = `<section class="panel2 co-ok">
      <span class="big-ic" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#1B1530" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7"/></svg></span>
      <h2 style="font-size:34px">Your order is confirmed</h2>
      <p>Order <b>${esc(order.number)}</b> · ${T.money(order.total)}</p>
      <p>${res.ok ? `We've emailed your download links to <b>${esc(c.email)}</b>.` : "Your download links are ready in your dashboard."} You can download your plugins any time from <b>My plugins</b>.</p>
      <div class="cta" style="justify-content:center"><a class="btn mint" href="${url("customer/plugins.html")}">Go to My plugins</a><a class="btn white" href="${url("index.html#shop")}">Keep shopping</a></div>
      <span class="note">This preview doesn't charge cards.</span>
    </section>`;
  }
  render();
})();
