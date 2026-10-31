<div class="scrim" id="cartScrim"></div>
<aside aria-label="Cart" class="drawer" id="cartDrawer">
    <div class="dh">
        <h3>Your cart</h3>
        <button aria-label="Close" class="x" data-close="" type="button">×</button>
    </div>
    <div class="dbody" id="cartBody">
        <div class="empty flat" hidden="" id="cartEmpty">
            <h3>Cart's empty</h3>
            <p>Add a plugin and it'll wait here for you.</p>
        </div>
    </div>
    <div class="dfoot" id="cartFoot" hidden>
        <div class="row total"><span>Total</span><span id="cartTotal">$0</span></div>
        <a class="btn pink" href="{{ route('login') }}" id="toCheckout">Log in to checkout</a>
        <span class="note text-center"
            >New here? <a class="link" href="{{ route('register') }}">Create an account</a></span
        >
        <span class="note text-center">One-time payment · Instant download</span>
    </div>
    <div class="dfoot" id="cartFootEmpty">
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
