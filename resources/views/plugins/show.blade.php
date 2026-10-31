@php
    $price = (float) $plugin->price;
    $priceLabel = $plugin->priceLabel();
    $oldPriceLabel = $plugin->oldPriceLabel();
    $badge = $plugin->badgeLabel();
    $hosts = $plugin->hostTags();
    $hostNames = implode(', ', array_column($hosts, 'name'));
    $coverUrl = $plugin->coverUrl();
    $fallbackImage = asset('assets/images/logo.png');
    $productUrl = route('plugins.show', $plugin->slug);
    $hue = $plugin->coverHue();
    $addLabel = $price <= 0 ? 'Get it free' : 'Add to cart';
    $gallery = collect($plugin->gallery ?? [])
        ->filter(fn ($image) => ! empty($image['path']))
        ->values();
    $features = collect(preg_split('/\r\n|\r|\n|\|/', (string) $plugin->features))
        ->map(fn ($feature) => trim($feature))
        ->filter()
        ->values();
@endphp

<x-guest-layout :title="$plugin->name" :description="$plugin->tagline ?: 'Plugin details, tutorial and gallery.'">
    <main
        class="wrap"
        data-category="{{ $plugin->category }}"
        data-features="{{ $features->implode('|') }}"
        data-hosts="{{ $hostNames }}"
        data-id="{{ $plugin->id }}"
        data-image="{{ $coverUrl ?: $fallbackImage }}"
        data-name="{{ $plugin->name }}"
        data-price="{{ $price }}"
        data-product=""
        data-rating="0"
        data-reviews="0"
        data-tagline="{{ $plugin->tagline }}"
        data-tutorial="{{ filled($plugin->youtube_url) ? '1' : '0' }}"
        data-url="{{ $productUrl }}"
        data-was="{{ $oldPriceLabel ? (float) $plugin->old_price : '' }}"
        id="pp"
    >
        <nav class="crumbs">
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
                >Back to shop</a
            >
        </nav>

        <section class="pp-hero" style="--h: {{ $hue }}">
            <span aria-hidden="true" class="blob b1"></span>
            <span aria-hidden="true" class="blob b2"></span>
            <span aria-hidden="true" class="spark s1">★</span>
            <span aria-hidden="true" class="spark s2">✦</span>
            <span aria-hidden="true" class="spark s3">★</span>

            <div class="pp-cover">
                @if ($coverUrl)
                    <img alt="{{ $plugin->name }} cover" src="{{ $coverUrl }}" />
                @else
                    <span class="cover-art" style="--hue: {{ $hue }}">{{ $plugin->initials() }}</span>
                @endif
                @if ($badge)
                    <span class="flag flag-top">{{ $badge }}</span>
                @endif
            </div>

            <div class="pp-head">
                <div class="cmeta cmeta-left">
                    <span>{{ $plugin->category }}</span>
                    @foreach ($hosts as $host)
                        <span class="host host-{{ strtolower($host['code']) }}" title="{{ $host['name'] }}">
                            {{ $host['code'] }}
                        </span>
                    @endforeach
                </div>
                <h1>{{ $plugin->name }}</h1>
                <span class="rate"
                    ><span aria-label="No ratings yet" class="stars"
                        ><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i><i
                            class="off"
                            >★</i
                        ></span
                    >
                    No ratings yet</span
                >
                @if ($plugin->tagline)
                    <p class="pp-tag">{{ $plugin->tagline }}</p>
                @endif
                <div class="pp-cta">
                    <span class="pp-price"
                        >{{ $priceLabel }}@if ($oldPriceLabel)<s>{{ $oldPriceLabel }}</s>@endif</span
                    >
                    <button
                        class="btn pink"
                        data-add="{{ $plugin->id }}"
                        data-in-label="Go to cart"
                        data-open-cart
                        data-out-label="{{ $addLabel }}"
                        type="button"
                    >
                        {{ $addLabel }}
                    </button>
                    @if ($plugin->youtube_url)
                        <a
                            class="btn white"
                            href="{{ $plugin->youtube_url }}"
                            rel="noopener noreferrer"
                            target="_blank"
                            ><svg height="16" viewBox="0 0 24 24" width="16">
                                <path
                                    d="M7 4.5v15l13-7.5z"
                                    fill="#E5334B"
                                    stroke="currentColor"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                ></path></svg
                            >Watch tutorial</a
                        >
                    @endif
                </div>
            </div>
        </section>

        <div class="pp-body">
            <div class="pp-main">
                @if ($plugin->description)
                    <section class="pp-card">
                        <h2>About this plugin</h2>
                        <p class="pp-desc">{{ $plugin->description }}</p>
                    </section>
                @endif

                @if ($features->isNotEmpty())
                    <section class="pp-card">
                        <h2>What you get</h2>
                        <div class="fgrid">
                            @foreach ($features as $feature)
                                <div class="fcard">
                                    <span aria-hidden="true" class="fstar">★</span><span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($plugin->youtube_url)
                    <a
                        class="yt pp-yt"
                        href="{{ $plugin->youtube_url }}"
                        rel="noopener noreferrer"
                        target="_blank"
                        ><span class="play"
                            ><svg height="22" viewBox="0 0 24 24" width="22">
                                <path
                                    d="M7 4.5v15l13-7.5z"
                                    fill="#E5334B"
                                    stroke="#1B1530"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                ></path></svg></span
                        ><span
                            ><b>Watch the step-by-step tutorial</b
                            ><small>See {{ $plugin->name }} in action on YouTube</small></span
                        ></a
                    >
                @endif

                @if ($gallery->isNotEmpty())
                    <section class="pp-card">
                        <h2>Inside the plugin</h2>
                        <div class="shots-list">
                            @foreach ($gallery as $shot)
                                <div class="shot {{ $loop->even ? 'flip' : '' }}">
                                    <button
                                        aria-label="Enlarge image: {{ $shot['title'] ?? $plugin->name }}"
                                        class="shot-img"
                                        data-zoom="{{ $shot['path'] }}"
                                        type="button"
                                    >
                                        <img
                                            alt="{{ $shot['title'] ?? 'Plugin gallery image' }}"
                                            src="{{ asset('storage/'.$shot['path']) }}"
                                        />
                                    </button>
                                    @if (! empty($shot['title']) || ! empty($shot['text']))
                                        <div class="shot-txt">
                                            @if (! empty($shot['title']))
                                                <h4>{{ $shot['title'] }}</h4>
                                            @endif
                                            @if (! empty($shot['text']))
                                                <p>{{ $shot['text'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="pp-card" id="reviews">
                    <div class="reviews">
                        <h3>Ratings &amp; reviews</h3>
                        <div class="mini">
                            <span>No ratings yet.</span>
                            <span class="note">Reviews will appear here once customers rate this plugin.</span>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="pp-side">
                <div class="pp-card buybox">
                    <span class="note eyebrow">One-time price</span>
                    <span class="big-price"
                        >{{ $priceLabel }}@if ($oldPriceLabel)<s>{{ $oldPriceLabel }}</s>@endif</span
                    >
                    <button
                        class="btn pink"
                        data-add="{{ $plugin->id }}"
                        data-in-label="Go to cart"
                        data-open-cart
                        data-out-label="{{ $addLabel }}"
                        type="button"
                    >
                        {{ $addLabel }}
                    </button>
                    <dl class="specs">
                        <dt>Works with</dt>
                        <dd>{{ $hostNames ?: 'See description' }}</dd>
                        @if ($plugin->requirements)
                            <dt>Requirements</dt>
                            <dd>{{ $plugin->requirements }}</dd>
                        @endif
                        @if ($plugin->version)
                            <dt>Version</dt>
                            <dd>{{ $plugin->version }}</dd>
                        @endif
                        <dt>License</dt>
                        <dd>1 user, commercial use</dd>
                    </dl>
                    <ul class="perks">
                        <li>Instant download after payment</li>
                        <li>Free updates for this version</li>
                        <li>Step-by-step tutorial support</li>
                    </ul>
                </div>
            </aside>
        </div>

        @if ($related->isNotEmpty())
            <section class="pp-more">
                <h2>You might also like</h2>
                <div class="more-grid">
                    @foreach ($related as $item)
                        <a class="more-card" href="{{ route('plugins.show', $item->slug) }}">
                            <span class="mc-cover">
                                @if ($item->coverUrl())
                                    <img alt="" src="{{ $item->coverUrl() }}" />
                                @else
                                    <span class="cover-art" style="--hue: {{ $item->coverHue() }}">
                                        {{ $item->initials() }}
                                    </span>
                                @endif
                            </span>
                            <span class="mc-body">
                                <b>{{ $item->name }}</b>
                                <span class="mc-price">{{ $item->priceLabel() }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <footer class="site-foot">
        <div class="wrap foot-in">
            <span>© 2026 ToonApp Hub</span><span
                ><a href="{{ route('contact') }}">Contact us</a> · Made for animators</span
            >
        </div>
    </footer>

    @include('partials.cart-drawer')

    <div class="lightbox" hidden="" id="lightbox">
        <figure>
            <img alt="" id="lbImg" />
            <figcaption id="lbCap"></figcaption>
        </figure>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/pages/plugin.js') }}"></script>
    @endpush
</x-guest-layout>
