/* Landing page: the plugin shelf now reads from the database.
   The server renders the first 12 cards; this file filters, searches, sorts and
   appends the remaining pages as the visitor scrolls. */
(function () {
    "use strict";

    var shop = document.getElementById("shop");
    if (!shop) return;

    var grid = document.getElementById("grid");
    var cats = document.getElementById("cats");
    var search = document.getElementById("q");
    var sort = document.getElementById("sort");
    var countNote = document.getElementById("countNote");
    var noMatches = document.getElementById("noMatches");
    var emptyTitle = document.getElementById("emptyTitle");
    var emptyText = document.getElementById("emptyText");
    var loadMore = document.getElementById("loadMore");
    var loadMoreFallback = document.getElementById("loadMoreFallback");
    var loadMoreBtn = document.getElementById("loadMoreBtn");
    var sentinel = document.getElementById("scrollSentinel");

    if (!grid) return;

    var feedUrl = shop.dataset.feedUrl;
    var fallbackImage = shop.dataset.fallbackImage || "";
    var supportsObserver = "IntersectionObserver" in window;
    var observer = null;
    var controller = null;
    var requestId = 0;
    var searchTimer = 0;

    var state = {
        q: shop.dataset.q || "",
        category: shop.dataset.category || "All",
        sort: shop.dataset.sort || "new",
        page: Number(shop.dataset.page) || 1,
        hasMore: shop.dataset.hasMore === "1",
        allTotal: Number(shop.dataset.allTotal) || 0,
        filteredTotal: Number(shop.dataset.filteredTotal) || 0,
        shown: Number(shop.dataset.shown) || 0,
        loading: false,
    };

    function escapeHtml(value) {
        return String(value == null ? "" : value).replace(/[&<>"']/g, function (char) {
            return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[char];
        });
    }

    function cardHtml(plugin) {
        var price = Number(plugin.price) || 0;
        var priceLabel = plugin.price_label || (price > 0 ? "$" + price : "Free");
        var oldLabel = plugin.old_price_label || "";
        var badge = plugin.badge || "";
        var url = plugin.url || "#shop";
        var image = plugin.cover_url || fallbackImage;
        var hosts = (plugin.hosts || [])
            .map(function (host) {
                var code = String(host.code || "").toLowerCase();
                return (
                    '<span class="host host-' +
                    escapeHtml(code) +
                    '" title="' +
                    escapeHtml(host.name) +
                    '">' +
                    escapeHtml(host.code) +
                    "</span>"
                );
            })
            .join("");
        var cover = plugin.cover_url
            ? '<img src="' + escapeHtml(plugin.cover_url) + '" alt="" loading="lazy" />'
            : '<span class="cover-art" style="--hue: ' + (Number(plugin.hue) || 0) + '">' + escapeHtml(plugin.initials) + "</span>";
        var inCart =
            typeof Toon !== "undefined" && typeof Toon.inCart === "function" && Toon.inCart(String(plugin.id));
        var outLabel = price <= 0 ? "Get it free" : "Add to cart";

        return (
            '<article class="card" data-product="" data-id="' +
            escapeHtml(plugin.id) +
            '" data-name="' +
            escapeHtml(plugin.name) +
            '" data-category="' +
            escapeHtml(plugin.category || "Plugin") +
            '" data-features="' +
            escapeHtml(String(plugin.features || "").replace(/\r\n|\r|\n/g, "|")) +
            '" data-hosts="' +
            escapeHtml(plugin.host_names || "") +
            '" data-price="' +
            price +
            '" data-rating="0" data-reviews="0" data-tagline="' +
            escapeHtml(plugin.tagline || "") +
            '" data-tutorial="' +
            (plugin.has_tutorial ? "1" : "0") +
            '" data-was="' +
            (Number(plugin.old_price) > price ? Number(plugin.old_price) : "") +
            '" data-image="' +
            escapeHtml(image) +
            '" data-url="' +
            escapeHtml(url) +
            '">' +
            '<span class="sticker ' +
            (price <= 0 ? "free" : "") +
            '">' +
            escapeHtml(priceLabel) +
            (oldLabel ? "<s>" + escapeHtml(oldLabel) + "</s>" : "") +
            "</span>" +
            '<a class="cover" href="' +
            escapeHtml(url) +
            '" aria-label="View ' +
            escapeHtml(plugin.name) +
            '">' +
            cover +
            (badge ? '<span class="flag">' + escapeHtml(badge) + "</span>" : "") +
            (plugin.has_tutorial ? '<span class="vidflag">▶ Tutorial</span>' : "") +
            "</a>" +
            '<div class="cbody">' +
            '<div class="cmeta"><span>' +
            escapeHtml(plugin.category || "Plugin") +
            '</span><span class="host-list">' +
            hosts +
            "</span></div>" +
            '<h3><a class="plain-link" href="' +
            escapeHtml(url) +
            '">' +
            escapeHtml(plugin.name) +
            "</a></h3>" +
            '<span class="rate"><span aria-label="No ratings yet" class="stars"><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i></span> No ratings yet</span>' +
            "<p>" +
            escapeHtml(plugin.tagline || "") +
            "</p>" +
            '<div class="cfoot">' +
            '<button class="btn sm' +
            (inCart ? " in" : "") +
            '" data-add="' +
            escapeHtml(plugin.id) +
            '" data-in-label="✓ In cart" data-out-label="' +
            escapeHtml(outLabel) +
            '" type="button">' +
            (inCart ? "✓ In cart" : escapeHtml(outLabel)) +
            "</button>" +
            '<a class="btn sm white" href="' +
            escapeHtml(url) +
            '">Preview</a>' +
            "</div></div></article>"
        );
    }

    function updateCount() {
        if (!countNote) return;

        if (state.allTotal <= 0) {
            countNote.textContent = "0 plugins";
            return;
        }

        countNote.textContent =
            "Showing " + state.shown + " of " + state.allTotal + " " + (state.allTotal === 1 ? "plugin" : "plugins");
    }

    function updateEmpty() {
        if (!noMatches) return;

        noMatches.hidden = state.filteredTotal > 0;
        if (state.filteredTotal > 0) return;

        var filtering = state.q.trim() !== "" || state.category !== "All";
        if (emptyTitle) emptyTitle.textContent = filtering ? "No matches" : "New plugins are on the way";
        if (emptyText) {
            emptyText.textContent = filtering
                ? "Try another category or clear the search."
                : "Nothing on the shelf yet. Check back soon.";
        }
    }

    function updateFallback() {
        if (!loadMoreFallback) return;
        loadMoreFallback.hidden = supportsObserver || !state.hasMore;
    }

    function setLoading(on) {
        state.loading = on;
        grid.classList.toggle("is-loading", on);
        if (loadMore) loadMore.hidden = !on;
    }

    function syncUrl() {
        var params = new URLSearchParams();
        if (state.q.trim()) params.set("q", state.q.trim());
        if (state.category !== "All") params.set("category", state.category);
        if (state.sort !== "new") params.set("sort", state.sort);

        var query = params.toString();
        var next = location.pathname + (query ? "?" + query : "") + location.hash;

        try {
            history.replaceState(null, "", next);
        } catch (error) {
            /* Browsers can refuse replaceState for local files. */
        }
    }

    function scrollToGrid() {
        if (shop.getBoundingClientRect().top >= 0) return;
        shop.scrollIntoView({ behavior: "smooth", block: "start" });
    }

    function fetchPage(page, replace) {
        var id = ++requestId;
        if (controller) controller.abort();
        controller = new AbortController();

        var params = new URLSearchParams({
            q: state.q.trim(),
            category: state.category,
            sort: state.sort,
            page: String(page),
        });

        setLoading(true);

        return fetch(feedUrl + "?" + params.toString(), {
            headers: { Accept: "application/json" },
            signal: controller.signal,
        })
            .then(function (response) {
                if (!response.ok) throw new Error("Request failed");
                return response.json();
            })
            .then(function (payload) {
                if (id !== requestId) return;

                var items = Array.isArray(payload.data) ? payload.data : [];
                var meta = payload.meta || {};

                if (replace) {
                    grid.innerHTML = "";
                    state.shown = 0;
                }

                if (items.length) {
                    grid.insertAdjacentHTML("beforeend", items.map(cardHtml).join(""));
                    state.shown += items.length;
                }

                state.page = Number(meta.current_page) || page;
                state.hasMore = Boolean(meta.has_more);
                state.allTotal = Number(meta.all_total) || state.allTotal;
                state.filteredTotal = Number(meta.total) || 0;

                updateCount();
                updateEmpty();
                updateFallback();
                syncUrl();
            })
            .catch(function (error) {
                if (error.name === "AbortError" || id !== requestId) return;
                if (window.Toon && typeof Toon.toast === "function") {
                    Toon.toast("Couldn't load plugins. Please try again.");
                }
            })
            .finally(function () {
                if (id !== requestId) return;
                setLoading(false);
                controller = null;
                maybeLoadMore();
            });
    }

    function loadNext() {
        if (!state.hasMore || state.loading) return;
        fetchPage(state.page + 1, false);
    }

    function maybeLoadMore() {
        if (!state.hasMore || state.loading || !sentinel) return;
        if (sentinel.getBoundingClientRect().top < window.innerHeight + 400) loadNext();
    }

    function resetAndFetch() {
        state.page = 0;
        state.hasMore = false;
        state.filteredTotal = 0;
        state.shown = 0;
        grid.innerHTML = "";
        if (noMatches) noMatches.hidden = true;
        updateCount();
        updateFallback();
        scrollToGrid();
        fetchPage(1, true);
    }

    if (cats) {
        cats.addEventListener("click", function (event) {
            var chip = event.target.closest("[data-cat]");
            if (!chip) return;

            state.category = chip.dataset.cat || "All";
            Array.prototype.forEach.call(cats.querySelectorAll("[data-cat]"), function (button) {
                button.setAttribute("aria-pressed", String(button === chip));
            });
            resetAndFetch();
        });
    }

    if (search) {
        search.addEventListener("input", function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                state.q = search.value.trim();
                resetAndFetch();
            }, 300);
        });
    }

    if (sort) {
        sort.addEventListener("change", function () {
            state.sort = sort.value;
            resetAndFetch();
        });
    }

    if (loadMoreBtn) loadMoreBtn.addEventListener("click", loadNext);

    if (supportsObserver && sentinel) {
        observer = new IntersectionObserver(
            function (entries) {
                if (entries.some(function (entry) { return entry.isIntersecting; })) loadNext();
            },
            { rootMargin: "400px 0px" },
        );
        observer.observe(sentinel);
    }

    updateCount();
    updateEmpty();
    updateFallback();
    maybeLoadMore();
})();
