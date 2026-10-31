/* Plugin preview: gallery lightbox and direct review-anchor scrolling. */
(function () {
    "use strict";

    var box = document.getElementById("lightbox");
    var image = document.getElementById("lbImg");
    var caption = document.getElementById("lbCap");

    if (!box || !image || !caption) return;

    document.addEventListener("click", function (event) {
        var zoom = event.target.closest("[data-zoom]");
        if (!zoom) return;

        var source = zoom.querySelector("img");
        if (!source) return;

        image.src = source.src;
        image.alt = source.alt || "";
        caption.textContent = (zoom.getAttribute("aria-label") || "").replace(/^Enlarge image: /, "");
        box.hidden = false;
    });

    box.addEventListener("click", function () {
        box.hidden = true;
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") box.hidden = true;
    });

    if (location.hash === "#reviews") {
        setTimeout(function () {
            var reviews = document.getElementById("reviews");
            if (reviews) reviews.scrollIntoView({ behavior: "smooth" });
        }, 100);
    }
})();
