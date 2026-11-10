
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
                        <h1>Dashboard</h1>
                        <div class="sub">How your store is doing.</div>
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
                            ><span class="tx"><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></span>
                        </div>
                    </div>
                </header>
                <main class="content" id="content">
                    <section class="hello">
                        <div class="hello-l">
                            <svg aria-hidden="true" class="toon-peek" viewBox="40 50 290 300">
                                <path
                                    d="M60 250 C 60 130, 115 60, 180 60 S 300 130, 300 250 C 300 310, 250 335, 180 335 S 60 310, 60 250 Z"
                                    fill="#7B5CFF"
                                    stroke="#1B1530"
                                    stroke-width="8"
                                ></path>
                                <path
                                    d="M95 150 C 110 110, 140 92, 165 88"
                                    fill="none"
                                    stroke="#fff"
                                    stroke-linecap="round"
                                    stroke-opacity=".55"
                                    stroke-width="10"
                                ></path>
                                <ellipse
                                    cx="135"
                                    cy="190"
                                    fill="#fff"
                                    rx="30"
                                    ry="34"
                                    stroke="#1B1530"
                                    stroke-width="6"
                                ></ellipse>
                                <ellipse
                                    cx="225"
                                    cy="190"
                                    fill="#fff"
                                    rx="30"
                                    ry="34"
                                    stroke="#1B1530"
                                    stroke-width="6"
                                ></ellipse>
                                <circle cx="140" cy="196" fill="#1B1530" r="13"></circle>
                                <circle cx="230" cy="196" fill="#1B1530" r="13"></circle>
                                <ellipse cx="102" cy="240" fill="#FF5C8A" opacity=".85" rx="16" ry="9"></ellipse>
                                <ellipse cx="258" cy="240" fill="#FF5C8A" opacity=".85" rx="16" ry="9"></ellipse>
                                <path
                                    d="M150 248 Q180 284 210 248"
                                    fill="#FF5C8A"
                                    stroke="#1B1530"
                                    stroke-linejoin="round"
                                    stroke-width="7"
                                ></path>
                            </svg>
                            <div>
                                <h2><span data-greeting="">Hello</span>, boss 👋</h2>
                                <p>{{ $summary['orders'] }} {{ \Illuminate\Support\Str::plural('order', $summary['orders']) }} so far and {{ $pluginCount }} {{ \Illuminate\Support\Str::plural('plugin', $pluginCount) }} on your shelf. Keep them coming!</p>
                            </div>
                        </div>
                        <a class="btn white" href="{{route('admin.plugins.create')}}"
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
                                <path d="M12 16V4m-5 5 5-5 5 5"></path>
                                <path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"></path></svg
                            >Upload plugin</a
                        >
                    </section>
                    <section class="kpis">
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
                                <div class="s">All time · {{ $summary['paid'] }} {{ \Illuminate\Support\Str::plural('paid order', $summary['paid']) }}</div>
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
                                    <circle cx="9" cy="8" r="3.5"></circle>
                                    <path d="M2.5 20c.8-3.5 3.4-5.5 6.5-5.5s5.7 2 6.5 5.5"></path>
                                    <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.8c1.9.7 3.1 2.5 3.5 5.2"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Customers</div>
                                <div class="v">{{ $customerCount }}</div>
                                <div class="s">Registered</div>
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
                                <div class="k">Awaiting payment</div>
                                <div class="v">{{ $summary['awaiting'] }}</div>
                                <div class="s">Unpaid checkouts</div>
                            </div>
                        </div>
                    </section>
                    <section class="panel">
                        <div class="panel-h">
                            <div>
                                <h2>Orders, last 14 days</h2>
                                <p>{{ $summary['orders_this_week'] }} this week · {{ $summary['orders'] }} all time</p>
                            </div>
                        </div>
                        <div aria-label="Daily orders for the last 14 days" class="vchart" role="img">
                            @foreach ($dailyOrders as $day)
                                <div class="vcol" title="{{ $day['date'] }}: {{ $day['count'] }} {{ \Illuminate\Support\Str::plural('order', $day['count']) }}">
                                    <span class="vn">{{ $day['count'] }}</span><span
                                        class="vbar h-{{ $day['height'] }}{{ $loop->last ? ' today' : '' }}"
                                    ></span><span class="vd">{{ $day['show_label'] ? $day['label'] : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                    <div class="grid2">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Sales by plugin</h2>
                                    <p>Revenue per plugin, all time.</p>
                                </div>
                                <a class="btn ghost sm" href="{{ route('admin.plugins') }}">All plugins</a>
                            </div>
                            <div class="bars">
                                @forelse ($salesByPlugin as $sale)
                                    <div class="barrow">
                                        <span class="nm">{{ $sale['name'] }}</span>
                                        <div class="track"><span class="w-{{ $sale['width'] }}"></span></div>
                                        <span class="amt">{{ $sale['label'] }}</span>
                                    </div>
                                @empty
                                    <div class="note">No paid orders yet. Sales per plugin appear here.</div>
                                @endforelse
                            </div>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Latest orders</h2>
                                    <p>Most recent purchases.</p>
                                </div>
                                <a class="btn ghost sm" href="{{ route('admin.orders') }}">All orders</a>
                            </div>
                            <div class="list">
                                @forelse ($latestOrders as $order)
                                    <div class="row-i">
                                        <div class="grow"><b>{{ $order->user?->name ?? 'Unknown customer' }}</b><small>{{ implode(', ', array_column($order->items ?: [], 'name')) ?: 'No plugins' }} · {{ $order->created_at->diffForHumans() }}</small></div>
                                        <b class="num">{{ $order->amountLabel() }}</b>
                                    </div>
                                @empty
                                    <div class="note">No orders yet. The latest purchases appear here.</div>
                                @endforelse
                            </div>
                        </section>
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Store storage</h2>
                                    <p>Space used by plugin files, images and data.</p>
                                </div>
                            </div>
                            <div class="meter"><span class="w-{{ $storage['width'] }}"></span></div>
                            <div class="note">
                                {{ $storage['used_label'] }} of {{ $storage['quota_label'] }} used ({{ $storage['percent'] }}%).
                                {{ $storage['files'] }} {{ \Illuminate\Support\Str::plural('file', $storage['files']) }} on the
                                public disk: covers and gallery images take the most space.
                            </div>
                            <div class="savebar mt-14">
                                <a class="btn ghost sm" href="{{ route('admin.settings') }}#backup"
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
                                        <path d="M12 4v11m-5-5 5 5 5-5"></path>
                                        <path d="M4 19h16"></path></svg
                                    >Back up store data</a
                                >
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
        
    </x-app-layout>>
