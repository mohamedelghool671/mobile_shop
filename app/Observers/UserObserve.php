<?php

namespace App\Observers;

use App\Models\User;
use App\Events\RegisterVerfiyEvent;

class UserObserve
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->profile()->create([
                'name' => $user->name,
                "email" => $user->email,
                "phone" => $user->phone ?? null
            ]);
         event(new RegisterVerfiyEvent($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
