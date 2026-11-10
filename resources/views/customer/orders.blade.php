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
                        <h1>Orders</h1>
                        <div class="sub">Your purchase history and receipts.</div>
                    </div>
                    <div class="top-right">
                        <a class="btn ghost sm store-link" href="{{ route('home') }}#shop"
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
                                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6"></path>
                                <circle cx="10" cy="20.5" r="1"></circle>
                                <circle cx="18" cy="20.5" r="1"></circle></svg
                            ><span>Shop plugins</span></a
                        >
                        <div class="who">
                            <span class="av">AO</span
                            ><span class="tx"><b>{{ Auth::user()->name }}</b><small> {{ Auth::user()->email }} </small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="kpis">
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
                                    <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                                    <path d="M9 8h6M9 12h6"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Orders</div>
                                <div class="v">{{ $summary['orders'] }}</div>
                            </div>
                        </div>
                        <div class="kpi">
                            <span class="ic ok"
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
                                    <rect height="12" rx="2" width="19" x="2.5" y="6"></rect>
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                    <path d="M6 10v4M18 10v4"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Total spent</div>
                                <div class="v">{{ $summary['spent_label'] }}</div>
                            </div>
                        </div>
                    </section>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Plugins</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="num"><b>{{ $order->reference }}</b></td>
                                        <td class="num">{{ ($order->paid_at ?? $order->created_at)->format('d M Y') }}</td>
                                        <td>{{ implode(', ', array_column($order->items ?: [], 'name')) ?: '—' }}</td>
                                        <td class="num"><b>{{ $order->amountLabel() }}</b></td>
                                        <td>
                                            @if ($order->isSuccessful())
                                                <span class="badge ok">Paid</span>
                                            @elseif ($order->hasFailed())
                                                <span class="badge bad">Failed</span>
                                            @else
                                                <span class="badge warn">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->isSuccessful())
                                                <div class="acts">
                                                    <button
                                                        class="btn xs ghost"
                                                        data-email="{{ Auth::user()->email }}"
                                                        data-resend="{{ $order->reference }}"
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
                                                            <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                                            <path d="m4 7 8 6 8-6"></path></svg
                                                        >Email my links
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="note" colspan="6">
                                            No orders yet. Your receipts appear here as soon as you check out.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="note">
                        “Email my links” sends the download links for that order to {{ Auth::user()->email }} again.
                    </p>

                    @if ($orders->hasPages())
                        <div class="toolbar" style="margin-top: 16px;">
                            <span class="note">
                                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}
                            </span>
                            <div class="acts ml-auto">
                                @if ($orders->onFirstPage())
                                    <span class="btn xs ghost" aria-disabled="true">Previous</span>
                                @else
                                    <a class="btn xs ghost" href="{{ $orders->previousPageUrl() }}">Previous</a>
                                @endif
                                @if ($orders->hasMorePages())
                                    <a class="btn xs ghost" href="{{ $orders->nextPageUrl() }}">Next</a>
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
                /* Customer orders: re-send the download links for an order. */
                (function () {
                    "use strict";
                    document.getElementById("content").addEventListener("click", function (e) {
                        var btn = e.target.closest("[data-resend]");
                        if (!btn) return;
                        // DEMO: Laravel emails the download links for this order.
                        Toon.toast("Download links sent to " + (btn.dataset.email || "your address"));
                    });
                })();
            </script>
        @endpush
    </x-app-layout>