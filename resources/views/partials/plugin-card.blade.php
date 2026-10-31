@php
    $price = (float) $plugin->price;
    $priceLabel = $plugin->priceLabel();
    $oldPriceLabel = $plugin->oldPriceLabel();
    $badge = $plugin->badgeLabel();
    $hosts = $plugin->hostTags();
    $coverUrl = $plugin->coverUrl();
    $url = route('plugins.show', $plugin->slug);
    $image = $coverUrl ?: asset('assets/images/logo.png');
@endphp

<article
    class="card"
    data-category="{{ $plugin->category }}"
    data-features="{{ str_replace(["\r\n", "\r", "\n"], '|', (string) $plugin->features) }}"
    data-hosts="{{ implode(', ', array_column($hosts, 'name')) }}"
    data-id="{{ $plugin->id }}"
    data-image="{{ $image }}"
    data-name="{{ $plugin->name }}"
    data-price="{{ $price }}"
    data-product=""
    data-rating="0"
    data-reviews="0"
    data-tagline="{{ $plugin->tagline }}"
    data-tutorial="{{ filled($plugin->youtube_url) ? '1' : '0' }}"
    data-url="{{ $url }}"
    data-was="{{ $oldPriceLabel ? (float) $plugin->old_price : '' }}"
>
    <span class="sticker {{ $price <= 0 ? 'free' : '' }}"
        >{{ $priceLabel }}@if ($oldPriceLabel)<s>{{ $oldPriceLabel }}</s>@endif</span
    >
    <a aria-label="View {{ $plugin->name }}" class="cover" href="{{ $url }}"
        >@if ($coverUrl)
            <img alt="" loading="lazy" src="{{ $coverUrl }}" />
        @else
            <span class="cover-art" style="--hue: {{ $plugin->coverHue() }}">{{ $plugin->initials() }}</span>
        @endif
        @if ($badge)<span class="flag">{{ $badge }}</span>@endif
        @if (filled($plugin->youtube_url))<span class="vidflag">▶ Tutorial</span>@endif</a
    >
    <div class="cbody">
        <div class="cmeta">
            <span>{{ $plugin->category }}</span>
            <span class="host-list">
                @foreach ($hosts as $host)
                    <span class="host host-{{ strtolower($host['code']) }}" title="{{ $host['name'] }}">{{ $host['code'] }}</span>
                @endforeach
            </span>
        </div>
        <h3><a class="plain-link" href="{{ $url }}">{{ $plugin->name }}</a></h3>
        <span class="rate"
            ><span aria-label="No ratings yet" class="stars"
                ><i class="off">★</i><i class="off">★</i><i class="off">★</i><i class="off">★</i><i
                    class="off"
                    >★</i
                ></span
            >
            No ratings yet</span
        >
        <p>{{ $plugin->tagline }}</p>
        <div class="cfoot">
            <button
                class="btn sm"
                data-add="{{ $plugin->id }}"
                data-in-label="✓ In cart"
                data-out-label="{{ $price <= 0 ? 'Get it free' : 'Add to cart' }}"
                type="button"
            >
                {{ $price <= 0 ? 'Get it free' : 'Add to cart' }}
            </button>
            <a class="btn sm white" href="{{ $url }}">Preview</a>
        </div>
    </div>
</article>
