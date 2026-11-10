/* ==========================================================================
   ToonApp Hub · store.js
   Shared behaviour for the public store pages: the cart drawer, the
   "Add to cart" buttons and calling the server cart routes. The cart
   lives on the server (guest cart token cookie / user account), so it
   persists across pages and shows up in the customer dashboard.
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

    var urls = {
        items: document.body.getAttribute("data-cart-items") || "/cart/items",
        add: document.body.getAttribute("data-cart-add") || "/cart/add",
        remove: document.body.getAttribute("data-cart-remove") || "/cart/remove",
    };

    function csrf() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    }

    function money(n) {
        n = Number(n) || 0;
        if (!n) return "Free";
        return "$" + (n % 1 ? n.toFixed(2) : String(n));
    }

    var items = [];

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

    /* ---------- render ---------- */
    function inCart(id) {
        return items.some(function (p) {
            return String(p.plugin_id) === String(id);
        });
    }
    Toon.inCart = inCart;

    function syncButtons() {
        $$("[data-add]").forEach(function (btn) {
            var on = inCart(btn.dataset.add);
            var opensCart = btn.hasAttribute("data-open-cart");
            btn.classList.toggle("in", on && !opensCart);
            btn.textContent = on
                ? btn.dataset.inLabel || "✓ In cart"
                : btn.dataset.outLabel || "Add to cart";
        });
    }

    function ensureEmpty() {
        var empty = $("#cartEmpty");
        if (!empty) {
            empty = document.createElement("div");
            empty.className = "empty flat";
            empty.id = "cartEmpty";
            empty.innerHTML = '<h3>Cart\'s empty</h3><p>Add a plugin and it\'ll wait here for you.</p>';
            body.appendChild(empty);
        }
        return empty;
    }

    function refresh() {
        var count = items.length;
        $$(".cart-n").forEach(function (n) {
            n.textContent = String(count);
        });
        var total = items.reduce(function (sum, p) {
            return sum + (Number(p.price) || 0);
        }, 0);
        var totalEl = $("#cartTotal");
        if (totalEl) totalEl.textContent = money(total);
        ensureEmpty().hidden = count > 0;
        var foot = $("#cartFoot");
        if (foot) foot.hidden = count === 0;
        var footEmpty = $("#cartFootEmpty");
        if (footEmpty) footEmpty.hidden = count > 0;
        syncButtons();
    }

    function renderRows() {
        $$("[data-cart-item]", body).forEach(function (row) {
            row.remove();
        });
        var empty = ensureEmpty();
        items.forEach(function (p) {
            if (!tpl) return;
            var row = tpl.content.firstElementChild.cloneNode(true);
            row.dataset.cartItem = p.plugin_id;
            row.dataset.price = p.price;
            var link = row.querySelector("a.th");
            if (link) link.href = p.url;
            var img = row.querySelector("img");
            if (img) {
                img.src = p.image;
                img.alt = p.name;
            }
            row.querySelector("b").textContent = p.name;
            row.querySelector("small").textContent = p.category;
            var rm = row.querySelector("[data-rm]");
            if (rm) rm.dataset.rm = p.plugin_id;
            row.querySelector(".p").textContent = p.price_label;
            body.insertBefore(row, empty || null);
        });
        refresh();
    }

    /* ---------- fetch from the server cart ---------- */
    function request(path, method, data, onDone) {
        return fetch(path, {
            method: method || "GET",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf(),
            },
            body: data ? JSON.stringify(data) : undefined,
            credentials: "same-origin",
        })
            .then(function (response) {
                if (!response.ok) throw new Error("Cart request failed: " + response.status);
                return response.json();
            })
            .then(function (payload) {
                items = payload.items || [];
                renderRows();
                if (onDone) onDone(payload);
            })
            .catch(function () {
                Toon.toast("The cart couldn't be updated. Check your connection and try again.");
            });
    }

    /* ---------- add to cart ---------- */
    document.addEventListener("click", function (e) {
        var btn = e.target.closest("[data-add]");
        if (!btn) return;
        var id = btn.dataset.add;
        if (inCart(id)) {
            openCart();
            return;
        }
        var host = btn.closest("[data-product]");
        var name = host && host.dataset.name ? host.dataset.name : "Plugin";
        request(urls.add, "POST", { plugin_id: Number(id) }, function () {
            Toon.toast(name + " added to cart");
        });
        if (btn.hasAttribute("data-open-cart")) openCart();
    });

    /* ---------- remove from cart ---------- */
    drawer.addEventListener("click", function (e) {
        var rm = e.target.closest("[data-rm]");
        if (!rm) return;
        request(urls.remove, "POST", { plugin_id: Number(rm.dataset.rm) });
    });

    /* ---------- initial state ---------- */
    /* The drawer is server-rendered, so parse whatever rows are present,
       then re-sync from the server (source of truth). */
    $$("[data-cart-item]", body).forEach(function (row) {
        var b = row.querySelector("b"),
            small = row.querySelector("small"),
            priceEl = row.querySelector(".p"),
            link = row.querySelector("a.th"),
            img = row.querySelector("img");
        items.push({
            id: null,
            plugin_id: Number(row.dataset.cartItem),
            name: b ? b.textContent : "",
            category: small ? small.textContent : "Plugin",
            price: Number(row.dataset.price) || 0,
            price_label: priceEl ? priceEl.textContent : "",
            url: link ? link.href : "",
            image: img ? img.src : "",
        });
    });
    refresh();
    request(urls.items, "GET", null);
})();
