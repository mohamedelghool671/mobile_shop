<?php

namespace App\Listeners;

use App\Jobs\EmailVerficationJob;
use App\Events\RegisterVerfiyEvent;
use App\Helpers\ApiResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterVerfiyListener
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
    public function handle(RegisterVerfiyEvent $event): void
    {
         $user=$event->user;
         if ($user) {
             dispatch(new EmailVerficationJob($user))->delay(5);
         }
    }
}
