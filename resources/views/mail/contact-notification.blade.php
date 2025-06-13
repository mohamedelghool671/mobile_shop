<x-mail::message>
# Hello {{ $reciver }}

<strong>{{ $reply['header'] }}</strong>

{{ $reply['body'] }}

<strong>{{ $reply['footer'] ?? '' }}</strong>

Thanks, {{ $reciver }}<br>
Support Team <br>
thank you for contact with us
bist wishes <strong>{{ config('app.name') }}</strong>
</x-mail::message>
