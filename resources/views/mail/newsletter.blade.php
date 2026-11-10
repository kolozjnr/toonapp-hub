<x-mail::message>
# {{ $headline }}

{{ str_replace("\n", "  \n", $body) }}

@if ($downloadUrl)
<x-mail::button :url="$downloadUrl">
Download the update
</x-mail::button>
@endif

Your downloads stay in **My plugins** on {{ config('app.name') }}.

<x-slot:subcopy>
    You're getting this because you asked for plugin updates. You can turn them off any time in your dashboard.
</x-slot:subcopy>
</x-mail::message>
