<?php

namespace App\Jobs;

use App\Helpers\FirebaseSendNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FirebaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $token,public $title,public $body,public $image = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (is_array($this->token)) {
            foreach($this->token as $user_token) {
                FirebaseSendNotification::send($user_token,$this->title,$this->body,$this->image);
            }
        }
         FirebaseSendNotification::send($this->token,$this->title,$this->body,$this->image);
    }
}
