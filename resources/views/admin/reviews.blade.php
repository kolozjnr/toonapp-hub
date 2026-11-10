
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
                            <span class="av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span
                            ><span class="tx"
                                ><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span
                            >
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
                                <div class="v">{{ $stats['total'] > 0 ? $stats['average'].' / 5' : '–' }}</div>
                                <div class="s">
                                    @if ($stats['total'] > 0)
                                        Across {{ $stats['total'] }} {{ \Illuminate\Support\Str::plural('review', $stats['total']) }}
                                    @else
                                        No ratings yet
                                    @endif
                                </div>
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
                                <div class="v">{{ $stats['total'] }}</div>
                                <div class="s">{{ $stats['commented'] }} with comments</div>
                            </div>
                        </div>
                    </section>
                    <div class="toolbar">
                        <span class="note">
                            {{ $reviews->total() }} {{ \Illuminate\Support\Str::plural('review', $reviews->total()) }} ·
                            newest first
                        </span>
                    </div>
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
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td>
                                            @if ($review->plugin)
                                                <a href="{{ route('admin.plugins.show', $review->plugin) }}"
                                                    ><b>{{ $review->plugin->name }}</b></a
                                                >
                                            @else
                                                <b>Removed plugin</b>
                                            @endif
                                        </td>
                                        <td>
                                            <b>{{ $review->user?->name ?? 'Deleted customer' }}</b>
                                            <span class="sub">{{ $review->user?->email }}</span>
                                        </td>
                                        <td>@include('partials.stars', ['rating' => $review->rating])</td>
                                        <td class="min-w-220">{{ $review->comment ?: '–' }}</td>
                                        <td class="num">{{ $review->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="acts">
                                                <form
                                                    data-confirm="Remove this review? This cannot be undone."
                                                    method="post"
                                                    action="{{ route('admin.reviews.destroy', $review) }}"
                                                >
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn xs danger ghost" type="submit">Remove</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="note" colspan="6">
                                            No reviews yet. Reviews appear here as soon as customers rate a plugin.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($reviews->hasPages())
                        <div class="toolbar" style="margin-top: 16px;">
                            <span class="note">
                                Showing {{ $reviews->firstItem() }}–{{ $reviews->lastItem() }} of {{ $reviews->total() }}
                            </span>
                            <div class="acts ml-auto">
                                @if ($reviews->onFirstPage())
                                    <span class="btn xs ghost" aria-disabled="true">Previous</span>
                                @else
                                    <a class="btn xs ghost" href="{{ $reviews->previousPageUrl() }}">Previous</a>
                                @endif
                                @if ($reviews->hasMorePages())
                                    <a class="btn xs ghost" href="{{ $reviews->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="btn xs ghost" aria-disabled="true">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        @push('scripts')
            <script>
                /* Admin reviews: confirm before a review is removed. */
                document.addEventListener("submit", function (event) {
                    var form = event.target.closest("form[data-confirm]");

                    if (form && !window.confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });
            </script>
        @endpush
    </x-app-layout>
