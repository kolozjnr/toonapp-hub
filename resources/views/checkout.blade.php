<x-guest-layout title="Checkout">
    @php
        $money = fn (float $value): string => '$'.number_format($value, fmod($value, 1.0) > 0 ? 2 : 0);
        $user = auth()->user();
    @endphp

    <main class="wrap page-pad">
        <div class="contact-head head-compact">
            <a class="btn sm white" href="{{ route('home') }}#shop"
                ><svg
                    fill="none"
                    height="16"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="3"
                    viewBox="0 0 24 24"
                    width="16"
                >
                    <path d="M15 5l-7 7 7 7"></path></svg
                >Keep shopping</a
            >
            <h1 id="coTitle">Checkout</h1>
        </div>

        <div id="checkout">
            @if (! count($items))
                <div class="empty flat">
                    <h3>Your cart is empty</h3>
                    <p>Add a plugin first, then come back here to pay.</p>
                    <a class="btn" href="{{ route('home') }}#shop">Browse plugins</a>
                </div>
            @else
                <div class="co">
                    <section class="panel2">
                        <h3>Payment</h3>
                        <form action="{{ route('checkout.pay') }}" class="form" id="payForm" method="post">
                            @csrf
                            {{-- One key per checkout page: posting it twice cannot open two payments. --}}
                            <input name="idempotency_key" type="hidden" value="{{ $idempotencyKey }}" />

                            <div class="mini">
                                <span
                                    ><b>{{ $user->name }}</b><small>{{ $user->email }}</small></span
                                >
                            </div>

                            <p class="note">
                                You'll be handed to Paystack's secure checkout to pay
                                <b>{{ $money((float) $total) }}</b>. We confirm the charge against this total before your
                                plugins are unlocked.
                            </p>

                            <button class="btn pink" id="payBtn" type="submit">Pay now · {{ $money((float) $total) }}</button>
                            <span class="note">One-time payment · Instant download</span>
                        </form>
                    </section>

                    <aside class="co-sum panel2">
                        <h3>Order summary</h3>
                        <div class="co-list" id="coList">
                            @foreach ($items as $item)
                                <div class="li" data-price="{{ $item['price'] }}">
                                    <a class="th" href="{{ $item['url'] }}"><img alt="" src="{{ $item['image'] }}" /></a>
                                    <div class="min-w-0">
                                        <b>{{ $item['name'] }}</b><small>{{ $item['category'] }}</small><br />
                                        <form action="{{ route('cart.remove') }}" method="post" style="display: contents">
                                            @csrf
                                            <input name="plugin_id" type="hidden" value="{{ $item['plugin_id'] }}" />
                                            <button class="link" type="submit">Remove</button>
                                        </form>
                                    </div>
                                    <span class="p">{{ $item['price_label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="row total"><span>Total</span><span>{{ $money((float) $total) }}</span></div>
                    </aside>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            document.getElementById('payForm')?.addEventListener('submit', function (event) {
                var button = document.getElementById('payBtn');

                if (button.disabled) {
                    event.preventDefault();

                    return;
                }

                button.disabled = true;
                button.textContent = 'Taking you to Paystack…';
            });
        </script>
    @endpush
</x-guest-layout>
