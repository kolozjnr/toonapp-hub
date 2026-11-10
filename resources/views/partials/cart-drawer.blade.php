@php
    $items = $cartItems ?? [];
    $count = $cartCount ?? 0;
    $total = $cartTotal ?? 0;
    $money = fn (float $value): string => '$'.number_format($value, fmod($value, 1.0) > 0 ? 2 : 0);
@endphp

<div class="scrim" id="cartScrim"></div>
<aside aria-label="Cart" class="drawer" id="cartDrawer">
    <div class="dh">
        <h3>Your cart</h3>
        <button aria-label="Close" class="x" data-close="" type="button">×</button>
    </div>
    <div class="dbody" id="cartBody">
        @forelse ($items as $item)
            <div class="li" data-cart-item="{{ $item['plugin_id'] }}" data-price="{{ $item['price'] }}">
                <a class="th" href="{{ $item['url'] }}"><img alt="" src="{{ $item['image'] }}" /></a>
                <div class="min-w-0">
                    <b>{{ $item['name'] }}</b><small>{{ $item['category'] }}</small><br />
                    <button class="link" data-rm="{{ $item['plugin_id'] }}" type="button">Remove</button>
                </div>
                <span class="p">{{ $item['price_label'] }}</span>
            </div>
        @empty
            <div class="empty flat" id="cartEmpty">
                <h3>Cart's empty</h3>
                <p>Add a plugin and it'll wait here for you.</p>
            </div>
        @endforelse
    </div>
    <div class="dfoot" @if (! $count) hidden @endif id="cartFoot">
        <div class="row total"><span>Total</span><span id="cartTotal">{{ $money((float) $total) }}</span></div>
        @auth
            <a class="btn pink" href="{{ route('checkout.create') }}" id="toCheckout">Pay now</a>
        @else
            <a class="btn pink" href="{{ route('login') }}" id="toCheckout">Log in to check out</a>
            <span class="note text-center"
                >New here? <a class="link" href="{{ route('register') }}">Create an account</a></span
            >
        @endauth
        <span class="note text-center">One-time payment · Instant download</span>
    </div>
    <div class="dfoot" @if ($count) hidden @endif id="cartFootEmpty">
        <a class="btn" data-close="" href="{{ route('home') }}#shop">Browse plugins</a>
    </div>
</aside>
<template id="cartItemTemplate"
    ><div class="li" data-cart-item="" data-price="">
        <a class="th" href=""><img alt="" src="" /></a>
        <div class="min-w-0">
            <b></b><small></small><br /><button class="link" data-rm="" type="button">Remove</button>
        </div>
        <span class="p"></span></div
></template>
