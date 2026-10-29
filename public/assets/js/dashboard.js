/* ==========================================================================
   ToonApp Hub · dashboard.js
   Shared behaviour for the customer and admin dashboards:
   opening and closing the sidebar on phones and tablets.
   ========================================================================== */
(function () {
    "use strict";
    var side = document.getElementById("sidebar");
    var scrim = document.getElementById("sideScrim");
    var btn = document.getElementById("menuBtn");
    if (!side || !btn) return;

    function open() {
        side.classList.add("on");
        if (scrim) scrim.classList.add("on");
        btn.setAttribute("aria-expanded", "true");
    }
    function close() {
        side.classList.remove("on");
        if (scrim) scrim.classList.remove("on");
        btn.setAttribute("aria-expanded", "false");
    }

    btn.addEventListener("click", open);
    if (scrim) scrim.addEventListener("click", close);
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") close();
    });
})();
