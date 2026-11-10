@php
    $rating = (float) $rating;
@endphp
<span aria-label="{{ number_format($rating, 1) }} out of 5" class="stars"
    >@for ($star = 1; $star <= 5; $star++)<i class="{{ $rating >= $star - 0.5 ? '' : 'off' }}">★</i>@endfor</span
>
