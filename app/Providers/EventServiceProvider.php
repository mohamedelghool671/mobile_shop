<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\Register::class => [
            [\App\Listeners\Register::class, 'handle'],
        ],
    ];

    public function boot(): void
    {
        //
    }
}