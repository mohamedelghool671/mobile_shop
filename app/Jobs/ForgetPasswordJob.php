<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgetPasswordJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user,public $resetCode)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
         Mail::to($user->email)->send(new ResetPassword($user->name, $this->resetCode));
    }
}
