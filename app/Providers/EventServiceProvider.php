<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\ThreadHasNewReply' => [
        //     'App\Listeners\NotifyThreadSubscribers',
        // ],

        'App\Events\ThreadReceiveNewReply' => [
            'App\Listeners\NotifyMentionedUsers',
            'App\Listeners\NotifySubscribers'
        ],

        'App\Events\ThreadWasPublished' => [
            'App\Listeners\NotifyMentionedUsers'
        ],

        /* Single Listener is not good */
        // Registered::class => [
        //     'App\Listeners\SendEmailConfirmationRequest'
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
