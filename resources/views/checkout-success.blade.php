<x-guest-layout title="Payment result">
    @php
        $amount = number_format($payment->amount / 100, 2, '.', ',').' '.$payment->currency;
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
            <h1>{{ $payment->isSuccessful() ? 'Thank you!' : 'Your payment' }}</h1>
        </div>

        <div id="checkout">
            @if ($payment->isSuccessful())
                <section class="panel2 co-ok">
                    <span aria-hidden="true" class="big-ic"
                        ><svg
                            fill="none"
                            height="40"
                            stroke="#1B1530"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="3"
                            viewBox="0 0 24 24"
                            width="40"
                        >
                            <path d="M5 12.5l4.5 4.5L19 7"></path></svg
                    ></span>
                    <h2 style="font-size: 34px">Your order is confirmed</h2>
                    <p>
                        We charged <b>{{ $amount }}</b> for
                        {{ collect($payment->items)->pluck('name')->join(', ') }}.
                    </p>
                    <p>Reference <b>{{ $payment->reference }}</b></p>
                    <div class="cta" style="justify-content: center">
                        <a class="btn mint" href="{{ route('customer.plugins') }}">Go to My plugins</a>
                        <a class="btn white" href="{{ route('home') }}#shop">Keep shopping</a>
                    </div>
                </section>
            @elseif ($payment->hasFailed())
                <section class="panel2 co-ok">
                    <h2 style="font-size: 30px">That payment didn't go through</h2>
                    <p>{{ $payment->failure_reason ?: 'Paystack could not complete this payment.' }}</p>
                    <p>Reference <b>{{ $payment->reference }}</b></p>
                    <div class="cta" style="justify-content: center">
                        <a class="btn pink" href="{{ route('checkout.create') }}">Try again</a>
                        <a class="btn white" href="{{ route('home') }}#shop">Keep shopping</a>
                    </div>
                    <span class="note">If you were charged, contact support with your reference.</span>
                </section>
            @else
                <section class="panel2 co-ok">
                    <h2 style="font-size: 30px">Confirming your payment</h2>
                    <p>
                        We're checking <b>{{ $amount }}</b> against Paystack. Reference
                        <b>{{ $payment->reference }}</b>
                    </p>
                    <p>This usually completes within a few seconds of returning from Paystack.</p>
                    <div class="cta" style="justify-content: center">
                        <form action="{{ route('checkout.recheck') }}" method="post" style="display: contents">
                            @csrf
                            <input name="reference" type="hidden" value="{{ $payment->reference }}" />
                            <button class="btn pink" type="submit">Check again</button>
                        </form>
                        <a class="btn white" href="{{ route('home') }}#shop">Keep shopping</a>
                    </div>
                </section>
            @endif
        </div>
    </main>
</x-guest-layout>
