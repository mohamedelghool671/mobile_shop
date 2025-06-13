<?php

namespace App\Listeners;

use App\Events\OrderDeliverd as EventsOrderDeliverd;
use App\Notifications\OrderDeliverd as NotificationsOrderDeliverd;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderDeliverd
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
    public function handle(EventsOrderDeliverd $event): void
    {
        $order = $event->order;
        $user = $event->order->user;
        $user->notify(new NotificationsOrderDeliverd($order,$user));
    }
}
