<?php

namespace App\Listeners;

use App\Events\OrderCreated as EventsOrderCreated;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventsOrderCreated $event): void
    {
        $order = $event->order;
        $user = $order->user;
        $user->notify(new OrderCreatedNotification($order,$user));
    }
}
