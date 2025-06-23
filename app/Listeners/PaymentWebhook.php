<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Cashier\Events\WebhookReceived;

class PaymentWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }


    public function handle(WebhookReceived $event): void
    {
        Log::info($event->payload);
        if ($event->payload['type'] === 'charge.succeeded') {
            Log::info('success pay');
        }
    }
}
