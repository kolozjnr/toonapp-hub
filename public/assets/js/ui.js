/* ==========================================================================
   ToonApp Hub · ui.js
   Small interaction helpers shared by every page (store and dashboards).
   No page content is created here: it only toggles classes, reads form
   values and changes the text of elements that already exist in the HTML.
   ========================================================================== */
(function () {
    "use strict";

    var Toon = (window.Toon = window.Toon || {});

    Toon.$ = function (sel, root) {
        return (root || document).querySelector(sel);
    };
    Toon.$$ = function (sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    };

    /* ---------- checks used by the forms ---------- */
    Toon.emailOk = function (v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v || "");
    };
    Toon.linkOk = function (v) {
        return /^https?:\/\/\S+$/.test(v || "");
    };
    Toon.youtubeOk = function (v) {
        return /^https?:\/\/(www\.|m\.)?(youtube\.com|youtu\.be)\//i.test(v || "");
    };
    Toon.passwordRule = function (pw) {
        if ((pw || "").length < 8) return "Use at least 8 characters.";
        if (!/\d/.test(pw) || !/[a-z]/i.test(pw)) return "Mix letters and at least one number.";
        return "";
    };
    Toon.money = function (n) {
        n = Number(n) || 0;
        return n ? "$" + (n % 1 ? n.toFixed(2) : String(n)) : "Free";
    };

    /* ---------- toast (uses the #toast element in the page) ---------- */
    Toon.toast = function (message) {
        var t = document.getElementById("toast");
        if (!t) return;
        t.textContent = message;
        t.classList.add("on");
        clearTimeout(t._hide);
        t._hide = setTimeout(function () {
            t.classList.remove("on");
        }, 2600);
    };

    /* ---------- form errors ----------
     Each input has an error <span class="ferr" data-for="inputId"> under it. */
    Toon.fieldErr = function (id, message) {
        var input = document.getElementById(id);
        if (input) input.setAttribute("aria-invalid", message ? "true" : "false");
        var slot = document.querySelector('[data-for="' + id + '"]');
        if (slot) slot.textContent = message || "";
        return !message;
    };
    Toon.showErr = function (el, message) {
        if (!el) return;
        el.textContent = message || "";
        el.hidden = !message;
    };

    /* ---------- two-tap confirm for destructive buttons ---------- */
    Toon.armed = function (btn, label) {
        if (btn.dataset.armed) return true;
        var original = btn.textContent;
        btn.dataset.armed = "1";
        btn.textContent = label || "Sure?";
        setTimeout(function () {
            if (btn.isConnected) {
                btn.dataset.armed = "";
                btn.textContent = original;
            }
        }, 3000);
        return false;
    };

    /* ---------- countdown on "Resend code" buttons ---------- */
    Toon.cooldown = function (btn, seconds) {
        var label = btn.dataset.label || (btn.dataset.label = btn.textContent);
        var left = seconds;
        btn.disabled = true;
        btn.textContent = label + " (" + left + "s)";
        var timer = setInterval(function () {
            left--;
            if (left <= 0) {
                clearInterval(timer);
                btn.disabled = false;
                btn.textContent = label;
            } else btn.textContent = label + " (" + left + "s)";
        }, 1000);
    };

    /* ---------- copy to clipboard ---------- */
    Toon.copy = function (text, okMessage) {
        if (!navigator.clipboard) {
            Toon.toast("Select the text and copy it manually.");
            return;
        }
        navigator.clipboard.writeText(text).then(
            function () {
                Toon.toast(okMessage || "Copied");
            },
            function () {
                Toon.toast("Couldn't copy. Select the text and copy it manually.");
            },
        );
    };

    /* ---------- page-wide click helpers ---------- */
    document.addEventListener("click", function (e) {
        // Show / hide password:  <button type="button" data-peek="inputId">Show</button>
        var peek = e.target.closest("[data-peek]");
        if (peek) {
            var input = document.getElementById(peek.dataset.peek);
            if (input) {
                input.type = input.type === "password" ? "text" : "password";
                peek.textContent = input.type === "password" ? "Show" : "Hide";
            }
            return;
        }
        // Copy:  <button data-copy="text to copy" data-copy-msg="Copied">
        var copy = e.target.closest("[data-copy]");
        if (copy) Toon.copy(copy.dataset.copy, copy.dataset.copyMsg);
    });

    /* ---------- live search over a list or table ----------
     <input data-search="#tableBody" data-search-items="tr" data-search-empty="#noResults"> */
    Toon.$$("[data-search]").forEach(function (input) {
        input.addEventListener("input", function () {
            var q = input.value.trim().toLowerCase();
            var box = document.querySelector(input.dataset.search);
            if (!box) return;
            var shown = 0;
            Toon.$$(input.dataset.searchItems || ":scope > *", box).forEach(function (item) {
                if (item.hasAttribute("data-search-ignore")) return;
                var hit = !q || item.textContent.toLowerCase().indexOf(q) !== -1;
                item.hidden = !hit;
                if (hit) shown++;
            });
            var empty = input.dataset.searchEmpty && document.querySelector(input.dataset.searchEmpty);
            if (empty) empty.hidden = shown > 0;
        });
    });

    /* ---------- greeting by time of day:  <span data-greeting>Hello</span> ---------- */
    Toon.$$("[data-greeting]").forEach(function (el) {
        var h = new Date().getHours();
        el.textContent = h < 12 ? "Good morning" : h < 17 ? "Good afternoon" : "Good evening";
    });
})();
