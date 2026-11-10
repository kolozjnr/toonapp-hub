
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
                        <h1>Customers</h1>
                        <div class="sub">Everyone who has registered.</div>
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
                                    <circle cx="9" cy="8" r="3.5"></circle>
                                    <path d="M2.5 20c.8-3.5 3.4-5.5 6.5-5.5s5.7 2 6.5 5.5"></path>
                                    <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.8c1.9.7 3.1 2.5 3.5 5.2"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Customers</div>
                                <div class="v">{{ $summary['customers'] }}</div>
                                <div class="s">Registered</div>
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
                                    <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"></path>
                                    <path d="M9 8h6M9 12h6"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Buyers</div>
                                <div class="v">{{ $summary['buyers'] }}</div>
                                <div class="s">Completed at least one order</div>
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
                                    <rect height="14" rx="2" width="18" x="3" y="5"></rect>
                                    <path d="m4 7 8 6 8-6"></path></svg
                            ></span>
                            <div class="min-w-0">
                                <div class="k">Newsletter</div>
                                <div class="v">{{ $summary['newsletter'] }}</div>
                                <div class="s">Subscribed</div>
                            </div>
                        </div>
                    </section>
                    <div class="toolbar">
                        <form action="{{ route('admin.customers') }}" class="search" method="GET" role="search">
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
                                aria-label="Search customers"
                                data-search="#customerRows"
                                data-search-empty="#noResults"
                                data-search-items="tr"
                                id="q"
                                name="q"
                                placeholder="Search by name, email or country"
                                type="search"
                                value="{{ request('q') }}"
                            />
                        </form>
                        <span class="note">
                            {{ $summary['customers'] }} {{ \Illuminate\Support\Str::plural('customer', $summary['customers']) }}
                            · {{ $summary['buyers'] }} bought
                            · {{ $summary['newsletter'] }} subscribed
                        </span>
                        @if ($subscriberEmails !== '')
                            <button
                                class="btn ghost sm ml-auto"
                                data-copy="{{ $subscriberEmails }}"
                                data-copy-msg="Subscriber emails copied"
                                id="copyAll"
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
                                    <rect height="12" rx="2" width="12" x="8" y="8"></rect>
                                    <path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"></path></svg
                                >Copy subscriber emails
                            </button>
                        @endif
                    </div>
                    <div class="tbl-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Newsletter</th>
                                    <th>Joined</th>
                                    <th>Orders</th>
                                    <th>Spent</th>
                                </tr>
                            </thead>
                            <tbody id="customerRows">
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td>
                                            <b>{{ $customer->name }}</b>
                                            <span class="sub"
                                                >{{ $customer->email_verified_at ? 'Email confirmed' : 'Email not confirmed' }}</span
                                            >
                                        </td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->country ?: '—' }}</td>
                                        <td>
                                            @if ($customer->newsletter)
                                                <span class="badge ok">Yes</span>
                                            @else
                                                <span class="badge">No</span>
                                            @endif
                                        </td>
                                        <td class="num">{{ $customer->created_at->format('d M Y') }}</td>
                                        <td class="num">{{ (int) $customer->orders_count }}</td>
                                        <td class="num">
                                            <b>{{ \App\Models\Payment::formatAmount((int) ($customer->spent ?? 0), $currency) }}</b>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="note" colspan="7">
                                            @if (request('q'))
                                                No customers match your search.
                                            @else
                                                No customers yet. Customers appear here as soon as someone registers.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                                <tr data-search-ignore="" hidden="" id="noResults">
                                    <td class="note" colspan="7">No customers match your search.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if ($customers->hasPages())
                        <div class="toolbar" style="margin-top: 16px;">
                            <span class="note">
                                Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ $customers->total() }}
                            </span>
                            <div class="acts ml-auto">
                                @if ($customers->onFirstPage())
                                    <span class="btn xs ghost" aria-disabled="true">Previous</span>
                                @else
                                    <a class="btn xs ghost" href="{{ $customers->previousPageUrl() }}">Previous</a>
                                @endif
                                @if ($customers->hasMorePages())
                                    <a class="btn xs ghost" href="{{ $customers->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="btn xs ghost" aria-disabled="true">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    
    </x-app-layout>
