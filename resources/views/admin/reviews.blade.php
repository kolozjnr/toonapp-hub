
    <x-app-layout>
        <div class="app">
            @include('layouts.admin-nav')
            <div class="app-main">
                <header class="top">
                    <button
                        aria-controls="sidebar"
                        aria-expanded="false"
                        aria-label="Open menu"
                        class="menu-btn"
                        id="menuBtn"
                        type="button"
                    >
                        <svg
                            fill="none"
                            height="18"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            width="18"
                        >
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1>Reviews</h1>
                        <div class="sub">Ratings customers have left.</div>
                    </div>
                    <div class="top-right">
                        <a class="btn ghost sm store-link" href="{{ route('home') }}"
                            ><svg
                                fill="none"
                                height="18"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                width="18"
                            >
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                                <circle cx="12" cy="12" r="3"></circle></svg
                            ><span>View store</span></a
                        >
                        <div class="who">
                            <span class="av">AD</span
                            ><span class="tx"><b>Store admin</b><small>admin@tooabihub.com</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="kpis">
                        <div class="kpi">
                            <span class="ic warn"
                                ><svg
                                    fill="none"
                                    height="18"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                    width="18"
                                >
                                    <path
                                        d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1 6.2L12 17.3 6.5 20.2l1-6.2L3 9.6l6.2-.9z"
                                    ></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Average rating</div>
                                <div class="v">4.6 / 5</div>
                            </div>
                        </div>
                        <div class="kpi">
                            <span class="ic"
                                ><svg
                                    fill="none"
                                    height="18"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                    width="18"
                                >
                                    <path d="M4 5h16v11H8l-4 4z"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Reviews</div>
                                <div class="v">5</div>
                                <div class="s">4 with comments</div>
                            </div>
                        </div>
                    </section>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Plugin</th>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="{{ route('home') }}#shop"><b>Smear Frames</b></a>
                                    </td>
                                    <td><b>Liam Chen</b><span class="sub">Verified buyer</span></td>
                                    <td>
                                        <span aria-label="4.0 out of 5" class="stars"
                                            ><i>★</i><i>★</i><i>★</i><i>★</i><i class="off">★</i></span
                                        >
                                    </td>
                                    <td class="min-w-220">–</td>
                                    <td class="num">29 Oct 2026</td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs danger ghost" data-del="r5" type="button">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{{ route('home') }}#shop"><b>Squash &amp; Stretch Pro</b></a>
                                    </td>
                                    <td><b>Tunde Bello</b><span class="sub">Verified buyer</span></td>
                                    <td>
                                        <span aria-label="5.0 out of 5" class="stars"
                                            ><i>★</i><i>★</i><i>★</i><i>★</i><i>★</i></span
                                        >
                                    </td>
                                    <td class="min-w-220">
                                        Saved me hours on a client job. The bounce slider is perfect.
                                    </td>
                                    <td class="num">28 Oct 2026</td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs danger ghost" data-del="r1" type="button">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{{ route('home') }}#shop"><b>Toon Shadows</b></a>
                                    </td>
                                    <td><b>Maria Garcia</b><span class="sub">Verified buyer</span></td>
                                    <td>
                                        <span aria-label="4.0 out of 5" class="stars"
                                            ><i>★</i><i>★</i><i>★</i><i>★</i><i class="off">★</i></span
                                        >
                                    </td>
                                    <td class="min-w-220">Great look. I'd love one more shadow band.</td>
                                    <td class="num">25 Oct 2026</td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs danger ghost" data-del="r3" type="button">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{{ route('home') }}#shop"><b>Line Boil FX</b></a>
                                    </td>
                                    <td><b>Jon Park</b><span class="sub">Verified buyer</span></td>
                                    <td>
                                        <span aria-label="5.0 out of 5" class="stars"
                                            ><i>★</i><i>★</i><i>★</i><i>★</i><i>★</i></span
                                        >
                                    </td>
                                    <td class="min-w-220">Exactly the hand-drawn wobble I wanted.</td>
                                    <td class="num">22 Oct 2026</td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs danger ghost" data-del="r4" type="button">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{{ route('home') }}#shop"><b>Squash &amp; Stretch Pro</b></a>
                                    </td>
                                    <td><b>Ada Okafor</b><span class="sub">Verified buyer</span></td>
                                    <td>
                                        <span aria-label="5.0 out of 5" class="stars"
                                            ><i>★</i><i>★</i><i>★</i><i>★</i><i>★</i></span
                                        >
                                    </td>
                                    <td class="min-w-220">So easy to use. My characters finally feel bouncy.</td>
                                    <td class="num">20 Oct 2026</td>
                                    <td>
                                        <div class="acts">
                                            <button class="btn xs danger ghost" data-del="r2" type="button">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>
       
    </x-app-layout>
        <script>
            /* Admin reviews: remove a review with a second tap. */
            (function () {
                "use strict";
                document.getElementById("content").addEventListener("click", function (e) {
                    var del = e.target.closest("[data-del]");
                    if (del && Toon.armed(del)) {
                        // DEMO: Laravel deletes the review.
                        del.closest("tr").remove();
                        Toon.toast("Review removed");
                    }
                });
            })();
        </script>
