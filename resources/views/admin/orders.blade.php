
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
                        <div class="sub">Every purchase in your store.</div>
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
                            ><span class="tx"
                                ><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span
                            >
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
                                <div class="s">{{ $summary['orders_this_week'] }} this week</div>
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
                                <div class="k">Revenue</div>
                                <div class="v">{{ $summary['revenue_label'] }}</div>
                                <div class="s">{{ $summary['revenue_this_week_label'] }} this week</div>
                            </div>
                        </div>
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
                                    <rect height="12" rx="2" width="19" x="2.5" y="6"></rect>
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                    <path d="M6 10v4M18 10v4"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Average order</div>
                                <div class="v">{{ $summary['average_label'] }}</div>
                                <div class="s">
                                    @if ($summary['paid'] > 0)
                                        Across {{ $summary['paid'] }} {{ \Illuminate\Support\Str::plural('paid order', $summary['paid']) }}
                                    @else
                                        No paid orders yet
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="toolbar">
                        <form action="{{ route('admin.orders') }}" class="search" method="GET" role="search">
                            <svg
                                fill="none"
                                height="16"
                                stroke="currentColor"
                                stroke-width="2.4"
                                viewBox="0 0 24 24"
                                width="16"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="m20 20-3.5-3.5"></path>
                            </svg>
                            <input
                                aria-label="Search orders"
                                data-search="#orderRows"
                                data-search-empty="#noResults"
                                data-search-items="tr"
                                id="q"
                                name="q"
                                placeholder="Search by order, customer or plugin"
                                type="search"
                                value="{{ request('q') }}"
                            />
                        </form>
                        <span class="note">
                            {{ $summary['orders'] }} {{ \Illuminate\Support\Str::plural('order', $summary['orders']) }} · {{ $summary['paid'] }} paid
                            @if ($summary['awaiting'] > 0)
                                · {{ $summary['awaiting'] }} awaiting payment
                            @endif
                            @if ($summary['failed'] > 0)
                                · {{ $summary['failed'] }} failed
                            @endif
                        </span>
                    </div>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Plugins</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="orderRows">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="num"><b>{{ $order->reference }}</b></td>
                                        <td class="num">
                                            {{ ($order->paid_at ?? $order->created_at)->format('d M Y') }}
                                            <span class="sub">{{ ($order->paid_at ?? $order->created_at)->format('h:i A') }}</span>
                                        </td>
                                        <td>
                                            <b>{{ $order->user?->name ?? 'Unknown customer' }}</b>
                                            <span class="sub">{{ $order->user?->email }}</span>
                                        </td>
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
                                            <div class="acts">
                                                <button
                                                    class="btn xs ghost"
                                                    data-email="{{ $order->user?->email }}"
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
                                                    >Resend links
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="note" colspan="7">
                                            No orders yet. Orders appear here as soon as a shopper checks out.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr data-search-ignore="" hidden="" id="noResults">
                                    <td class="note" colspan="7">No orders match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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
                /* Admin orders: re-send download links to the customer. (Search is in ui.js.) */
                (function () {
                    "use strict";
                    document.getElementById("orderRows").addEventListener("click", function (e) {
                        var btn = e.target.closest("[data-resend]");
                        if (!btn) return;
                        // DEMO: Laravel emails the download links for this order.
                        Toon.toast("Download links sent to " + (btn.dataset.email || "the customer"));
                    });
                })();
            </script>
        @endpush
    </x-app-layout>
