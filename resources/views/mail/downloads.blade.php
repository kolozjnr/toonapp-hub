<x-mail::message>
# Your downloads are ready

Hi {{ $recipientName }},

Thanks for your order **{{ $reference }}**. Everything you bought is one click away:

@foreach ($downloads as $download)
## {{ $download['name'] }}

<x-mail::button :url="$download['url']">
Download Plugin
</x-mail::button>

@endforeach
Your plugins also stay in **My plugins** on {{ config('app.name') }}, so you can pull them down again whenever you need to reinstall.

Need a hand with an install? Just reply to this email.

<x-slot:subcopy>
Order {{ $reference }} · Sent by {{ config('app.name') }}
</x-slot:subcopy>
</x-mail::message>
