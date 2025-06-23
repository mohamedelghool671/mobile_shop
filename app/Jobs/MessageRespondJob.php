<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Notifications\ContactNotification;
use Illuminate\Support\Facades\Notification;

class MessageRespondJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $userEmail ,public $userName, public $responseText,public $senderEmail)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info("$this->userEmail");
         Notification::route('mail', $this->userEmail)
            ->notify(new ContactNotification($this->senderEmail, $this->userName, $this->responseText));
    }
}
