<x-mail::message>
# Hi , {{ $user->first_name }}

Order of number : {{ $order->id }} created success please complete payment
<br><br>
Thank you for choosing to deal with us. Best regards, <strong>{{ config('app.name') }}</strong> Company.

Thanks, {{ $user->first_name }}<br>
{{ config('app.name') }}
</x-mail::message>
