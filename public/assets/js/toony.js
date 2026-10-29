/* ==========================================================================
   ToonApp Hub · toony.js
   Toony, the talking mascot. His markup (the hero mascot on the home page
   and the small buddy in the corner) is written in the HTML.
   He bounces everywhere, but only talks and laughs on computers with a
   mouse and a wide screen (too heavy for phones).
   Product facts come from data- attributes on elements with [data-product]:
     data-name, data-tagline, data-price, data-was, data-features ("a|b|c"),
     data-hosts ("After Effects and Premiere Pro"), data-rating, data-reviews,
     data-tutorial ("1" when there is a tutorial video).
   ========================================================================== */
(function () {
    "use strict";
    var reduce = matchMedia("(prefers-reduced-motion: reduce)").matches;
    var deskQ = matchMedia("(hover: hover) and (pointer: fine) and (min-width: 900px)");

    /* ---------- eyes follow the pointer ---------- */
    if (!reduce) {
        addEventListener(
            "pointermove",
            function (e) {
                document.querySelectorAll("svg.toon").forEach(function (svg) {
                    var r = svg.getBoundingClientRect();
                    if (!r.width) return;
                    var sx = 360 / r.width;
                    svg.querySelectorAll(".pupil").forEach(function (p) {
                        var cx = +p.dataset.cx,
                            cy = +p.dataset.cy;
                        var dx = (e.clientX - r.left) * sx - cx,
                            dy = (e.clientY - r.top) * sx - cy;
                        var d = Math.hypot(dx, dy) || 1,
                            m = Math.min(11, d / 18);
                        p.setAttribute("cx", cx + (dx / d) * m);
                        p.setAttribute("cy", cy + (dy / d) * m);
                    });
                });
            },
            { passive: true },
        );
    }

    var hero = document.getElementById("mascot");
    var heroBubble = document.getElementById("heroBubble");
    var box = document.getElementById("buddy");
    var mini = box ? box.querySelector("svg.toon") : null;
    var miniBubble = box ? box.querySelector(".buddy-bubble") : null;
    var DEFAULT = heroBubble ? heroBubble.textContent : "";
    if (!mini) return;
    if (!deskQ.matches && !hero) return;

    var MOUTH = {
        laugh: "M138 238 Q180 318 222 238 Q180 252 138 238",
        laugh2: "M144 242 Q180 300 216 242 Q180 254 144 242",
        smile: "M150 248 Q180 280 210 248",
        wide: "M146 244 Q180 304 214 244 Q180 256 146 244",
        mid: "M154 248 Q180 286 206 248 Q180 258 154 248",
        shut: "M156 254 Q180 266 204 254 Q180 260 156 254",
        o: "M166 248 Q180 284 194 248 Q180 236 166 248",
    };
    var SHAPES = ["wide", "mid", "shut", "o", "mid", "wide", "shut"];
    var LAUGHS = ["😂 HAHAHA!", "🤣 BWAHAHA!", "😆 Hehehe… I crack myself up!", "😂 HA! Classic me."];
    var speaking = false,
        lastSpeech = 0,
        moved = false,
        typeT = 0,
        mouthT = 0,
        hideT = 0,
        laughT = 0,
        laughEnd = 0;
    var said = {};
    function on() {
        return deskQ.matches;
    }
    function pick(a) {
        return a[Math.floor(Math.random() * a.length)];
    }
    function clip(t, n) {
        n = n || 120;
        return t.length > n ? t.slice(0, n - 1).replace(/\s+\S*$/, "") + "…" : t;
    }

    function useHero() {
        if (!hero) return false;
        var r = hero.getBoundingClientRect();
        if (!r.height) return false;
        var bar = document.querySelector(".bar-in"),
            barBottom = bar ? bar.getBoundingClientRect().bottom : 0;
        return r.top > barBottom - 10 && r.top < innerHeight - r.height * 0.5;
    }
    function update() {
        var showMini = on() && !useHero();
        box.classList.toggle("off", !showMini);
        if (!showMini) miniBubble.classList.add("hide");
    }
    var raf = 0;
    addEventListener(
        "scroll",
        function () {
            if (raf) return;
            raf = requestAnimationFrame(function () {
                raf = 0;
                update();
            });
        },
        { passive: true },
    );
    if (deskQ.addEventListener)
        deskQ.addEventListener("change", function () {
            stop();
            update();
        });

    function setMouth(svg, k) {
        var m = svg && svg.querySelector(".mouth");
        if (m) m.setAttribute("d", MOUTH[k]);
    }
    function stop() {
        clearInterval(typeT);
        clearInterval(mouthT);
        clearTimeout(hideT);
        clearInterval(laughT);
        clearTimeout(laughEnd);
        speaking = false;
        [hero, mini].forEach(function (s) {
            if (s) {
                s.classList.remove("talking", "laughing");
                setMouth(s, "smile");
            }
        });
        if (heroBubble) {
            heroBubble.textContent = DEFAULT;
            heroBubble.classList.remove("talk");
        }
        miniBubble.classList.add("hide");
    }
    function laugh(svg, bubble) {
        speaking = true;
        lastSpeech = Date.now();
        bubble.textContent += " " + pick(LAUGHS);
        svg.classList.add("laughing");
        var k = 0;
        laughT = setInterval(function () {
            setMouth(svg, k++ % 2 ? "laugh" : "laugh2");
        }, 150);
        laughEnd = setTimeout(function () {
            clearInterval(laughT);
            svg.classList.remove("laughing");
            setMouth(svg, "smile");
            speaking = false;
            lastSpeech = Date.now();
        }, 1900);
    }
    function say(text, funny) {
        if (!on()) return;
        update();
        stop();
        var heroOn = useHero();
        var svg = heroOn ? hero : mini,
            bubble = heroOn ? heroBubble : miniBubble;
        speaking = true;
        lastSpeech = Date.now();
        svg.classList.add("talking");
        bubble.classList.remove("hide");
        bubble.classList.add("talk");
        var words = String(text).split(" "),
            i = 0;
        bubble.textContent = "";
        typeT = setInterval(
            function () {
                bubble.textContent = words.slice(0, ++i).join(" ");
                if (i >= words.length) {
                    clearInterval(typeT);
                    clearInterval(mouthT);
                    setMouth(svg, "smile");
                    svg.classList.remove("talking");
                    speaking = false;
                    if (funny) laugh(svg, bubble);
                    hideT = setTimeout(
                        function () {
                            if (bubble === heroBubble) {
                                heroBubble.textContent = DEFAULT;
                                heroBubble.classList.remove("talk");
                            } else miniBubble.classList.add("hide");
                        },
                        2600 + words.length * 120 + (funny ? 1900 : 0),
                    );
                }
            },
            reduce ? 40 : 150,
        );
        if (!reduce) {
            var k = 0;
            mouthT = setInterval(function () {
                setMouth(svg, SHAPES[(k++ + ((Math.random() * 3) | 0)) % SHAPES.length]);
            }, 95);
        }
    }

    var QUIPS = [
        "Psst… that cursor of yours looks talented.",
        "Keyframes? I eat them for breakfast.",
        "Easy ease in, easy ease out… easy life.",
        "Ctrl+Z is my love language.",
        "I'm not bouncing. I'm doing squash and stretch practice.",
        "Is it hot in here or is that your GPU?",
        "Don't tell After Effects, but I render in real time.",
        "Every frame counts. Especially the good ones.",
        "Fun fact: I was animated at 12 frames per second. Smooth, right?",
        "Hover over a plugin and I'll spill the details!",
        "My therapist says I have too many keyframes.",
        "Rendering… rendering… just kidding, I'm already here!",
        "I'd help you edit, but I don't have fingers.",
        "Somewhere a timeline is waiting for you. Go get it!",
    ];

    function productLine(d) {
        var name = d.name,
            price = parseFloat(d.price) || 0,
            was = parseFloat(d.was) || 0;
        var feats = (d.features || "").split("|").filter(Boolean),
            opts = [];
        if (d.tagline) opts.push(name + ": " + d.tagline);
        if (feats.length) opts.push('"' + pick(feats) + "\". That's " + name + " for you!");
        if (!price) opts.push(name + " is FREE. Grab it before I do!");
        else if (was > price) opts.push(name + " is on sale: " + Toon.money(price) + " instead of $" + was + "!");
        else opts.push(name + " for just " + Toon.money(price) + ". Your timeline will thank you.");
        var n = parseInt(d.reviews, 10) || 0;
        if (n) opts.push(d.rating + " stars from " + n + " animator" + (n > 1 ? "s" : "") + ". Not bad, " + name + "!");
        if (d.tutorial === "1") opts.push(name + " has a tutorial video. I watched it twice!");
        if (d.hosts) opts.push(name + " works in " + d.hosts + ".");
        return clip(pick(opts));
    }
    function addLine(d, id) {
        var name = d.name,
            price = parseFloat(d.price) || 0;
        if (Toon.inCart && Toon.inCart(id))
            return pick([
                name + " is already in your cart, silly! Go check out!",
                "It's in the cart already! Now go finish the job, champ.",
                "Already added! The cart button is RIGHT up there.",
            ]);
        if (!price)
            return pick([
                name + " is FREE! Grab it before I eat it.",
                "Free?! Click it. Click it now. I'll wait. (I won't.)",
                "Zero dollars! Even my budget can handle that.",
            ]);
        return pick([
            "Yes! Put " + name + " in the cart! Do it!",
            name + ' is giving serious "take me home" energy.',
            "One click and " + name + " is yours. I'm SO excited!",
            "Add it! Your keyframes will never be the same.",
            "My finger would click that… if I had fingers.",
            "That button is BEGGING to be clicked. Look at it!",
            "Go on, treat yourself. You've rendered enough today.",
            Toon.money(price) + "? That's cheaper than redoing the animation by hand!",
        ]);
    }
    function cartCount() {
        var n = document.querySelector("#cartBtn .cart-n");
        return n ? parseInt(n.textContent, 10) || 0 : 0;
    }

    function lineFor(el) {
        if (el.matches("[data-add]")) {
            var id = el.dataset.add;
            var info =
                el.closest("[data-product]") ||
                document.querySelector('[data-product][data-id="' + CSS.escape(id) + '"]');
            return info ? ["add" + id, addLine(info.dataset, id), true] : null;
        }
        var card = el.closest(".card, .more-card");
        if (card && card.dataset.name) return [card.dataset.id, productLine(card.dataset)];
        if (el.matches("svg.toon"))
            return [
                "me",
                pick([
                    "Hey! That tickles!",
                    "Careful, I'm fragile. Mostly jelly.",
                    "Boop! You found me.",
                    "I'm the mascot. I work for compliments.",
                ]),
            ];
        if (el.id === "cartBtn") {
            var n = cartCount();
            return [
                "cart",
                n
                    ? pick([
                          "DO IT! You're almost there!",
                          n + " goodie" + (n > 1 ? "s" : "") + " in the cart… just one tiny click away from greatness!",
                          "Checkout is right there. I believe in you!",
                          "Your wallet said yes. I heard it. Clearly.",
                          "Think of it as an investment… in your keyframes.",
                          "Go on, your timeline deserves nice things!",
                          "Pssst… future you is already thanking you.",
                          "Click it. Click it. CLICK IT! …sorry, I got excited.",
                      ])
                    : pick([
                          "Your cart is emptier than my render queue. Fill it up!",
                          "An empty cart? That's a crime in 12 frames per second.",
                          "Your cart is feeling lonely. Give it a friend!",
                          "Nothing in here yet… the plugins are getting jealous.",
                      ]),
                true,
            ];
        }
        if (el.id === "acctBtn") return ["acct", "Join the club! We have plugins. And me."];
        if (el.id === "loginLink") return ["login", "Welcome back! Your plugins missed you."];
        if (el.id === "userBtn") return ["user", "Hey " + (el.dataset.first || "there") + "! Nice to see you."];
        if (el.id === "q") return ["q", "Looking for something? Type away, I'll wait."];
        if (el.matches(".apps .host")) return ["host" + el.textContent, (el.title || "That app") + " fan? Me too!"];
        if (el.matches('[data-goto="contact"]')) return ["contact", "Got questions? The team is just a message away."];
        if (el.matches(".chip"))
            return ["chip" + el.textContent, "Filtering by " + el.textContent.trim() + ". Smart move."];
        if (el.matches(".pp-hero .btn.white, .pp-yt")) return ["yt", "Grab some popcorn, it's tutorial time!"];
        return null;
    }

    var lastEl = null,
        hoverT = 0;
    var HOVER =
        '[data-add], .card, .more-card, svg.toon, #cartBtn, #acctBtn, #loginLink, #userBtn, #q, .apps .host, [data-goto="contact"], .chip, .pp-hero .btn.white, .pp-yt';
    document.addEventListener("mouseover", function (e) {
        if (!on()) return;
        var el = e.target.closest(HOVER);
        if (el === lastEl) return;
        lastEl = el;
        clearTimeout(hoverT);
        if (!el || document.querySelector(".drawer.on")) return;
        hoverT = setTimeout(function () {
            var r = lineFor(el);
            if (!r) return;
            var key = r[0],
                t = said[key] || 0;
            if (Date.now() - t < (key === "cart" || key.indexOf("add") === 0 ? 4000 : 7000)) return;
            said[key] = Date.now();
            say(r[1], key === "me" || r[2]);
        }, 280);
    });
    [hero, mini].forEach(function (svg) {
        if (svg)
            svg.addEventListener("click", function () {
                if (on()) say(pick(QUIPS), true);
            });
    });
    addEventListener(
        "pointermove",
        function () {
            moved = true;
        },
        { passive: true },
    );
    setInterval(function () {
        if (!on() || document.hidden || speaking || !moved) return;
        if (document.querySelector(".drawer.on")) return;
        if (Date.now() - lastSpeech < 11000) return;
        moved = false;
        say(pick(QUIPS), true);
    }, 3500);
    setTimeout(function () {
        if (on() && useHero())
            say(
                pick([
                    "Hi there! I'm Toony. Hover around, I've got opinions.",
                    "Welcome! Hover a plugin and I'll tell you about it.",
                ]),
            );
    }, 1500);
    update();
})();
