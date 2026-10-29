/* ==========================================================================
   ToonApp Hub · store.js
   Shared behaviour for the public store pages: the cart drawer and the
   "Add to cart" buttons. The drawer markup and the cart row <template>
   are written in each page's HTML; this file only shows, hides, clones
   and updates them.
   In Laravel the cart lives on the server: replace the add/remove parts
   with form posts or fetch() calls to your cart routes.
   ========================================================================== */
(function () {
    "use strict";
    var $ = Toon.$,
        $$ = Toon.$$;
    var drawer = $("#cartDrawer"),
        scrim = $("#cartScrim");
    if (!drawer) return;
    var body = $("#cartBody"),
        tpl = $("#cartItemTemplate");

    /* ---------- open / close ---------- */
    function openCart() {
        drawer.classList.add("on");
        if (scrim) scrim.classList.add("on");
    }
    function closeCart() {
        drawer.classList.remove("on");
        if (scrim) scrim.classList.remove("on");
    }
    Toon.openCart = openCart;
    Toon.closeCart = closeCart;

    document.addEventListener("click", function (e) {
        if (e.target.closest("#cartBtn")) {
            openCart();
            return;
        }
        if (e.target.closest("#cartScrim") || e.target.closest("#cartDrawer [data-close]")) closeCart();
    });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeCart();
    });

    /* ---------- totals, counts and empty state ---------- */
    function items() {
        return $$("[data-cart-item]", body);
    }
    function refresh() {
        var list = items();
        var total = list.reduce(function (sum, row) {
            return sum + (parseFloat(row.dataset.price) || 0);
        }, 0);
        $$("#cartBtn .cart-n").forEach(function (n) {
            n.textContent = String(list.length);
        });
        var totalEl = $("#cartTotal");
        if (totalEl) totalEl.textContent = Toon.money(total);
        $("#cartEmpty").hidden = list.length > 0;
        $("#cartFoot").hidden = list.length === 0;
        $("#cartFootEmpty").hidden = list.length > 0;
    }
    function inCart(id) {
        return !!body.querySelector('[data-cart-item="' + CSS.escape(id) + '"]');
    }
    Toon.inCart = inCart;

    /* Button labels: each [data-add] button carries data-in-label / data-out-label. */
    function syncButtons(id) {
        $$('[data-add="' + CSS.escape(id) + '"]').forEach(function (btn) {
            var on = inCart(id);
            btn.classList.toggle("in", on && !btn.hasAttribute("data-open-cart"));
            btn.textContent = on ? btn.dataset.inLabel : btn.dataset.outLabel;
        });
    }

    /* ---------- add (clone the row template) ---------- */
    Toon.addToCart = function (p) {
        if (inCart(p.id)) return false;
        var row = tpl.content.firstElementChild.cloneNode(true);
        row.dataset.cartItem = p.id;
        row.dataset.price = p.price;
        row.querySelector("a.th").href = p.url;
        var img = row.querySelector("img");
        img.src = p.image;
        row.querySelector("b").textContent = p.name;
        row.querySelector("small").textContent = p.category;
        row.querySelector("[data-rm]").dataset.rm = p.id;
        row.querySelector(".p").textContent = Toon.money(p.price);
        body.insertBefore(row, $("#cartEmpty"));
        refresh();
        syncButtons(p.id);
        return true;
    };

    /* Any button with data-add="id" inside an element with data-id/data-name/... */
    document.addEventListener("click", function (e) {
        var btn = e.target.closest("[data-add]");
        if (!btn) return;
        var id = btn.dataset.add;
        if (inCart(id)) {
            openCart();
            return;
        }
        var info =
            btn.closest("[data-product]") || document.querySelector('[data-product][data-id="' + CSS.escape(id) + '"]');
        if (!info) return;
        var d = info.dataset;
        Toon.addToCart({ id: id, name: d.name, category: d.category, price: d.price, image: d.image, url: d.url });
        Toon.toast(d.name + " added to cart");
        if (btn.hasAttribute("data-open-cart")) openCart();
    });

    /* ---------- remove ---------- */
    drawer.addEventListener("click", function (e) {
        var rm = e.target.closest("[data-rm]");
        if (!rm) return;
        var row = rm.closest("[data-cart-item]");
        var id = row.dataset.cartItem;
        row.remove();
        refresh();
        syncButtons(id);
    });

    refresh();
})();
